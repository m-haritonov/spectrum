<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\matchers;

/**
 * Matcher for less than operator ($actual < $expected).
 * @return bool
 */
function lt($actual, $expected)
{
	return ($actual < $expected);
}