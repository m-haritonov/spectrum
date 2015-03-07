<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internals\reports\text\components\code;

class property extends \spectrum\_internals\reports\text\components\component {
	/**
	 * @param string $propertyName String in "US-ASCII" charset
	 * @param null|string $inputCharset
	 * @return string
	 */
	static public function getContent($propertyName, $inputCharset = null) {
		return static::convertToOutputCharset($propertyName, $inputCharset);
	}
}