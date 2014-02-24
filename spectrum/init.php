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
if (!function_exists('\spectrum\builders\getRootSpec'))                              { require_once __DIR__ . '/builders/getRootSpec.php'; }
if (!function_exists('\spectrum\builders\group'))                                    { require_once __DIR__ . '/builders/group.php'; }
if (!function_exists('\spectrum\builders\isRunningState'))                           { require_once __DIR__ . '/builders/isRunningState.php'; }
if (!function_exists('\spectrum\builders\message'))                                  { require_once __DIR__ . '/builders/message.php'; }
if (!function_exists('\spectrum\builders\test'))                                     { require_once __DIR__ . '/builders/test.php'; }
if (!function_exists('\spectrum\builders\this'))                                     { require_once __DIR__ . '/builders/this.php'; }
if (!function_exists('\spectrum\builders\internal\addExclusionSpec'))                { require_once __DIR__ . '/builders/internal/addExclusionSpec.php'; }
if (!function_exists('\spectrum\builders\internal\callFunctionOnBuildingSpec'))      { require_once __DIR__ . '/builders/internal/callFunctionOnBuildingSpec.php'; }
if (!function_exists('\spectrum\builders\internal\convertArguments'))                { require_once __DIR__ . '/builders/internal/convertArguments.php'; }
if (!function_exists('\spectrum\builders\internal\convertArrayWithContextsToSpecs')) { require_once __DIR__ . '/builders/internal/convertArrayWithContextsToSpecs.php'; }
if (!function_exists('\spectrum\builders\internal\filterOutExclusionSpecs'))         { require_once __DIR__ . '/builders/internal/filterOutExclusionSpecs.php'; }
if (!function_exists('\spectrum\builders\internal\getBuildingSpec'))                 { require_once __DIR__ . '/builders/internal/getBuildingSpec.php'; }
if (!function_exists('\spectrum\builders\internal\getExclusionSpecs'))               { require_once __DIR__ . '/builders/internal/getExclusionSpecs.php'; }
if (!function_exists('\spectrum\builders\internal\getRunningEndingSpec'))            { require_once __DIR__ . '/builders/internal/getRunningEndingSpec.php'; }
if (!function_exists('\spectrum\builders\internal\loadBaseMatchers'))                { require_once __DIR__ . '/builders/internal/loadBaseMatchers.php'; }
if (!function_exists('\spectrum\builders\internal\setBuildingSpec'))                 { require_once __DIR__ . '/builders/internal/setBuildingSpec.php'; }
if (!function_exists('\spectrum\builders\internal\normalizeSettings'))               { require_once __DIR__ . '/builders/internal/normalizeSettings.php'; }

require_once __DIR__ . '/tools/convertCharset.php';
require_once __DIR__ . '/tools/convertLatinCharsToLowerCase.php';
require_once __DIR__ . '/tools/formatTextForOutput.php';
require_once __DIR__ . '/tools/translate.php';

if (!function_exists('addMatcher')) { function addMatcher() { return call_user_func_array('\spectrum\builders\addMatcher', func_get_args()); }}
if (!function_exists('after'))      { function after()      { return call_user_func_array('\spectrum\builders\after',      func_get_args()); }}
if (!function_exists('be'))         { function be()         { return call_user_func_array('\spectrum\builders\be',         func_get_args()); }}
if (!function_exists('before'))     { function before()     { return call_user_func_array('\spectrum\builders\before',     func_get_args()); }}
if (!function_exists('fail'))       { function fail()       { return call_user_func_array('\spectrum\builders\fail',       func_get_args()); }}
if (!function_exists('group'))      { function group()      { return call_user_func_array('\spectrum\builders\group',      func_get_args()); }}
if (!function_exists('message'))    { function message()    { return call_user_func_array('\spectrum\builders\message',    func_get_args()); }}
if (!function_exists('test'))       { function test()       { return call_user_func_array('\spectrum\builders\test',       func_get_args()); }}
if (!function_exists('this'))       { function this()       { return call_user_func_array('\spectrum\builders\this',       func_get_args()); }}