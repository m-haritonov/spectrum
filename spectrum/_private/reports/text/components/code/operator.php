<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_private\reports\text\components\code;

class operator extends \spectrum\_private\reports\text\components\component {
	/**
	 * @param string $operator String in "US-ASCII" charset
	 * @param null|string $inputCharset
	 * @return string
	 */
	static public function getContent($operator, $inputCharset = null) {
		return static::convertToOutputCharset($operator, $inputCharset);
	}
}