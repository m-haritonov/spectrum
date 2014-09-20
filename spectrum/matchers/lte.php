<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\matchers;

/**
 * Matcher for less than or equal operator ($actual <= $expected).
 * @return bool
 */
function lte($actual, $expected) {
	return ($actual <= $expected);
}