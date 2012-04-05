<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\matchers\base;

/**
 * Matcher for less than or equal operator ($actual <= $expected).
 * @return bool
 */
function lte($actual, $expected)
{
	return ($actual <= $expected);
}