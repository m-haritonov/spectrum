<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\details;

interface UserFailInterface {
	public function __construct($message);
	public function getMessage();
}