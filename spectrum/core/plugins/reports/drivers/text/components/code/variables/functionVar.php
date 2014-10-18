<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\text\components\code\variables;

class functionVar extends \spectrum\core\plugins\reports\drivers\text\components\component {
	/**
	 * @param \Closure $variable
	 * @param null|string $inputCharset
	 * @return string
	 */
	static public function getContent($variable, $inputCharset = null) {
		return 'function(' . static::getContentForParameters($variable, $inputCharset) . '){}';
	}
	
	static protected function getContentForParameters($variable, $inputCharset) {
		$content = '';
		$reflection = new \ReflectionFunction($variable);
		$parameters = $reflection->getParameters();
		$parametersCount = count($parameters);
		
		$i = 0;
		foreach ($parameters as $parameter) {
			$i++;
			
			$reflectionClass = $parameter->getClass();
			if ($reflectionClass) {
				$content .= '\\' . static::convertToOutputCharset($reflectionClass->getName(), $inputCharset) . ' ';
			}
			
			if ($parameter->isArray()) {
				$content .= 'array ';
			} else if (method_exists($parameter, 'isCallable') && $parameter->isCallable()) {
				$content .= 'callable ';
			}
			
			if ($parameter->isPassedByReference()) {
				$content .= '&';
			}
			
			$content .= '$' . static::convertToOutputCharset($parameter->getName(), $inputCharset);
			
			if ($parameter->isDefaultValueAvailable()) {
				$content .= ' = ' . static::getContentForDefaultValue($parameter->getDefaultValue(), $inputCharset);
			}
			
			if ($i < $parametersCount) {
				$content .= ', ';
			}
		}
		
		return $content;
	}
	
	static protected function getContentForDefaultValue($defaultValue, $inputCharset) {
		$content = '';
		$type = gettype($defaultValue);
		
		if ($defaultValue === null) {
			$content .= 'null';
		} elseif ($defaultValue === true) {
			$content .= 'true';
		} else if ($defaultValue === false) {
			$content .= 'false';
		} else if ($type === 'integer' || $type === 'double') {
			$content .= $defaultValue;
		} else if ($type === 'string') {
			$content .= '"' . static::convertToOutputCharset(strtr($defaultValue, array('\\' => '\\\\', '"' => '\"')), $inputCharset) . '"';
		} else if ($type === 'array') {
			$content .= 'array';
		} else {
			$content .= 'unknown';
		}
		
		return $content;
	}
}