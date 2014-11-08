<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\text;

use spectrum\config;
use spectrum\core\SpecInterface;

class text {
	/**
	 * @return string
	 */
	static public function getContentBeforeSpec(SpecInterface $spec) {
		$content = '';
		
		if (!$spec->getParentSpecs()) {
			$content .= static::getHeader() . static::getOutputNewline(2);
		}

		$content .= static::callComponentMethod('specList', 'getContentBegin', array($spec));
		
		return $content;
	}

	/**
	 * @return string
	 */
	static public function getContentAfterSpec(SpecInterface $spec) {
		$content = '';
		$content .= static::callComponentMethod('specList', 'getContentEnd', array($spec));
		
		if (!$spec->getParentSpecs()) {
			$content .= static::callComponentMethod('totalInfo', 'getContent', array($spec)) . static::getOutputNewline();
			$content .= static::getFooter();
		}
		
		return $content;
	}

	/**
	 * @return string
	 */
	static protected function getHeader() {
		$title = static::translate('Spectrum framework') . ' ' . config::getVersion() . ' (http://m-haritonov.net/projects/spectrum)';
		return
			str_repeat('=', mb_strlen($title, config::getOutputCharset())) . static::getOutputNewline() .
			$title . static::getOutputNewline() .
			str_repeat('=', mb_strlen($title, config::getOutputCharset()));
	}

	static protected function getFooter() {
		
	}

	/**
	 * @param string $componentShortName
	 * @param string $methodName
	 * @return mixed
	 */
	static protected function callComponentMethod($componentShortName, $methodName, array $arguments = array()) {
		return call_user_func_array(array(config::getClassReplacement('\spectrum\core\plugins\reports\drivers\text\components\\' . $componentShortName), $methodName), $arguments);
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
		$translateFunction = config::getFunctionReplacement('\spectrum\_internals\translate');
		return $translateFunction($string, $replacements);
	}

	/**
	 * @param string $string
	 * @param null|string $inputCharset
	 * @return string
	 */
	static protected function convertToOutputCharset($string, $inputCharset = null) {
		$convertCharsetFunction = config::getFunctionReplacement('\spectrum\_internals\convertCharset');
		return $convertCharsetFunction($string, $inputCharset);
	}
}