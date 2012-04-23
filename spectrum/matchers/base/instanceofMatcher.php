<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\matchers\base;

/**
 * Matcher for identical operator ($actual === $expected).
 * @return bool
 */
function instanceofMatcher($actual, $expected)
{
	return ($actual instanceof $expected);
}