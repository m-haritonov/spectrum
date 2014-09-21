<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\text\components\code\variables;

use spectrum\config;

class stringVar extends \spectrum\core\plugins\reports\drivers\text\components\component {
	static public function getContent($variable, $inputCharset = null) {
		return
			static::getContentForType($variable, $inputCharset) .
			static::getContentForValue($variable, $inputCharset);
	}

	static protected function getContentForType($variable, $inputCharset) {
		if ($inputCharset === null) {
			$inputCharset = config::getInputCharset();
		}
		
		return
			static::translate('string') .
			'(' . 
			static::translate('%count% chars', array('%count%' => mb_strlen($variable, $inputCharset))) . ', ' .
			static::translate('%count% bytes', array('%count%' => mb_strlen($variable, 'us-ascii'))) .
			') ';
	}

	static protected function getContentForValue($variable, $inputCharset) {
		return '"' . static::highlightSpaces(static::convertToOutputCharset($variable, $inputCharset)) . '"';
	}

	static protected function highlightSpaces($string) {
		return strtr($string, array(
			'\t' => '\\\\t',
			'\r' => '\\\\r',
			'\n' => '\\\\n',
			"\t" => '\t',
			"\r" => '\r',
			"\n" => '\n',
		));
	}
}