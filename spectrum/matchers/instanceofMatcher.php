<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\matchers;

/**
 * @return bool
 */
function instanceofMatcher($actual, $expected)
{
	// TODO add class name support
	return ($actual instanceof $expected);
}