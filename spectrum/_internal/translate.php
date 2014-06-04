<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internal;

/**
 * @param string $string String in "us-ascii" charset
 * @param array $replacement Strings in output charset
 * @return string String in output charset
 */
function translate($string, array $replacement = array())
{
	return strtr($string, $replacement);
}