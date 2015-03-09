<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum;

/**
 * Returns data instance of current test.
 * @throws \spectrum\core\Exception If called not at running state
 * @return \spectrum\core\DataInterface
 */
function data() {
	return call_user_func_array(\spectrum\core\config::getCoreFunctionReplacement('\spectrum\core\builders\data'), func_get_args());
}