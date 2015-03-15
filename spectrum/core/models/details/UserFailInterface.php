<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\models\details;

interface UserFailInterface {
	/**
	 * @param string $message
	 */
	public function __construct($message);
	
	/**
	 * @return string
	 */
	public function getMessage();
}