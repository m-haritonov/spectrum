<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\matchers;

/**
 * Matcher for greater than or equal operator ($actual >= $expected).
 * @param mixed $actual
 * @param mixed $expected
 * @return bool
 */
function gte(\spectrum\core\details\MatcherCallInterface $marcherCallDetails, $actual, $expected) {
	return ($actual >= $expected);
}