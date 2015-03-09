<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\_private\reports\text\components\code\variables;

class resourceVar extends \spectrum\core\_private\reports\text\components\component {
	/**
	 * @param resource $variable
	 * @param null|string $inputCharset
	 * @return string
	 */
	static public function getContent($variable, $inputCharset = null) {
		return static::translate('resource') . ' ' . static::convertToOutputCharset(print_r($variable, true), $inputCharset);
	}
}