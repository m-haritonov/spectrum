<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum;

/**
 * Adds message to current test.
 * @throws \spectrum\core\Exception If called not at running state
 */
function message($message) {
	return call_user_func_array(\spectrum\core\config::getFunctionReplacement('\spectrum\core\builders\message'), func_get_args());
}