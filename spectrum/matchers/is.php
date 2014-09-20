<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\matchers;

/**
 * @return bool
 */
function is($actual, $expected) {
	if (is_object($actual)) {
		$actual = get_class($actual);
	}
	
	if (is_object($expected)) {
		$expected = get_class($expected);
	}
		
	return is_a($actual, $expected, true);
}