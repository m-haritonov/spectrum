<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internals;

/**
 * @access private
 * @return null|array
 */
function convertArguments(array $arguments, array $inputArgumentPattern, array $outputArgumentPattern) {
	$arguments = array_values($arguments);
	$argumentCount = count($arguments);
	$function = function(){};
	foreach ($inputArgumentPattern as $pattern) {
		if ($argumentCount == count($pattern)) {
			$result = $outputArgumentPattern;
			foreach ($pattern as $num => $patternArgument) {
				list($types, $name) = explode(':', $patternArgument);
				$types = explode('|', $types);
				
				$isNull = (in_array('null', $types) && is_null($arguments[$num]));
				$isScalar = (in_array('scalar', $types) && is_scalar($arguments[$num]));
				$isArray = (in_array('array', $types) && is_array($arguments[$num]));
				$isClosure = (in_array('closure', $types) && is_object($arguments[$num]) && $arguments[$num] instanceof $function);
				
				if ($isNull || $isScalar || $isArray || $isClosure) {
					$result[$name] = $arguments[$num];
				} else {
					continue(2);
				}
			}
			
			return array_values($result);
		}
	}
	
	return null;
}