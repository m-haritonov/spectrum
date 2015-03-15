<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\models;

interface ErrorHandlingInterface {
	public function __construct(SpecInterface $ownerSpec);
	
	/**
	 * False or "0" is turn off PHP errors catching. True = -1 (catches all PHP errors).
	 * @param null|int|boolean $errorReportingLevel
	 */
	public function setCatchPhpErrors($errorReportingLevel);

	/**
	 * @return null|int
	 */
	public function getCatchPhpErrors();

	/**
	 * @return int
	 */
	public function getCatchPhpErrorsThroughRunningAncestors();
	
/**/

	/**
	 * Affected only when getCatchPhpErrorsThroughRunningAncestors() is not "0"
	 * @param bool $isEnable
	 */
	public function setBreakOnFirstPhpError($isEnable);

	/**
	 * @return null|bool
	 */
	public function getBreakOnFirstPhpError();

	/**
	 * @return bool
	 */
	public function getBreakOnFirstPhpErrorThroughRunningAncestors();

/**/

	/**
	 * @param bool $isEnable
	 */
	public function setBreakOnFirstMatcherFail($isEnable);

	/**
	 * @return null|bool
	 */
	public function getBreakOnFirstMatcherFail();

	/**
	 * @return bool
	 */
	public function getBreakOnFirstMatcherFailThroughRunningAncestors();
}