<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\_private\reports\text\components\code\variables;

class arrayVar extends \spectrum\core\_private\reports\text\components\component {
	/**
	 * @param int $depth
	 * @param null|string $inputCharset
	 * @return string
	 */
	static public function getContent(array $variable, $depth, $inputCharset = null) {
		$content = '';
		$content .= static::getContentForType($variable);
		$content .= static::callComponentMethod('code\operator', 'getContent', array('{', 'us-ascii')) . static::getOutputNewline();
		$content .= static::getContentForElements($variable, $depth, $inputCharset);
		
		if (count($variable)) {
			$content .= static::getOutputIndention($depth);
		}
		
		$content .= static::callComponentMethod('code\operator', 'getContent', array('}', 'us-ascii'));
		return $content;
	}

	/**
	 * @return string
	 */
	static protected function getContentForType(array $variable) {
		return static::translate('array') . '(' . count($variable) . ') ';
	}

	/**
	 * @param int $depth
	 * @param string $inputCharset
	 * @return string
	 */
	static protected function getContentForElements(array $variable, $depth, $inputCharset) {
		$content = '';
		if (count($variable)) {
			foreach ($variable as $key => $value) {
				$content .= static::getContentForElement($key, $value, $depth, $inputCharset) . static::getOutputNewline();
			}
		}
		
		return $content;
	}

	/**
	 * @param mixed $key
	 * @param mixed $value
	 * @param int $depth
	 * @param string $inputCharset
	 * @return string
	 */
	static protected function getContentForElement($key, $value, $depth, $inputCharset) {
		return
			static::getOutputIndention($depth + 1) .
			static::callComponentMethod('code\operator', 'getContent', array('[', 'us-ascii')) .
			static::convertToOutputCharset($key, $inputCharset) .
			static::callComponentMethod('code\operator', 'getContent', array(']', 'us-ascii')) . ' ' .
			static::callComponentMethod('code\operator', 'getContent', array('=>', 'us-ascii')) . ' ' .
			static::callComponentMethod('code\variable', 'getContent', array($value, $depth + 1, $inputCharset));
	}
}