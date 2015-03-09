<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\_private\reports\text\components\code\variables;

class functionVar extends \spectrum\core\_private\reports\text\components\component {
	/**
	 * @param \Closure|\spectrum\core\types\FunctionTypeInterface $variable
	 * @param null|string $inputCharset
	 * @return string
	 */
	static public function getContent($variable, $inputCharset = null) {
		if ($variable instanceof \spectrum\core\types\FunctionTypeInterface) {
			return 'function(' . static::getContentForParameters($variable->getFunction(), $inputCharset) . '){ ' . static::getContentForBody($variable->getBodyCode(), $inputCharset) . ' }';
		} else {
			return 'function(' . static::getContentForParameters($variable, $inputCharset) . '){}';
		}
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
	
	static protected function getContentForBody(array $bodyCode, $inputCharset) {
		foreach ($bodyCode as $expression) {
			if ($expression['operator'] === 'throw') {
				$e = $expression['operands'][0];
				return 'throw new \\' . static::convertToOutputCharset(get_class($e), $inputCharset) . '(' . static::getContentForExceptionArguments($e, $inputCharset) . ');';
			}
		}
	}
	
	static protected function getContentForExceptionArguments(\Exception $e, $inputCharset) {
		return '"' . static::convertToOutputCharset(strtr($e->getMessage(), array('\\' => '\\\\', '"' => '\"')), $inputCharset) . '", ' . $e->getCode();
	}
}