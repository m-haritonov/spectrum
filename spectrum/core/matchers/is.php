<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\matchers;

/**
 * Matcher for "is_a" function (is_a($actual, $expected, true)).
 * @param object|string $actual
 * @param object|string $expected
 * @return bool
 */
function is(\spectrum\core\models\details\MatcherCallInterface $marcherCallDetails, $actual, $expected) {
	if (is_object($actual)) {
		$actual = get_class($actual);
	}
	
	if (is_object($expected)) {
		$expected = get_class($expected);
	}
		
	return is_a($actual, $expected, true);
}