<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\matchers;

/**
 * Matcher for equal operator ($actual == $expected).
 * @return bool
 */
function eq($actual, $expected)
{
	return ($actual == $expected);
}