<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\text\components\code\variables;

class arrayVar extends \spectrum\core\plugins\reports\drivers\text\components\component {
	static public function getContent($variable, $depth, $inputCharset = null) {
		$output = '';
		$output .= static::getContentForType($variable);
		$output .= static::callComponentMethod('code\operator', 'getContent', array('{', 'us-ascii')) . static::getOutputNewline();
		$output .= static::getContentForElements($variable, $depth, $inputCharset);
		
		if (count($variable)) {
			$output .= static::getOutputIndention($depth);
		}
		
		$output .= static::callComponentMethod('code\operator', 'getContent', array('}', 'us-ascii'));
		return $output;
	}
	
	static protected function getContentForType($variable) {
		return static::translate('array') . '(' . count($variable) . ') ';
	}
	
	static protected function getContentForElements($variable, $depth, $inputCharset) {
		$output = '';
		if (count($variable)) {
			foreach ($variable as $key => $value) {
				$output .= static::getContentForElement($key, $value, $depth, $inputCharset) . static::getOutputNewline();
			}
		}
		
		return $output;
	}
	
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