<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\matchers;

/**
 * @return bool
 */
function instanceofMatcher($actual, $expected)
{
	if (is_string($actual))
	{
		if (is_object($expected))
			$expected = get_class($expected);
		
		return is_a($actual, $expected, true);
	}
	else
		return ($actual instanceof $expected);
}