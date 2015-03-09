<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\_private\reports\text\components;

use spectrum\core\config;
use spectrum\core\SpecInterface;

class totalInfo extends component {
	/**
	 * @return null|string
	 */
	static public function getContent(SpecInterface $spec) {
		if ($spec->getParentSpecs()) {
			return null;
		}
		
		$content = static::translate('Total result') . ': ' . static::callComponentMethod('totalResult', 'getContent', array($spec));
		return
			static::getOutputNewline() .
			str_repeat('=', mb_strlen($content, config::getOutputCharset())) . static::getOutputNewline() .
			$content . static::getOutputNewline() .
			str_repeat('=', mb_strlen($content, config::getOutputCharset())) . static::getOutputNewline();
	}
}