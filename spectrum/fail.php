<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum;

/**
 * Adds to results of current test false result wits message as details.
 * @throws \spectrum\core\Exception If called not at running state
 * @param null|string $message
 */
function fail($message = null) {
	return call_user_func_array(\spectrum\core\config::getCoreFunctionReplacement('\spectrum\core\builders\fail'), func_get_args());
}