<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_private\reports\text\components\code\variables;

class unknownVar extends \spectrum\_private\reports\text\components\component {
	/**
	 * @param mixed $variable
	 * @param null|string $inputCharset
	 * @return string
	 */
	static public function getContent($variable, $inputCharset = null) {
		return static::translate('unknown') . ' ' . static::convertToOutputCharset(print_r($variable, true), $inputCharset);
	}
}