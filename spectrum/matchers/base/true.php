<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\matchers\base;

/**
 * Matcher for identical true comparison ($actual === true).
 * @return bool
 */
function true($actual)
{
	return ($actual === true);
}