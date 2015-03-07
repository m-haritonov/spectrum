<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_private\reports\text\components\code\variables;

class nullVar extends \spectrum\_private\reports\text\components\component {
	/**
	 * @return string
	 */
	static public function getContent() {
		return 'null';
	}
}