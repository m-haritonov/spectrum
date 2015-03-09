<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\_private;

use spectrum\core\SpecInterface;

/**
 * @access private
 * @param string $methodName
 * @param mixed $defaultReturnValue
 * @param mixed $ignoredReturnValue
 * @param bool $useStrictComparison
 * @return mixed
 */
function callMethodThroughRunningAncestorSpecs(SpecInterface $spec, $methodName, array $arguments = array(), $defaultReturnValue = null, $ignoredReturnValue = null, $useStrictComparison = true) {
	$methodNames = explode('->', $methodName);
	$methodNamesCount = count($methodNames);
	
	foreach (array_merge(array($spec), $spec->getRunningAncestorSpecs()) as $spec2) {
		if ($methodNamesCount == 1) {
			$return = call_user_func_array(array($spec2, $methodNames[0]), $arguments);
		} else {
			$return = $spec2;
			foreach ($methodNames as $key => $name) {
				if ($key < $methodNamesCount - 1) {
					$return = call_user_func(array($return, $name));
				} else {
					$return = call_user_func_array(array($return, $name), $arguments);
				}
			}
		}
		
		if (($useStrictComparison && $return !== $ignoredReturnValue) || (!$useStrictComparison && $return != $ignoredReturnValue)) {
			return $return;
		}
	}

	return $defaultReturnValue;
}