<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum;

/**
 * Creates group.
 * @param null|string|int|float $name
 * @param null|\Closure|array $contexts
 * @param null|\Closure $body
 * @param null|int|bool|array $settings
 * @return \spectrum\core\SpecInterface
 */
function group($name = null, $contexts = null, $body = null, $settings = null) {
	return call_user_func_array(\spectrum\core\config::getCoreFunctionReplacement('\spectrum\core\builders\group'), func_get_args());
}