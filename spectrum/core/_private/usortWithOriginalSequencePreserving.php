<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\_private;

/**
 * @param array $array
 * @param callable $cmpFunction
 * @param bool $reverseEqualElementSequence
 */
function usortWithOriginalSequencePreserving(&$array, $cmpFunction, $reverseEqualElementSequence = false) {
	$indexes = array();
	$num = 0;
	foreach ($array as $key => $value) {
		$indexes[$key] = $num;
		$num++;
	}
	
	uksort($array, function($keyA, $keyB) use($array, &$indexes, &$cmpFunction, &$reverseEqualElementSequence) {
		$result = $cmpFunction($array[$keyA], $array[$keyB]);
		
		// Keep equal elements in original sequence
		if ($result == 0) {
			// Equal indexes are not existed
			if ($reverseEqualElementSequence) {
				return ($indexes[$keyA] < $indexes[$keyB] ? 1 : -1);
			} else {
				return ($indexes[$keyA] < $indexes[$keyB] ? -1 : 1);
			}
		}
		
		return $result;
	});
}