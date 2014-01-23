<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\matchers;

/**
 * Matcher for greater than or equal operator ($actual >= $expected).
 * @return bool
 */
function gte($actual, $expected)
{
	return ($actual >= $expected);
}