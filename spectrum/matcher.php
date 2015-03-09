<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum;

/**
 * Adds matcher to current group.
 * @throws \spectrum\core\Exception If called not at building state
 * @param string $name
 * @param callable $function
 */
function matcher($name, $function) {
	return call_user_func_array(\spectrum\core\config::getFunctionReplacement('\spectrum\core\builders\matcher'), func_get_args());
}