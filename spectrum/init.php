<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/root.php';

function addMatcher(){ return call_user_func_array(array(\spectrum\config::getConstructionCommandsCallBrokerClass(), __FUNCTION__), func_get_args()); }
function after()     { return call_user_func_array(array(\spectrum\config::getConstructionCommandsCallBrokerClass(), __FUNCTION__), func_get_args()); }
function be()        { return call_user_func_array(array(\spectrum\config::getConstructionCommandsCallBrokerClass(), __FUNCTION__), func_get_args()); }
function before()    { return call_user_func_array(array(\spectrum\config::getConstructionCommandsCallBrokerClass(), __FUNCTION__), func_get_args()); }
function fail()      { return call_user_func_array(array(\spectrum\config::getConstructionCommandsCallBrokerClass(), __FUNCTION__), func_get_args()); }
function group()     { return call_user_func_array(array(\spectrum\config::getConstructionCommandsCallBrokerClass(), __FUNCTION__), func_get_args()); }
function message()   { return call_user_func_array(array(\spectrum\config::getConstructionCommandsCallBrokerClass(), __FUNCTION__), func_get_args()); }
function test()      { return call_user_func_array(array(\spectrum\config::getConstructionCommandsCallBrokerClass(), __FUNCTION__), func_get_args()); }
function this()      { return call_user_func_array(array(\spectrum\config::getConstructionCommandsCallBrokerClass(), __FUNCTION__), func_get_args()); }