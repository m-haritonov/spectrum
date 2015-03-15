<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\models\details;

class UserFail implements UserFailInterface {
	/**
	 * @var string
	 */
	protected $message;

	/**
	 * @param string $message
	 */
	public function __construct($message) {
		$this->message = $message;
	}

	/**
	 * @return string
	 */
	public function getMessage() {
		return $this->message;
	}
}