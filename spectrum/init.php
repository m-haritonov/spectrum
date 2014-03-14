<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/exceptionHandler.php';

if (!function_exists('\spectrum\run'))                                               { require_once __DIR__ . '/run.php'; }
if (!function_exists('\spectrum\builders\addMatcher'))                               { require_once __DIR__ . '/builders/addMatcher.php'; }
if (!function_exists('\spectrum\builders\after'))                                    { require_once __DIR__ . '/builders/after.php'; }
if (!function_exists('\spectrum\builders\be'))                                       { require_once __DIR__ . '/builders/be.php'; }
if (!function_exists('\spectrum\builders\before'))                                   { require_once __DIR__ . '/builders/before.php'; }
if (!function_exists('\spectrum\builders\fail'))                                     { require_once __DIR__ . '/builders/fail.php'; }
if (!function_exists('\spectrum\builders\group'))                                    { require_once __DIR__ . '/builders/group.php'; }
if (!function_exists('\spectrum\builders\message'))                                  { require_once __DIR__ . '/builders/message.php'; }
if (!function_exists('\spectrum\builders\test'))                                     { require_once __DIR__ . '/builders/test.php'; }
if (!function_exists('\spectrum\builders\this'))                                     { require_once __DIR__ . '/builders/this.php'; }
if (!function_exists('\spectrum\_internal\addExclusionSpec'))                { require_once __DIR__ . '/_internal/addExclusionSpec.php'; }
if (!function_exists('\spectrum\_internal\callFunctionOnBuildingSpec'))      { require_once __DIR__ . '/_internal/callFunctionOnBuildingSpec.php'; }
if (!function_exists('\spectrum\_internal\convertArguments'))                { require_once __DIR__ . '/_internal/convertArguments.php'; }
if (!function_exists('\spectrum\_internal\convertArrayWithContextsToSpecs')) { require_once __DIR__ . '/_internal/convertArrayWithContextsToSpecs.php'; }
if (!function_exists('\spectrum\_internal\filterOutExclusionSpecs'))         { require_once __DIR__ . '/_internal/filterOutExclusionSpecs.php'; }
if (!function_exists('\spectrum\_internal\getBuildingSpec'))                 { require_once __DIR__ . '/_internal/getBuildingSpec.php'; }
if (!function_exists('\spectrum\_internal\getExclusionSpecs'))               { require_once __DIR__ . '/_internal/getExclusionSpecs.php'; }
if (!function_exists('\spectrum\_internal\getRootSpec'))                     { require_once __DIR__ . '/_internal/getRootSpec.php'; }
if (!function_exists('\spectrum\_internal\getRunningEndingSpec'))            { require_once __DIR__ . '/_internal/getRunningEndingSpec.php'; }
if (!function_exists('\spectrum\_internal\isRunningState'))                  { require_once __DIR__ . '/_internal/isRunningState.php'; }
if (!function_exists('\spectrum\_internal\loadBaseMatchers'))                { require_once __DIR__ . '/_internal/loadBaseMatchers.php'; }
if (!function_exists('\spectrum\_internal\setBuildingSpec'))                 { require_once __DIR__ . '/_internal/setBuildingSpec.php'; }
if (!function_exists('\spectrum\_internal\normalizeSettings'))               { require_once __DIR__ . '/_internal/normalizeSettings.php'; }

require_once __DIR__ . '/_internal/convertCharset.php';
require_once __DIR__ . '/_internal/convertLatinCharsToLowerCase.php';
require_once __DIR__ . '/_internal/formatTextForOutput.php';
require_once __DIR__ . '/_internal/translate.php';

if (!function_exists('addMatcher')) { function addMatcher() { return call_user_func_array('\spectrum\builders\addMatcher', func_get_args()); }}
if (!function_exists('after'))      { function after()      { return call_user_func_array('\spectrum\builders\after',      func_get_args()); }}
if (!function_exists('be'))         { function be()         { return call_user_func_array('\spectrum\builders\be',         func_get_args()); }}
if (!function_exists('before'))     { function before()     { return call_user_func_array('\spectrum\builders\before',     func_get_args()); }}
if (!function_exists('fail'))       { function fail()       { return call_user_func_array('\spectrum\builders\fail',       func_get_args()); }}
if (!function_exists('group'))      { function group()      { return call_user_func_array('\spectrum\builders\group',      func_get_args()); }}
if (!function_exists('message'))    { function message()    { return call_user_func_array('\spectrum\builders\message',    func_get_args()); }}
if (!function_exists('test'))       { function test()       { return call_user_func_array('\spectrum\builders\test',       func_get_args()); }}
if (!function_exists('this'))       { function this()       { return call_user_func_array('\spectrum\builders\this',       func_get_args()); }}