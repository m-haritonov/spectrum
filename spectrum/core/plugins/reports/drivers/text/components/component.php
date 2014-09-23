<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\text\components;

use spectrum\config;

class component {
	static protected function callComponentMethod($componentShortName, $methodName, $arguments = array()) {
		return call_user_func_array(array(config::getClassReplacement('\spectrum\core\plugins\reports\drivers\text\components\\' . $componentShortName), $methodName), $arguments);
	}

	static protected function getOutputIndention($repeat = 1) {
		return str_repeat(config::getOutputIndention(), $repeat);
	}
	
	static protected function getOutputNewline($repeat = 1) {
		return str_repeat(config::getOutputNewline(), $repeat);
	}
	
	static protected function prependOutputIndentionToEachOutputNewline($text, $repeat = 1) {
		if ($text == '') {
			return $text;
		}
		
		$indention = static::getOutputIndention($repeat);
		$newline = static::getOutputNewline();
		return $indention . str_replace($newline, $newline . $indention, $text);
	}
	
	static protected function translate($string, array $replacement = array()) {
		$translateFunction = config::getFunctionReplacement('\spectrum\_internals\translate');
		return $translateFunction($string, $replacement);
	}
	
	static protected function convertToOutputCharset($string, $inputCharset = null) {
		$function = config::getFunctionReplacement('\spectrum\_internals\convertCharset');
		return $function($string, $inputCharset);
	}
}