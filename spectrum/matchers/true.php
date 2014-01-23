<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\matchers;

/**
 * Matcher for identical true comparison ($actual === true).
 * @return bool
 */
function true($actual)
{
	return ($actual === true);
}