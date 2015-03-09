<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_private\reports\html\components;

use spectrum\core\config;

class component {
	/**
	 * @return null
	 */
	static public function getStyles() {
		return null;
	}

	/**
	 * @return null
	 */
	static public function getScripts() {
		return null;
	}

	/**
	 * @param string $componentShortName
	 * @param string $methodName
	 * @return mixed
	 */
	static protected function callComponentMethod($componentShortName, $methodName, array $arguments = array()) {
		return call_user_func_array(array(config::getClassReplacement('\spectrum\_private\reports\html\components\\' . $componentShortName), $methodName), $arguments);
	}

	/**
	 * @param string $html
	 * @return string
	 */
	static protected function escapeHtml($html) {
		return htmlspecialchars($html, ENT_QUOTES, 'iso-8859-1');
	}

	/**
	 * @param int $repeat
	 * @return string
	 */
	static protected function getHtmlEscapedOutputIndention($repeat = 1) {
		return str_repeat(static::escapeHtml(config::getOutputIndention()), $repeat);
	}

	/**
	 * @param int $repeat
	 * @return string
	 */
	static protected function getHtmlEscapedOutputNewline($repeat = 1) {
		return str_repeat(static::escapeHtml(config::getOutputNewline()), $repeat);
	}

	/**
	 * @param string $text
	 * @param int $repeat
	 * @return string
	 */
	static protected function prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline($text, $repeat = 1) {
		if ($text == '') {
			return $text;
		}
		
		$indention = static::getHtmlEscapedOutputIndention($repeat);
		$newline = static::getHtmlEscapedOutputNewline();
		return $indention . str_replace($newline, $newline . $indention, $text);
	}

	/**
	 * @param string $text
	 * @param int $indentionToRemoveCount
	 * @return string
	 */
	static protected function formatTextForOutput($text, $indentionToRemoveCount = 0) {
		$formatTextForOutputFunction = config::getFunctionReplacement('\spectrum\_private\formatTextForOutput');
		return $formatTextForOutputFunction($text, $indentionToRemoveCount, "\t", "\n", static::escapeHtml(config::getOutputIndention()), static::escapeHtml(config::getOutputNewline()));
	}

	/**
	 * @param string $string
	 * @return string
	 */
	static protected function translateAndEscapeHtml($string, array $replacements = array()) {
		$translateFunction = config::getFunctionReplacement('\spectrum\_private\translate');
		return static::escapeHtml($translateFunction($string, $replacements));
	}

	/**
	 * @param string $string
	 * @param null|string $inputCharset
	 * @return string
	 */
	static protected function convertToOutputCharset($string, $inputCharset = null) {
		$convertCharsetFunction = config::getFunctionReplacement('\spectrum\_private\convertCharset');
		return $convertCharsetFunction($string, $inputCharset);
	}
}