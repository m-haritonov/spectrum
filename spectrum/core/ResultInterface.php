<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core;

interface ResultInterface {
	/**
	 * @param null|bool $value
	 */
	public function setValue($value);

	/**
	 * @return null|bool
	 */
	public function getValue();
	
	/**
	 * @param mixed $details Exception object, some string, backtrace info, etc.
	 */
	public function setDetails($details);

	/**
	 * @return mixed
	 */
	public function getDetails();
}