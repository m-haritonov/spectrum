<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/root.php';

require_once __DIR__ . '/constructionCommands/commands/addMatcher.php';
require_once __DIR__ . '/constructionCommands/commands/afterEach.php';
require_once __DIR__ . '/constructionCommands/commands/be.php';
require_once __DIR__ . '/constructionCommands/commands/beforeEach.php';
require_once __DIR__ . '/constructionCommands/commands/fail.php';
require_once __DIR__ . '/constructionCommands/commands/group.php';
require_once __DIR__ . '/constructionCommands/commands/message.php';
require_once __DIR__ . '/constructionCommands/commands/test.php';
require_once __DIR__ . '/constructionCommands/commands/this.php';
require_once __DIR__ . '/constructionCommands/commands/internal/addMultiplierExclusionSpec.php';
require_once __DIR__ . '/constructionCommands/commands/internal/getArgumentsForGroupCommand.php';
require_once __DIR__ . '/constructionCommands/commands/internal/getArgumentsForTestCommand.php';
require_once __DIR__ . '/constructionCommands/commands/internal/getCurrentDeclaringSpec.php';
require_once __DIR__ . '/constructionCommands/commands/internal/getCurrentRunningSpec.php';
require_once __DIR__ . '/constructionCommands/commands/internal/getCurrentSpec.php';
require_once __DIR__ . '/constructionCommands/commands/internal/getRootSpec.php';
require_once __DIR__ . '/constructionCommands/commands/internal/getMultiplierEndingSpecs.php';
require_once __DIR__ . '/constructionCommands/commands/internal/getMultiplierExclusionSpecs.php';
require_once __DIR__ . '/constructionCommands/commands/internal/getNameForArguments.php';
require_once __DIR__ . '/constructionCommands/commands/internal/isRunningState.php';
require_once __DIR__ . '/constructionCommands/commands/internal/loadBaseMatchers.php';
require_once __DIR__ . '/constructionCommands/commands/internal/setCurrentDeclaringSpec.php';
require_once __DIR__ . '/constructionCommands/commands/internal/setSpecSettings.php';

function addMatcher()      { $callBrokerClass = \spectrum\config::getConstructionCommandsCallBrokerClass(); return call_user_func_array(array($callBrokerClass, __FUNCTION__), func_get_args()); }
function afterEach()       { $callBrokerClass = \spectrum\config::getConstructionCommandsCallBrokerClass(); return call_user_func_array(array($callBrokerClass, __FUNCTION__), func_get_args()); }
function be()            { $callBrokerClass = \spectrum\config::getConstructionCommandsCallBrokerClass(); return call_user_func_array(array($callBrokerClass, __FUNCTION__), func_get_args()); }
function beforeEach()    { $callBrokerClass = \spectrum\config::getConstructionCommandsCallBrokerClass(); return call_user_func_array(array($callBrokerClass, __FUNCTION__), func_get_args()); }
function fail()    { $callBrokerClass = \spectrum\config::getConstructionCommandsCallBrokerClass(); return call_user_func_array(array($callBrokerClass, __FUNCTION__), func_get_args()); }
function group()     { $callBrokerClass = \spectrum\config::getConstructionCommandsCallBrokerClass(); return call_user_func_array(array($callBrokerClass, __FUNCTION__), func_get_args()); }
function message()           { $callBrokerClass = \spectrum\config::getConstructionCommandsCallBrokerClass(); return call_user_func_array(array($callBrokerClass, __FUNCTION__), func_get_args()); }
function test()        { $callBrokerClass = \spectrum\config::getConstructionCommandsCallBrokerClass(); return call_user_func_array(array($callBrokerClass, __FUNCTION__), func_get_args()); }
function this()       { $callBrokerClass = \spectrum\config::getConstructionCommandsCallBrokerClass(); return call_user_func_array(array($callBrokerClass, __FUNCTION__), func_get_args()); }