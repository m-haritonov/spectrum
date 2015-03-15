<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\matchers;

/**
 * Matcher for identical operator ($actual === $expected).
 * @param mixed $actual
 * @param mixed $expected
 * @return bool
 */
function ident(\spectrum\core\models\details\MatcherCallInterface $marcherCallDetails, $actual, $expected) {
	return ($actual === $expected);
}