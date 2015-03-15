<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum;

/**
 * Runs tests.
 * @return null|bool
 */
function run() {
	return call_user_func_array(\spectrum\core\config::getCoreFunctionReplacement('\spectrum\core\constructs\run'), func_get_args());
}