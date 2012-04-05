<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins;
use spectrum\core\Config;
use spectrum\core\plugins\Exception;

class ErrorHandling extends \spectrum\core\plugins\Plugin
{
	protected $catchExceptions;
	protected $catchPhpErrors;

	protected $breakOnFirstMatcherFail;
	protected $breakOnFirstPhpError;

	public function setCatchExceptions($isEnable)
	{
		if (!Config::getAllowErrorHandlingModify())
			throw new Exception('Error handling modify deny in Config');

		$this->catchExceptions = $isEnable;
	}

	public function getCatchExceptions()
	{
		return $this->catchExceptions;
	}

	public function getCatchExceptionsCascade()
	{
		return $this->callCascadeThroughRunningContexts('getCatchExceptions', array(), true);
	}

/**/

	/**
	 * False or 0 turn off fail on php error. True = -1 (report all PHP errors)
	 * @param int|boolean|null $errorReportingLevel
	 */
	public function setCatchPhpErrors($errorReportingLevel)
	{
		if (!Config::getAllowErrorHandlingModify())
			throw new Exception('Error handling modify deny in Config');

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

	public function getCatchPhpErrorsCascade()
	{
		return $this->callCascadeThroughRunningContexts('getCatchPhpErrors', array(), -1);
	}

	/**
	 * Affected only when setFailOnPhpError() enabled
	 */
	public function setBreakOnFirstPhpError($isEnable)
	{
		if (!Config::getAllowErrorHandlingModify())
			throw new Exception('Error handling modify deny in Config');

		$this->breakOnFirstPhpError = $isEnable;
	}

	public function getBreakOnFirstPhpError()
	{
		return $this->breakOnFirstPhpError;
	}

	public function getBreakOnFirstPhpErrorCascade()
	{
		return $this->callCascadeThroughRunningContexts('getBreakOnFirstPhpError', array(), false);
	}

/**/

	public function setBreakOnFirstMatcherFail($isEnable)
	{
		if (!Config::getAllowErrorHandlingModify())
			throw new Exception('Error handling modify deny in Config');

		$this->breakOnFirstMatcherFail = $isEnable;
	}

	public function getBreakOnFirstMatcherFail()
	{
		return $this->breakOnFirstMatcherFail;
	}

	public function getBreakOnFirstMatcherFailCascade()
	{
		return $this->callCascadeThroughRunningContexts('getBreakOnFirstMatcherFail', array(), false);
	}
}