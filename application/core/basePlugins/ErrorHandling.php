<?php
/*
 * Spectrum
 *
 * Copyright (c) 2011 Mikhail Kharitonov <mvkharitonov@gmail.com>
 * All rights reserved.
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 */

namespace net\mkharitonov\spectrum\core\basePlugins;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class ErrorHandling extends \net\mkharitonov\spectrum\core\plugin\Plugin
{
	protected $catchExceptions = false;
	protected $catchPhpErrors = false;

	protected $breakOnFirstMatcherFail = false;
	protected $breakOnFirstPhpError = false;

	public function setCatchExceptions($isEnable)
	{
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
		return $this->callCascadeThroughRunningContexts('getCatchPhpErrors', array(), true);
	}

	/**
	 * Affected only when setFailOnPhpError() enabled
	 */
	public function setBreakOnFirstPhpError($isEnable)
	{
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