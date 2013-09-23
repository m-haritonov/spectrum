<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\errorHandling;
use spectrum\config;
use spectrum\core\BreakException;
use spectrum\core\MatcherCallDetailsInterface;
use spectrum\core\plugins\Exception;

class ErrorHandling extends \spectrum\core\plugins\Plugin
{
	protected $catchPhpErrors;
	protected $breakOnFirstPhpError;
	protected $breakOnFirstMatcherFail;
	protected $errorHandler;
	protected $errorReportingBackup;

	static public function getAccessName()
	{
		return 'errorHandling';
	}
	
	static public function getEventListeners()
	{
		return array(
			array('event' => 'onEndingSpecExecuteBefore', 'method' => 'onEndingSpecExecuteBefore', 'order' => 10),
			array('event' => 'onEndingSpecExecuteAfter', 'method' => 'onEndingSpecExecuteAfter', 'order' => -10),
			array('event' => 'onMatcherCallFinish', 'method' => 'onMatcherCallFinish', 'order' => -10),
		);
	}
	
/**/

	/**
	 * False or "0" is turn off PHP errors catching. True = -1 (catches all PHP errors).
	 * @param int|boolean|null $errorReportingLevel
	 */
	public function setCatchPhpErrors($errorReportingLevel)
	{
		$this->handleModifyDeny();
		
		if (!config::getAllowErrorHandlingModify())
			throw new Exception('Error handling modify deny in config');

		if ($errorReportingLevel === true)
			$errorReportingLevel = -1;
		else if ($errorReportingLevel !== null)
			$errorReportingLevel = (int) $errorReportingLevel;

		$this->catchPhpErrors = $errorReportingLevel;
	}

	public function getCatchPhpErrors()
	{
		return $this->catchPhpErrors;
	}

	public function getCatchPhpErrorsThroughRunningAncestors()
	{
		return $this->callMethodThroughRunningAncestorSpecs('getCatchPhpErrors', array(), -1);
	}
	
/**/

	/**
	 * Affected only when getCatchPhpErrorsThroughRunningAncestors() is not "0"
	 */
	public function setBreakOnFirstPhpError($isEnable)
	{
		$this->handleModifyDeny();
		
		if (!config::getAllowErrorHandlingModify())
			throw new Exception('Error handling modify deny in config');

		$this->breakOnFirstPhpError = $isEnable;
	}

	public function getBreakOnFirstPhpError()
	{
		return $this->breakOnFirstPhpError;
	}

	public function getBreakOnFirstPhpErrorThroughRunningAncestors()
	{
		return $this->callMethodThroughRunningAncestorSpecs('getBreakOnFirstPhpError', array(), false);
	}

/**/

	public function setBreakOnFirstMatcherFail($isEnable)
	{
		$this->handleModifyDeny();
		
		if (!config::getAllowErrorHandlingModify())
			throw new Exception('Error handling modify deny in config');

		$this->breakOnFirstMatcherFail = $isEnable;
	}

	public function getBreakOnFirstMatcherFail()
	{
		return $this->breakOnFirstMatcherFail;
	}

	public function getBreakOnFirstMatcherFailThroughRunningAncestors()
	{
		return $this->callMethodThroughRunningAncestorSpecs('getBreakOnFirstMatcherFail', array(), false);
	}
	
/**/
	
	protected function onEndingSpecExecuteBefore()
	{
		$this->errorReportingBackup = error_reporting($this->getCatchPhpErrorsThroughRunningAncestors());
		
		$thisObject = $this;
		$this->errorHandler = function($errorSeverity, $errorMessage, $file, $line) use($thisObject)
		{
			if (!($errorSeverity & error_reporting()))
				return;
			
			$thisObject->getOwnerSpec()->getResultBuffer()->addResult(false, new ErrorException($errorMessage, 0, $errorSeverity, $file, $line));

			if ($thisObject->getBreakOnFirstPhpErrorThroughRunningAncestors())
				throw new BreakException();
		};
		
		set_error_handler($this->errorHandler, -1);
	}
	
	protected function onEndingSpecExecuteAfter()
	{
		$this->removeSubsequentErrorHandlers($this->errorHandler);
		
		if ($this->getLastErrorHandler() === $this->errorHandler)
			restore_error_handler();
		else
			$this->getOwnerSpec()->getResultBuffer()->addResult(false, 'Error handler in spec "' . $this->getOwnerSpec()->getName() . '" was removed');

		$this->errorHandler = null;
		error_reporting($this->errorReportingBackup);
	}
	
	protected function removeSubsequentErrorHandlers($checkErrorHandler)
	{
		$errorHandlers = array();
		while (true)
		{
			$lastErrorHandler = $this->getLastErrorHandler();
			if ($lastErrorHandler === null)
				break;
			
			if ($lastErrorHandler === $checkErrorHandler)
			{
				$errorHandlers = array();
				break;
			}
			
			$errorHandlers[] = $lastErrorHandler;
			restore_error_handler();
		}
		
		// Rollback all error handlers if $checkErrorHandler is not find
		foreach (array_reverse($errorHandlers) as $errorHandler)
			set_error_handler($errorHandler);
	}
	
	protected function getLastErrorHandler()
	{
		$lastErrorHandler = set_error_handler(function($errorSeverity, $errorMessage){});
		restore_error_handler();
		return $lastErrorHandler;
	}
		
		
	protected function onMatcherCallFinish(MatcherCallDetailsInterface $callDetails)
	{
		if (!$callDetails->getResult() && $this->getBreakOnFirstMatcherFailThroughRunningAncestors())
			throw new BreakException();
	}
}