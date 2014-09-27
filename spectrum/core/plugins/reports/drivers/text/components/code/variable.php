<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\text\components\code;

use spectrum\config;

class variable extends \spectrum\core\plugins\reports\drivers\text\components\component {
	static protected $previousVariables = array();
	
	static public function getContent($variable, $depth = 0, $inputCharset = null) {
		$convertLatinCharsToLowerCaseFunction = config::getFunctionReplacement('\spectrum\_internals\convertLatinCharsToLowerCase');
		$type = $convertLatinCharsToLowerCaseFunction(gettype($variable));
		
		if (is_object($variable)) {
			static::$previousVariables[$depth] = $variable;
		}
		
		$previousVariableDepth = array_search($variable, static::$previousVariables, true);

		if ($previousVariableDepth !== false && $previousVariableDepth < $depth) {
			return static::callComponentMethod('code\variables\recursionVar', 'getContent', array($variable));	
		} else if ($type === 'boolean') {
			return static::callComponentMethod('code\variables\boolVar', 'getContent', array($variable));
		} else if ($type === 'integer') {
			return static::callComponentMethod('code\variables\intVar', 'getContent', array($variable));
		} else if ($type === 'double') {
			return static::callComponentMethod('code\variables\floatVar', 'getContent', array($variable));
		} else if ($type === 'string') {
			return static::callComponentMethod('code\variables\stringVar', 'getContent', array($variable, $inputCharset));
		} else if ($type === 'array') {
			return static::callComponentMethod('code\variables\arrayVar', 'getContent', array($variable, $depth, $inputCharset));
		} else if ($type === 'object') {
			$closure = function(){};
			if ($variable instanceof $closure) {
				return static::callComponentMethod('code\variables\functionVar', 'getContent', array($variable, $inputCharset));
			} else {
				return static::callComponentMethod('code\variables\objectVar', 'getContent', array($variable, $depth, $inputCharset));
			}
		} else if ($type === 'resource') {
			return static::callComponentMethod('code\variables\resourceVar', 'getContent', array($variable, $inputCharset));
		} else if ($type === 'null') {
			return static::callComponentMethod('code\variables\nullVar', 'getContent');
		} else {
			return static::callComponentMethod('code\variables\unknownVar', 'getContent', array($variable, $inputCharset));
		}
	}
}