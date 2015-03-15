<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/
namespace spectrum;

/**
 * Creates test.
 * @param null|string|int|float $name
 * @param null|\Closure|array $contexts
 * @param null|\Closure $body
 * @param null|int|bool|array $settings
 * @return \spectrum\core\models\SpecInterface
 */
function test($name = null, $contexts = null, $body = null, $settings = null) {
	return call_user_func_array(\spectrum\core\config::getCoreFunctionReplacement('\spectrum\core\constructs\test'), func_get_args());
}