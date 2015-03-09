<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\_private\reports\text\components;

use spectrum\core\config;

class component {
	/**
	 * @param string $componentShortName
	 * @param string $methodName
	 * @return mixed
	 */
	static protected function callComponentMethod($componentShortName, $methodName, array $arguments = array()) {
		return call_user_func_array(array(config::getCoreClassReplacement('\spectrum\core\_private\reports\text\components\\' . $componentShortName), $methodName), $arguments);
	}

	/**
	 * @param int $repeat
	 * @return string
	 */
	static protected function getOutputIndention($repeat = 1) {
		return str_repeat(config::getOutputIndention(), $repeat);
	}

	/**
	 * @param int $repeat
	 * @return string
	 */
	static protected function getOutputNewline($repeat = 1) {
		return str_repeat(config::getOutputNewline(), $repeat);
	}

	/**
	 * @param string $text
	 * @param int $repeat
	 * @return string
	 */
	static protected function prependOutputIndentionToEachOutputNewline($text, $repeat = 1) {
		if ($text == '') {
			return $text;
		}
		
		$indention = static::getOutputIndention($repeat);
		$newline = static::getOutputNewline();
		return $indention . str_replace($newline, $newline . $indention, $text);
	}

	/**
	 * @param string $string
	 * @return string
	 */
	static protected function translate($string, array $replacements = array()) {
		$translateFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\translate');
		return $translateFunction($string, $replacements);
	}

	/**
	 * @param string $string
	 * @param null|string $inputCharset
	 * @return string
	 */
	static protected function convertToOutputCharset($string, $inputCharset = null) {
		$convertCharsetFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\convertCharset');
		return $convertCharsetFunction($string, $inputCharset);
	}
}