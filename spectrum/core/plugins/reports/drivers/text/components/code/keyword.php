<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\text\components\code;

class keyword extends \spectrum\core\plugins\reports\drivers\text\components\component {
	/**
	 * @param string $keyword String in "US-ASCII" charset
	 * @param null|string $inputCharset
	 * @return string
	 */
	static public function getContent($keyword, $inputCharset = null) {
		return static::convertToOutputCharset($keyword, $inputCharset);
	}
}