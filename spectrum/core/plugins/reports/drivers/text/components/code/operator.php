<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\text\components\code;

class operator extends \spectrum\core\plugins\reports\drivers\text\components\component {
	/**
	 * @param string $operator String in "us-ascii" charset
	 * @return string
	 */
	static public function getContent($operator, $inputCharset = null) {
		return static::convertToOutputCharset($operator, $inputCharset);
	}
}