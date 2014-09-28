<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/exceptionHandler.php';

require_once __DIR__ . '/run.php';
require_once __DIR__ . '/builders/addMatcher.php';
require_once __DIR__ . '/builders/after.php';
require_once __DIR__ . '/builders/be.php';
require_once __DIR__ . '/builders/before.php';
require_once __DIR__ . '/builders/fail.php';
require_once __DIR__ . '/builders/group.php';
require_once __DIR__ . '/builders/message.php';
require_once __DIR__ . '/builders/test.php';
require_once __DIR__ . '/builders/data.php';

require_once __DIR__ . '/_internals/addTestSpec.php';
require_once __DIR__ . '/_internals/callFunctionOnCurrentBuildingSpec.php';
require_once __DIR__ . '/_internals/convertArguments.php';
require_once __DIR__ . '/_internals/convertArgumentsForSpec.php';
require_once __DIR__ . '/_internals/convertArrayWithContextsToSpecs.php';
require_once __DIR__ . '/_internals/convertCharset.php';
require_once __DIR__ . '/_internals/convertLatinCharsToLowerCase.php';
require_once __DIR__ . '/_internals/formatTextForOutput.php';
require_once __DIR__ . '/_internals/getCurrentBuildingSpec.php';
require_once __DIR__ . '/_internals/getCurrentData.php';
require_once __DIR__ . '/_internals/getCurrentRunningEndingSpec.php';
require_once __DIR__ . '/_internals/getRootSpec.php';
require_once __DIR__ . '/_internals/getTestSpecs.php';
require_once __DIR__ . '/_internals/isRunningState.php';
require_once __DIR__ . '/_internals/loadBaseMatchers.php';
require_once __DIR__ . '/_internals/normalizeSettings.php';
require_once __DIR__ . '/_internals/setCurrentBuildingSpec.php';
require_once __DIR__ . '/_internals/setSettingsToSpec.php';
require_once __DIR__ . '/_internals/translate.php';

if (!function_exists('addMatcher')) {
	/**
	 * Adds matcher to current group.
	 * @throws \spectrum\Exception If called not at building state
	 * @param string $name
	 * @param callable $function
	 */
	function addMatcher($name, $function) {
		return call_user_func_array(\spectrum\config::getFunctionReplacement('\spectrum\builders\addMatcher'), func_get_args());
	}
}

if (!function_exists('after')) {
	/**
	 * Adds "after" context modifier.
	 * @throws \spectrum\Exception If called not at building state
	 * @param callable $function
	 */
	function after($function) {
		return call_user_func_array(\spectrum\config::getFunctionReplacement('\spectrum\builders\after'), func_get_args());
	}
}

if (!function_exists('be')) {
	/**
	 * Creates assertion.
	 * @throws \spectrum\Exception If called not at running state
	 * @param mixed $testedValue
	 * @return \spectrum\core\AssertionInterface
	 */
	function be($testedValue) {
		return call_user_func_array(\spectrum\config::getFunctionReplacement('\spectrum\builders\be'), func_get_args());
	}
}

if (!function_exists('before')) {
	/**
	 * Adds "before" context modifier.
	 * @throws \spectrum\Exception If called not at building state
	 * @param callable $function
	 */
	function before($function) {
		return call_user_func_array(\spectrum\config::getFunctionReplacement('\spectrum\builders\before'), func_get_args());
	}
}

if (!function_exists('data')) {
	/**
	 * Returns data instance of current test.
	 * @throws \spectrum\Exception If called not at running state
	 * @return \spectrum\core\DataInterface
	 */
	function data() {
		return call_user_func_array(\spectrum\config::getFunctionReplacement('\spectrum\builders\data'), func_get_args());
	}
}

if (!function_exists('fail')) {
	/**
	 * Adds to result buffer of current test false result wits message as details.
	 * @throws \spectrum\Exception If called not at running state
	 * @param null|string $message
	 */
	function fail($message = null) {
		return call_user_func_array(\spectrum\config::getFunctionReplacement('\spectrum\builders\fail'), func_get_args());
	}
}

if (!function_exists('group')) {
	/**
	 * Creates group.
	 * @param null|string|int|float $name
	 * @param null|\Closure|array $contexts
	 * @param null|\Closure $body
	 * @param null|int|bool|array $settings
	 * @return \spectrum\core\SpecInterface
	 */
	function group($name = null, $contexts = null, $body = null, $settings = null) {
		return call_user_func_array(\spectrum\config::getFunctionReplacement('\spectrum\builders\group'), func_get_args());
	}
}

if (!function_exists('message')) {
	/**
	 * Adds message to current test.
	 * @throws \spectrum\Exception If called not at running state
	 */
	function message($message) {
		return call_user_func_array(\spectrum\config::getFunctionReplacement('\spectrum\builders\message'), func_get_args());
	}
}

if (!function_exists('test')) {
	/**
	 * Creates test.
	 * @param null|string|int|float $name
	 * @param null|\Closure|array $contexts
	 * @param null|\Closure $body
	 * @param null|int|bool|array $settings
	 * @return \spectrum\core\SpecInterface
	 */
	function test($name = null, $contexts = null, $body = null, $settings = null) {
		return call_user_func_array(\spectrum\config::getFunctionReplacement('\spectrum\builders\test'), func_get_args());
	}
}