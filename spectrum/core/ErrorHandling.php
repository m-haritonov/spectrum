<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core;

class ErrorHandling implements ErrorHandlingInterface {
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
	 * @var SpecInterface
	 */
	protected $ownerSpec;
	
	public function __construct(SpecInterface $ownerSpec) {
		$this->ownerSpec = $ownerSpec;
	}

	/**
	 * False or "0" is turn off PHP errors catching. True = -1 (catches all PHP errors).
	 * @param null|int|boolean $errorReportingLevel
	 */
	public function setCatchPhpErrors($errorReportingLevel) {
		$handleSpecModifyDenyFunction = config::getFunctionReplacement('\spectrum\_private\handleSpecModifyDeny');
		$handleSpecModifyDenyFunction($this->ownerSpec, $this, __FUNCTION__);
		
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
		$callMethodThroughRunningAncestorSpecsFunction = config::getFunctionReplacement('\spectrum\_private\callMethodThroughRunningAncestorSpecs');
		return $callMethodThroughRunningAncestorSpecsFunction($this->ownerSpec, 'getErrorHandling->getCatchPhpErrors', array(), -1);
	}
	
/**/

	/**
	 * Affected only when getCatchPhpErrorsThroughRunningAncestors() is not "0"
	 * @param bool $isEnable
	 */
	public function setBreakOnFirstPhpError($isEnable) {
		$handleSpecModifyDenyFunction = config::getFunctionReplacement('\spectrum\_private\handleSpecModifyDeny');
		$handleSpecModifyDenyFunction($this->ownerSpec, $this, __FUNCTION__);
		
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
		$callMethodThroughRunningAncestorSpecsFunction = config::getFunctionReplacement('\spectrum\_private\callMethodThroughRunningAncestorSpecs');
		return $callMethodThroughRunningAncestorSpecsFunction($this->ownerSpec, 'getErrorHandling->getBreakOnFirstPhpError', array(), false);
	}

/**/

	/**
	 * @param bool $isEnable
	 */
	public function setBreakOnFirstMatcherFail($isEnable) {
		$handleSpecModifyDenyFunction = config::getFunctionReplacement('\spectrum\_private\handleSpecModifyDeny');
		$handleSpecModifyDenyFunction($this->ownerSpec, $this, __FUNCTION__);
		
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
		$callMethodThroughRunningAncestorSpecsFunction = config::getFunctionReplacement('\spectrum\_private\callMethodThroughRunningAncestorSpecs');
		return $callMethodThroughRunningAncestorSpecsFunction($this->ownerSpec, 'getErrorHandling->getBreakOnFirstMatcherFail', array(), false);
	}
}