<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\html\components\code\variables;

class functionVar extends \spectrum\core\plugins\reports\drivers\html\components\component {
	/**
	 * @return string
	 */
	static public function getStyles() {
		return static::formatTextForOutput('<style type="text/css">/*<![CDATA[*/
			.app-code-variables-function { font-size: 12px; }
			.app-code-variables-function .type { font-size: 0.8em; color: rgba(0, 0, 0, 0.6); }
			.app-code-variables-function .value { display: inline-block; overflow: hidden; text-overflow: ellipsis; -o-text-overflow: ellipsis; max-width: 5em; border-radius: 4px; background: rgba(255, 255, 255, 0.5); white-space: nowrap; vertical-align: text-top; }
			.app-resultBuffer>.results>.result.expanded .app-code-variables-function .value { overflow: visible; max-width: none; white-space: pre; }
		/*]]>*/</style>', 2);
	}

	/**
	 * @param \Closure|\spectrum\core\types\FunctionTypeInterface $variable
	 * @param null|string $inputCharset
	 * @return string
	 */
	static public function getContent($variable, $inputCharset = null) {
		$content = '';
		$content .= '<span class="app-code-variables-function">';
		if ($variable instanceof \spectrum\core\types\FunctionTypeInterface) {
			$content .= '<span class="type">function(' . static::getContentForParameters($variable->getFunction(), $inputCharset) . '){ ' . static::getContentForBody($variable->getBodyCode(), $inputCharset) . ' }</span>';
		} else {
			$content .= '<span class="type">function(' . static::getContentForParameters($variable, $inputCharset) . '){}</span>';
		}
		
		$content .= '</span>';
		return $content;
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
				$content .= '\\' . static::escapeHtml(static::convertToOutputCharset($reflectionClass->getName(), $inputCharset)) . ' ';
			}
			
			if ($parameter->isArray()) {
				$content .= 'array ';
			} else if (method_exists($parameter, 'isCallable') && $parameter->isCallable()) {
				$content .= 'callable ';
			}
			
			if ($parameter->isPassedByReference()) {
				$content .= '&amp;';
			}
			
			$content .= '$' . static::escapeHtml(static::convertToOutputCharset($parameter->getName(), $inputCharset));
			
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
			$content .= static::escapeHtml($defaultValue);
		} else if ($type === 'string') {
			$content .= '"' . static::escapeHtml(static::convertToOutputCharset(strtr($defaultValue, array('\\' => '\\\\', '"' => '\"')), $inputCharset)) . '"';
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
				return 'throw new \\' . static::escapeHtml(static::convertToOutputCharset(get_class($e), $inputCharset)) . '(' . static::getContentForExceptionArguments($e, $inputCharset) . ');';
			}
		}
	}
	
	static protected function getContentForExceptionArguments(\Exception $e, $inputCharset) {
		return '"' . static::escapeHtml(static::convertToOutputCharset(strtr($e->getMessage(), array('\\' => '\\\\', '"' => '\"')), $inputCharset)) . '", ' . static::escapeHtml($e->getCode());
	}
}