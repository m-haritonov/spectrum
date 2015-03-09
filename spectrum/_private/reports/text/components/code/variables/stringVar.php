<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_private\reports\text\components\code\variables;

use spectrum\core\config;

class stringVar extends \spectrum\_private\reports\text\components\component {
	/**
	 * @param string $variable
	 * @param null|string $inputCharset
	 * @return string
	 */
	static public function getContent($variable, $inputCharset = null) {
		return
			static::getContentForType($variable, $inputCharset) .
			static::getContentForValue($variable, $inputCharset);
	}

	/**
	 * @param string $variable
	 * @param string $inputCharset
	 * @return string
	 */
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

	/**
	 * @param string $variable
	 * @param string $inputCharset
	 * @return string
	 */
	static protected function getContentForValue($variable, $inputCharset) {
		return '"' . static::highlightSpaces(static::convertToOutputCharset($variable, $inputCharset)) . '"';
	}

	/**
	 * @param string $string
	 * @return string
	 */
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