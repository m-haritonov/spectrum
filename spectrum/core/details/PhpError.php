<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\details;

class PhpError implements PhpErrorInterface
{
	protected $errorLevel;
	protected $errorMessage;
	protected $file;
	protected $line;

	public function __construct($errorLevel, $errorMessage, $file, $line)
	{
		$this->errorLevel = $errorLevel;
		$this->errorMessage = $errorMessage;
		$this->file = $file;
		$this->line = $line;
	}
	
	public function getErrorLevel(){ return $this->errorLevel; }
	public function getErrorMessage(){ return $this->errorMessage; }
	public function getFile(){ return $this->file; }
	public function getLine(){ return $this->line; }
}