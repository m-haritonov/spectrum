<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internals\reports\text\components\code\variables;

class intVar extends \spectrum\_internals\reports\text\components\component {
	/**
	 * @param int $variable
	 * @return string
	 */
	static public function getContent($variable) {
		return static::translate('int') . ' ' . $variable;
	}
}