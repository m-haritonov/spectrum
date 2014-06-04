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

if (!function_exists('addMatcher')) { function addMatcher() { return call_user_func_array(\spectrum\config::getFunctionReplacement('\spectrum\builders\addMatcher'), func_get_args()); }}
if (!function_exists('after'))      { function after()      { return call_user_func_array(\spectrum\config::getFunctionReplacement('\spectrum\builders\after'),      func_get_args()); }}
if (!function_exists('be'))         { function be()         { return call_user_func_array(\spectrum\config::getFunctionReplacement('\spectrum\builders\be'),         func_get_args()); }}
if (!function_exists('before'))     { function before()     { return call_user_func_array(\spectrum\config::getFunctionReplacement('\spectrum\builders\before'),     func_get_args()); }}
if (!function_exists('fail'))       { function fail()       { return call_user_func_array(\spectrum\config::getFunctionReplacement('\spectrum\builders\fail'),       func_get_args()); }}
if (!function_exists('group'))      { function group()      { return call_user_func_array(\spectrum\config::getFunctionReplacement('\spectrum\builders\group'),      func_get_args()); }}
if (!function_exists('message'))    { function message()    { return call_user_func_array(\spectrum\config::getFunctionReplacement('\spectrum\builders\message'),    func_get_args()); }}
if (!function_exists('test'))       { function test()       { return call_user_func_array(\spectrum\config::getFunctionReplacement('\spectrum\builders\test'),       func_get_args()); }}
if (!function_exists('data'))       { function data()       { return call_user_func_array(\spectrum\config::getFunctionReplacement('\spectrum\builders\data'),       func_get_args()); }}