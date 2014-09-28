<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internals;

/**
 * @access private
 * @param string $string String in "US-ASCII" charset
 * @param array $replacements Strings in output charset
 * @return string String in output charset
 */
function translate($string, array $replacements = array()) {
	return strtr($string, $replacements);
}