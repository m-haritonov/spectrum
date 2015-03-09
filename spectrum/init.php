<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

require_once __DIR__ . '/_private/autoload.php';
require_once __DIR__ . '/_private/exceptionHandler.php';

require_once __DIR__ . '/core/builders/after.php';
require_once __DIR__ . '/core/builders/be.php';
require_once __DIR__ . '/core/builders/before.php';
require_once __DIR__ . '/core/builders/data.php';
require_once __DIR__ . '/core/builders/fail.php';
require_once __DIR__ . '/core/builders/group.php';
require_once __DIR__ . '/core/builders/matcher.php';
require_once __DIR__ . '/core/builders/message.php';
require_once __DIR__ . '/core/builders/run.php';
require_once __DIR__ . '/core/builders/self.php';
require_once __DIR__ . '/core/builders/test.php';

require_once __DIR__ . '/after.php';
require_once __DIR__ . '/be.php';
require_once __DIR__ . '/before.php';
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/fail.php';
require_once __DIR__ . '/group.php';
require_once __DIR__ . '/matcher.php';
require_once __DIR__ . '/message.php';
require_once __DIR__ . '/run.php';
require_once __DIR__ . '/self.php';
require_once __DIR__ . '/test.php';

require_once __DIR__ . '/_private/addTestSpec.php';
require_once __DIR__ . '/_private/callFunctionOnCurrentBuildingSpec.php';
require_once __DIR__ . '/_private/callMethodThroughRunningAncestorSpecs.php';
require_once __DIR__ . '/_private/convertArguments.php';
require_once __DIR__ . '/_private/convertArgumentsForSpec.php';
require_once __DIR__ . '/_private/convertArrayWithContextsToSpecs.php';
require_once __DIR__ . '/_private/convertCharset.php';
require_once __DIR__ . '/_private/convertLatinCharsToLowerCase.php';
require_once __DIR__ . '/_private/dispatchEvent.php';
require_once __DIR__ . '/_private/formatTextForOutput.php';
require_once __DIR__ . '/_private/getArrayWithContextsElementTitle.php';
require_once __DIR__ . '/_private/getCurrentBuildingSpec.php';
require_once __DIR__ . '/_private/getCurrentData.php';
require_once __DIR__ . '/_private/getCurrentRunningEndingSpec.php';
require_once __DIR__ . '/_private/getLastErrorHandler.php';
require_once __DIR__ . '/_private/getReportClass.php';
require_once __DIR__ . '/_private/getRootSpec.php';
require_once __DIR__ . '/_private/getTestSpecs.php';
require_once __DIR__ . '/_private/handleSpecModifyDeny.php';
require_once __DIR__ . '/_private/isRunningState.php';
require_once __DIR__ . '/_private/loadBaseMatchers.php';
require_once __DIR__ . '/_private/normalizeSettings.php';
require_once __DIR__ . '/_private/removeSubsequentErrorHandlers.php';
require_once __DIR__ . '/_private/setCurrentBuildingSpec.php';
require_once __DIR__ . '/_private/setSettingsToSpec.php';
require_once __DIR__ . '/_private/translate.php';
require_once __DIR__ . '/_private/usortWithOriginalSequencePreserving.php';

if (!function_exists('after')) {
	/**
	 * Adds "after" context modifier.
	 * @throws \spectrum\core\Exception If called not at building state
	 * @param callable $function
	 */
	function after($function) {
		return call_user_func_array(\spectrum\core\config::getFunctionReplacement('\spectrum\core\builders\after'), func_get_args());
	}
}

if (!function_exists('be')) {
	/**
	 * Creates assertion.
	 * @throws \spectrum\core\Exception If called not at running state
	 * @param mixed $testedValue
	 * @return \spectrum\core\AssertionInterface
	 */
	function be($testedValue) {
		return call_user_func_array(\spectrum\core\config::getFunctionReplacement('\spectrum\core\builders\be'), func_get_args());
	}
}

if (!function_exists('before')) {
	/**
	 * Adds "before" context modifier.
	 * @throws \spectrum\core\Exception If called not at building state
	 * @param callable $function
	 */
	function before($function) {
		return call_user_func_array(\spectrum\core\config::getFunctionReplacement('\spectrum\core\builders\before'), func_get_args());
	}
}

if (!class_exists('config')) {
	class config extends \spectrum\core\config {}
}

if (!function_exists('data')) {
	/**
	 * Returns data instance of current test.
	 * @throws \spectrum\core\Exception If called not at running state
	 * @return \spectrum\core\DataInterface
	 */
	function data() {
		return call_user_func_array(\spectrum\core\config::getFunctionReplacement('\spectrum\core\builders\data'), func_get_args());
	}
}

if (!function_exists('fail')) {
	/**
	 * Adds to results of current test false result wits message as details.
	 * @throws \spectrum\core\Exception If called not at running state
	 * @param null|string $message
	 */
	function fail($message = null) {
		return call_user_func_array(\spectrum\core\config::getFunctionReplacement('\spectrum\core\builders\fail'), func_get_args());
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
		return call_user_func_array(\spectrum\core\config::getFunctionReplacement('\spectrum\core\builders\group'), func_get_args());
	}
}

if (!function_exists('matcher')) {
	/**
	 * Adds matcher to current group.
	 * @throws \spectrum\core\Exception If called not at building state
	 * @param string $name
	 * @param callable $function
	 */
	function matcher($name, $function) {
		return call_user_func_array(\spectrum\core\config::getFunctionReplacement('\spectrum\core\builders\matcher'), func_get_args());
	}
}

if (!function_exists('message')) {
	/**
	 * Adds message to current test.
	 * @throws \spectrum\core\Exception If called not at running state
	 */
	function message($message) {
		return call_user_func_array(\spectrum\core\config::getFunctionReplacement('\spectrum\core\builders\message'), func_get_args());
	}
}

if (!function_exists('run')) {
	/**
	 * Runs tests.
	 */
	function run() {
		return call_user_func_array(\spectrum\core\config::getFunctionReplacement('\spectrum\core\builders\run'), func_get_args());
	}
}

if (!function_exists('self')) {
	/**
	 * Returns current group or test spec.
	 * @return \spectrum\core\SpecInterface
	 */
	function self() {
		return call_user_func_array(\spectrum\core\config::getFunctionReplacement('\spectrum\core\builders\self'), func_get_args());
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
		return call_user_func_array(\spectrum\core\config::getFunctionReplacement('\spectrum\core\builders\test'), func_get_args());
	}
}