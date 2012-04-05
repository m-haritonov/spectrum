<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\matchers\base;

/**
 * Matcher for less than operator ($actual < $expected).
 * @return bool
 */
function lt($actual, $expected)
{
	return ($actual < $expected);
}