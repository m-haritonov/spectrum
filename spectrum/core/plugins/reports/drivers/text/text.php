<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\text;

use spectrum\config;
use spectrum\core\SpecInterface;

class text {
	static public function getContentBeforeSpec(SpecInterface $spec) {
		$output = '';
		
		if (!$spec->getParentSpecs()) {
			$output .= static::getHeader() . static::getOutputNewline(2);
		}

		$output .= static::callComponentMethod('specList', 'getContentBegin', array($spec));
		
		return $output;
	}

	static public function getContentAfterSpec(SpecInterface $spec) {
		$output = '';
		$output .= static::callComponentMethod('specList', 'getContentEnd', array($spec));
		
		if (!$spec->getParentSpecs()) {
			$output .= static::callComponentMethod('totalInfo', 'getContent', array($spec)) . static::getOutputNewline();
			$output .= static::getFooter();
		}
		
		return $output;
	}
	
	static protected function getHeader() {
		$title = static::translate('Spectrum framework report');
		return
			str_repeat('=', mb_strlen($title, config::getOutputCharset())) . static::getOutputNewline() .
			$title . static::getOutputNewline() .
			str_repeat('=', mb_strlen($title, config::getOutputCharset()));
	}

	static protected function getFooter() {}
	
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
	
	static protected function formatTextForOutput($text, $indentionToRemoveCount = 0) {
		$function = config::getFunctionReplacement('\spectrum\_internals\formatTextForOutput');
		return $function($text, $indentionToRemoveCount, "\t", "\n", config::getOutputIndention(), config::getOutputNewline());
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