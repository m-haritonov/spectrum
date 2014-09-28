<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\details;

interface PhpErrorInterface {
	/**
	 * @param int $errorLevel
	 * @param string $errorMessage
	 * @param string $file
	 * @param int $line
	 */
	public function __construct($errorLevel, $errorMessage, $file, $line);
	
	/**
	 * @return int
	 */
	public function getErrorLevel();
	
	/**
	 * @return string
	 */
	public function getErrorMessage();
	
	/**
	 * @return string
	 */
	public function getFile();
	
	/**
	 * @return int
	 */
	public function getLine();
}