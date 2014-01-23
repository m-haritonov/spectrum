<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\matchers;

/**
 * Matcher for identical null comparison ($actual === null).
 * @return bool
 */
function null($actual)
{
	return ($actual === null);
}