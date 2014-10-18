<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internals;

use spectrum\config;

/**
 * @access private
 * @return array
 */
function getArrayWithContextsElementTitle($defaultTitle, array $values) {
	if ($defaultTitle == '' || is_int($defaultTitle)) {
		$title = '';
		
		$i = 0;
		$valuesCount = count($values);
		foreach ($values as $value) {
			$i++;
			
			$valueType = gettype($value);
			
			if ($valueType === 'string') {
				$title .= '"' . $value . '"';
			} else if ($valueType === 'integer' || $valueType === 'double') {
				$title .= $value;
			} else if ($value === true) {
				$title .= 'true';
			} else if ($value === false) {
				$title .= 'false';
			} else if ($value === null) {
				$title .= 'null';
			} else if ($valueType === 'array') {
				$title .= 'array';
			} else if ($valueType === 'object') {
				$function = function(){};
				if ($value instanceof $function) {
					$title .= 'function';
				} else {
					$title .= 'object';
				}
			} else if ($valueType === 'resource') {
				$title .= print_r($value, true);
			} else {
				$title .= 'unknown';
			}
			
			if ($i < $valuesCount)
				$title .= ', ';
		}
		
		$inputCharset = config::getInputCharset();
		if (mb_strlen($title, $inputCharset) > 100) {
			$title = mb_substr($title, 0, 100, $inputCharset) . '...';
		}
		
		return $title;
	} else {
		return $defaultTitle;
	}
}