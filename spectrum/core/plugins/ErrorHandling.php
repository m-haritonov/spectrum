<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins;

use spectrum\config;
use spectrum\core\BreakException;
use spectrum\core\details\MatcherCallInterface;
use spectrum\Exception;

class ErrorHandling extends \spectrum\core\plugins\Plugin {
	/**
	 * @var null|int
	 */
	protected $catchPhpErrors;

	/**
	 * @var null|bool
	 */
	protected $breakOnFirstPhpError;

	/**
	 * @var null|bool
	 */
	protected $breakOnFirstMatcherFail;

	/**
	 * @var null|\Closure
	 */
	protected $errorHandler;

	/**
	 * @var null|int
	 */
	protected $errorReportingBackup;

	/**
	 * @return string
	 */
	static public function getAccessName() {
		return 'errorHandling';
	}

	/**
	 * @return array
	 */
	static public function getEventListeners() {
		return array(
			array('event' => 'onEndingSpecExecuteBefore', 'method' => 'onEndingSpecExecuteBefore', 'order' => 10),
			array('event' => 'onEndingSpecExecuteAfter', 'method' => 'onEndingSpecExecuteAfter', 'order' => -10),
			array('event' => 'onMatcherCallFinish', 'method' => 'onMatcherCallFinish', 'order' => -10),
		);
	}
	
/**/

	/**
	 * False or "0" is turn off PHP errors catching. True = -1 (catches all PHP errors).
	 * @param null|int|boolean $errorReportingLevel
	 */
	public function setCatchPhpErrors($errorReportingLevel) {
		$this->handleModifyDeny(__FUNCTION__);
		
		if (!config::getAllowErrorHandlingModify()) {
			throw new Exception('Error handling modify deny in config');
		}

		if ($errorReportingLevel === true) {
			$errorReportingLevel = -1;
		} else if ($errorReportingLevel !== null) {
			$errorReportingLevel = (int) $errorReportingLevel;
		}

		$this->catchPhpErrors = $errorReportingLevel;
	}

	/**
	 * @return null|int
	 */
	public function getCatchPhpErrors() {
		return $this->catchPhpErrors;
	}

	/**
	 * @return int
	 */
	public function getCatchPhpErrorsThroughRunningAncestors() {
		return $this->callMethodThroughRunningAncestorSpecs('getCatchPhpErrors', array(), -1);
	}
	
/**/

	/**
	 * Affected only when getCatchPhpErrorsThroughRunningAncestors() is not "0"
	 * @param bool $isEnable
	 */
	public function setBreakOnFirstPhpError($isEnable) {
		$this->handleModifyDeny(__FUNCTION__);
		
		if (!config::getAllowErrorHandlingModify()) {
			throw new Exception('Error handling modify deny in config');
		}

		$this->breakOnFirstPhpError = $isEnable;
	}

	/**
	 * @return null|bool
	 */
	public function getBreakOnFirstPhpError() {
		return $this->breakOnFirstPhpError;
	}

	/**
	 * @return bool
	 */
	public function getBreakOnFirstPhpErrorThroughRunningAncestors() {
		return $this->callMethodThroughRunningAncestorSpecs('getBreakOnFirstPhpError', array(), false);
	}

/**/

	/**
	 * @param bool $isEnable
	 */
	public function setBreakOnFirstMatcherFail($isEnable) {
		$this->handleModifyDeny(__FUNCTION__);
		
		if (!config::getAllowErrorHandlingModify()) {
			throw new Exception('Error handling modify deny in config');
		}

		$this->breakOnFirstMatcherFail = $isEnable;
	}

	/**
	 * @return null|bool
	 */
	public function getBreakOnFirstMatcherFail() {
		return $this->breakOnFirstMatcherFail;
	}

	/**
	 * @return bool
	 */
	public function getBreakOnFirstMatcherFailThroughRunningAncestors() {
		return $this->callMethodThroughRunningAncestorSpecs('getBreakOnFirstMatcherFail', array(), false);
	}
	
/**/
	
	protected function onEndingSpecExecuteBefore() {
		$this->errorReportingBackup = error_reporting($this->getCatchPhpErrorsThroughRunningAncestors());
		
		$thisObject = $this;
		$this->errorHandler = function($errorLevel, $errorMessage, $file, $line) use($thisObject) {
			if (!($errorLevel & error_reporting())) {
				return;
			}
			
			$phpErrorDetailsClass = config::getClassReplacement('\spectrum\core\details\PhpError');
			$thisObject->getOwnerSpec()->getResultBuffer()->addResult(false, new $phpErrorDetailsClass($errorLevel, $errorMessage, $file, $line));

			if ($thisObject->getBreakOnFirstPhpErrorThroughRunningAncestors()) {
				throw new BreakException();
			}
		};
		
		set_error_handler($this->errorHandler, -1);
	}
	
	protected function onEndingSpecExecuteAfter() {
		$this->removeSubsequentErrorHandlers($this->errorHandler);
		
		if ($this->getLastErrorHandler() === $this->errorHandler) {
			restore_error_handler();
		} else {
			$this->getOwnerSpec()->getResultBuffer()->addResult(false, 'Error handler was removed');
		}

		$this->errorHandler = null;
		error_reporting($this->errorReportingBackup);
	}

	/**
	 * @param callable $checkErrorHandler
	 */
	protected function removeSubsequentErrorHandlers($checkErrorHandler) {
		$errorHandlers = array();
		while ($lastErrorHandler = $this->getLastErrorHandler()) {
			if ($lastErrorHandler === $checkErrorHandler) {
				$errorHandlers = array();
				break;
			}
			
			$errorHandlers[] = $lastErrorHandler;
			restore_error_handler();
		}
		
		// Rollback all error handlers if $checkErrorHandler is not find
		foreach (array_reverse($errorHandlers) as $errorHandler) {
			set_error_handler($errorHandler);
		}
	}

	/**
	 * @return null|callable
	 */
	protected function getLastErrorHandler() {
		$lastErrorHandler = set_error_handler(function($errorSeverity, $errorMessage){});
		restore_error_handler();
		return $lastErrorHandler;
	}
		
		
	protected function onMatcherCallFinish(MatcherCallInterface $callDetails) {
		if (!$callDetails->getResult() && $this->getBreakOnFirstMatcherFailThroughRunningAncestors()) {
			throw new BreakException();
		}
	}
}