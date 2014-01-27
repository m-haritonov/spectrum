<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/run.php';
require_once __DIR__ . '/builders/addMatcher.php';
require_once __DIR__ . '/builders/after.php';
require_once __DIR__ . '/builders/be.php';
require_once __DIR__ . '/builders/before.php';
require_once __DIR__ . '/builders/fail.php';
require_once __DIR__ . '/builders/getRootSpec.php';
require_once __DIR__ . '/builders/group.php';
require_once __DIR__ . '/builders/isRunningState.php';
require_once __DIR__ . '/builders/message.php';
require_once __DIR__ . '/builders/test.php';
require_once __DIR__ . '/builders/this.php';
require_once __DIR__ . '/builders/internal/addExclusionSpec.php';
require_once __DIR__ . '/builders/internal/callFunctionOnBuildingSpec.php';
require_once __DIR__ . '/builders/internal/convertArguments.php';
require_once __DIR__ . '/builders/internal/convertArrayWithContextsToSpecs.php';
require_once __DIR__ . '/builders/internal/filterOutExclusionSpecs.php';
require_once __DIR__ . '/builders/internal/getBuildingSpec.php';
require_once __DIR__ . '/builders/internal/getExclusionSpecs.php';
require_once __DIR__ . '/builders/internal/getRunningEndingSpec.php';
require_once __DIR__ . '/builders/internal/loadBaseMatchers.php';
require_once __DIR__ . '/builders/internal/setBuildingSpec.php';
require_once __DIR__ . '/builders/internal/setSettingsToSpec.php';

function addMatcher(){ return call_user_func_array('\spectrum\builders\\' . __FUNCTION__, func_get_args()); }
function after()     { return call_user_func_array('\spectrum\builders\\' . __FUNCTION__, func_get_args()); }
function be()        { return call_user_func_array('\spectrum\builders\\' . __FUNCTION__, func_get_args()); }
function before()    { return call_user_func_array('\spectrum\builders\\' . __FUNCTION__, func_get_args()); }
function fail()      { return call_user_func_array('\spectrum\builders\\' . __FUNCTION__, func_get_args()); }
function group()     { return call_user_func_array('\spectrum\builders\\' . __FUNCTION__, func_get_args()); }
function message()   { return call_user_func_array('\spectrum\builders\\' . __FUNCTION__, func_get_args()); }
function test()      { return call_user_func_array('\spectrum\builders\\' . __FUNCTION__, func_get_args()); }
function this()      { return call_user_func_array('\spectrum\builders\\' . __FUNCTION__, func_get_args()); }