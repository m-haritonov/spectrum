<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\matchers\base;

/**
 * Matcher for identical null comparison ($actual === null).
 * @return bool
 */
function null($actual)
{
	return ($actual === null);
}