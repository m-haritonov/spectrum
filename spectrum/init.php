<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
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
require_once __DIR__ . '/builders/this.php';

require_once __DIR__ . '/_internal/addExclusionSpec.php';
require_once __DIR__ . '/_internal/callFunctionOnBuildingSpec.php';
require_once __DIR__ . '/_internal/convertArguments.php';
require_once __DIR__ . '/_internal/convertArrayWithContextsToSpecs.php';
require_once __DIR__ . '/_internal/convertCharset.php';
require_once __DIR__ . '/_internal/convertLatinCharsToLowerCase.php';
require_once __DIR__ . '/_internal/filterOutExclusionSpecs.php';
require_once __DIR__ . '/_internal/formatTextForOutput.php';
require_once __DIR__ . '/_internal/getBuildingSpec.php';
require_once __DIR__ . '/_internal/getExclusionSpecs.php';
require_once __DIR__ . '/_internal/getRootSpec.php';
require_once __DIR__ . '/_internal/getRunningEndingSpec.php';
require_once __DIR__ . '/_internal/isRunningState.php';
require_once __DIR__ . '/_internal/loadBaseMatchers.php';
require_once __DIR__ . '/_internal/normalizeSettings.php';
require_once __DIR__ . '/_internal/setBuildingSpec.php';
require_once __DIR__ . '/_internal/translate.php';

if (!function_exists('addMatcher')) { function addMatcher() { return call_user_func_array(\spectrum\config::getFunctionReplacement('\spectrum\builders\addMatcher'), func_get_args()); }}
if (!function_exists('after'))      { function after()      { return call_user_func_array(\spectrum\config::getFunctionReplacement('\spectrum\builders\after'),      func_get_args()); }}
if (!function_exists('be'))         { function be()         { return call_user_func_array(\spectrum\config::getFunctionReplacement('\spectrum\builders\be'),         func_get_args()); }}
if (!function_exists('before'))     { function before()     { return call_user_func_array(\spectrum\config::getFunctionReplacement('\spectrum\builders\before'),     func_get_args()); }}
if (!function_exists('fail'))       { function fail()       { return call_user_func_array(\spectrum\config::getFunctionReplacement('\spectrum\builders\fail'),       func_get_args()); }}
if (!function_exists('group'))      { function group()      { return call_user_func_array(\spectrum\config::getFunctionReplacement('\spectrum\builders\group'),      func_get_args()); }}
if (!function_exists('message'))    { function message()    { return call_user_func_array(\spectrum\config::getFunctionReplacement('\spectrum\builders\message'),    func_get_args()); }}
if (!function_exists('test'))       { function test()       { return call_user_func_array(\spectrum\config::getFunctionReplacement('\spectrum\builders\test'),       func_get_args()); }}
if (!function_exists('this'))       { function this()       { return call_user_func_array(\spectrum\config::getFunctionReplacement('\spectrum\builders\this'),       func_get_args()); }}