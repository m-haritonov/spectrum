<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\matchers\base;

/**
 * Matcher for greater than operator ($actual > $expected).
 * @return bool
 */
function gt($actual, $expected)
{
	return ($actual > $expected);
}