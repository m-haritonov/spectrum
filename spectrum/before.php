<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum;

/**
 * Adds "before" context modifier.
 * @throws \spectrum\core\Exception If called not at building state
 * @param callable $function
 */
function before($function) {
	return call_user_func_array(\spectrum\core\config::getCoreFunctionReplacement('\spectrum\core\builders\before'), func_get_args());
}