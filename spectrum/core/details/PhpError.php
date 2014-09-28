<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\details;

class PhpError implements PhpErrorInterface {
	/**
	 * @var int
	 */
	protected $errorLevel;

	/**
	 * @var string
	 */
	protected $errorMessage;

	/**
	 * @var string
	 */
	protected $file;

	/**
	 * @var int
	 */
	protected $line;

	/**
	 * @param int $errorLevel
	 * @param string $errorMessage
	 * @param string $file
	 * @param int $line
	 */
	public function __construct($errorLevel, $errorMessage, $file, $line) {
		$this->errorLevel = $errorLevel;
		$this->errorMessage = $errorMessage;
		$this->file = $file;
		$this->line = $line;
	}

	/**
	 * @return int
	 */
	public function getErrorLevel() {
		return $this->errorLevel;
	}

	/**
	 * @return string
	 */
	public function getErrorMessage() {
		return $this->errorMessage;
	}

	/**
	 * @return string
	 */
	public function getFile() {
		return $this->file;
	}

	/**
	 * @return int
	 */
	public function getLine() {
		return $this->line;
	}
}