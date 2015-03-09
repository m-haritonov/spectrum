<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core;

class Result implements ResultInterface {
	protected $value;
	protected $details;
	
	/**
	 * @param null|bool $value
	 * @throws Exception
	 */
	public function setValue($value) {
		if ($value !== true && $value !== false && $value !== null) {
			throw new Exception('Value accepts only "true", "false" or "null"');
		}
		
		$this->value = $value;
	}

	/**
	 * @return null|bool
	 */
	public function getValue() {
		return $this->value;
	}
	
	/**
	 * @param mixed $details Exception object, some string, backtrace info, etc.
	 */
	public function setDetails($details) {
		$this->details = $details;
	}

	/**
	 * @return mixed
	 */
	public function getDetails() {
		return $this->details;
	}
}