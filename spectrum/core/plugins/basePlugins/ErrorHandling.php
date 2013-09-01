<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins;
use spectrum\config;
use spectrum\core\ExceptionBreak;
use spectrum\core\ExceptionPhpError;
use spectrum\core\MatcherCallDetailsInterface;
use spectrum\core\plugins\Exception;

class ErrorHandling extends \spectrum\core\plugins\Plugin
{
	protected $catchPhpErrors;
	protected $breakOnFirstPhpError;
	protected $breakOnFirstMatcherFail;
	protected $isErrorHandlerSets = false;

	static public function getAccessName()
	{
		return 'errorHandling';
	}
	
	static public function getEventListeners()
	{
		return array(
			array('event' => 'onEndingSpecExecuteBefore', 'method' => 'onEndingSpecExecuteBefore', 'order' => 10),
			array('event' => 'onEndingSpecExecuteAfter', 'method' => 'onEndingSpecExecuteAfter', 'order' => -10),
			array('event' => 'onMatcherCallAfter', 'method' => 'onMatcherCallAfter', 'order' => -10),
		);
	}

	/**
	 * False or 0 turn off fail on php error. True = -1 (catch all PHP errors)
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

	/**
	 * Affected only when setFailOnPhpError() enabled
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
		$catchPhpErrors = $this->getCatchPhpErrorsThroughRunningAncestors();

		if (!$catchPhpErrors)
			return;

		$this->isErrorHandlerSets = true;

		$thisObject = $this;
		set_error_handler(function($severity, $message, $file, $line) use($thisObject)
		{
			if (error_reporting() == 0)
				return;

			$thisObject->getOwnerSpec()->getResultBuffer()->addResult(false, new ExceptionPhpError($message, 0, $severity, $file, $line));

			if ($thisObject->getBreakOnFirstPhpErrorThroughRunningAncestors())
				throw new ExceptionBreak();

		}, $catchPhpErrors);
	}
	
	protected function onEndingSpecExecuteAfter()
	{
		if ($this->isErrorHandlerSets)
			restore_error_handler();

		$this->isErrorHandlerSets = false;
	}
	
	protected function onMatcherCallAfter(MatcherCallDetailsInterface $callDetails)
	{
		if (!$callDetails->getResult() && $this->getBreakOnFirstMatcherFailThroughRunningAncestors())
			throw new ExceptionBreak();
	}
}