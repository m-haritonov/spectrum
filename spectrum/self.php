<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum;

/**
 * Returns current group or test spec.
 * @return \spectrum\core\models\SpecInterface
 */
function self() {
	return call_user_func_array(\spectrum\core\config::getCoreFunctionReplacement('\spectrum\core\constructs\self'), func_get_args());
}