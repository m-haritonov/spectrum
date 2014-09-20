<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\details;

interface PhpErrorInterface {
	public function __construct($errorLevel, $errorMessage, $file, $line);
	public function getErrorLevel();
	public function getErrorMessage();
	public function getFile();
	public function getLine();
}