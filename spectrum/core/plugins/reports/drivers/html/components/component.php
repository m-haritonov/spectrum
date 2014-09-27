<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\html\components;

use spectrum\config;

class component {
	static public function getStyles() {
		return null;
	}

	static public function getScripts() {
		return null;
	}
	
	static protected function callComponentMethod($componentShortName, $methodName, $arguments = array()) {
		return call_user_func_array(array(config::getClassReplacement('\spectrum\core\plugins\reports\drivers\html\components\\' . $componentShortName), $methodName), $arguments);
	}
	
	static protected function escapeHtml($html) {
		return htmlspecialchars($html, ENT_QUOTES, 'iso-8859-1');
	}

	static protected function getHtmlEscapedOutputIndention($repeat = 1) {
		return str_repeat(static::escapeHtml(config::getOutputIndention()), $repeat);
	}
	
	static protected function getHtmlEscapedOutputNewline($repeat = 1) {
		return str_repeat(static::escapeHtml(config::getOutputNewline()), $repeat);
	}
	
	static protected function prependHtmlEscapedOutputIndentionToEachHtmlEscapedOutputNewline($text, $repeat = 1) {
		if ($text == '') {
			return $text;
		}
		
		$indention = static::getHtmlEscapedOutputIndention($repeat);
		$newline = static::getHtmlEscapedOutputNewline();
		return $indention . str_replace($newline, $newline . $indention, $text);
	}
	
	static protected function formatTextForOutput($text, $indentionToRemoveCount = 0) {
		$function = config::getFunctionReplacement('\spectrum\_internals\formatTextForOutput');
		return $function($text, $indentionToRemoveCount, "\t", "\n", static::escapeHtml(config::getOutputIndention()), static::escapeHtml(config::getOutputNewline()));
	}
	
	static protected function translateAndEscapeHtml($string, array $replacements = array()) {
		$translateFunction = config::getFunctionReplacement('\spectrum\_internals\translate');
		return static::escapeHtml($translateFunction($string, $replacements));
	}
	
	static protected function convertToOutputCharset($string, $inputCharset = null) {
		$function = config::getFunctionReplacement('\spectrum\_internals\convertCharset');
		return $function($string, $inputCharset);
	}
}