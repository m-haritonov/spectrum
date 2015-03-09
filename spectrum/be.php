<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum;

/**
 * Creates assertion.
 * @throws \spectrum\core\Exception If called not at running state
 * @param mixed $testedValue
 * @return \spectrum\core\AssertionInterface
 */
function be($testedValue) {
	return call_user_func_array(\spectrum\core\config::getCoreFunctionReplacement('\spectrum\core\builders\be'), func_get_args());
}