<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

function describe()      { return call_user_func_array('\spectrum\constructionCommands\Manager::' . __FUNCTION__, func_get_args()); }
function context()       { return call_user_func_array('\spectrum\constructionCommands\Manager::' . __FUNCTION__, func_get_args()); }
function it()            { return call_user_func_array('\spectrum\constructionCommands\Manager::' . __FUNCTION__, func_get_args()); }
function itLikePattern() { return call_user_func_array('\spectrum\constructionCommands\Manager::' . __FUNCTION__, func_get_args()); }
function addPattern()    { return call_user_func_array('\spectrum\constructionCommands\Manager::' . __FUNCTION__, func_get_args()); }
function addMatcher()    { return call_user_func_array('\spectrum\constructionCommands\Manager::' . __FUNCTION__, func_get_args()); }
function beforeEach()    { return call_user_func_array('\spectrum\constructionCommands\Manager::' . __FUNCTION__, func_get_args()); }
function afterEach()     { return call_user_func_array('\spectrum\constructionCommands\Manager::' . __FUNCTION__, func_get_args()); }
function be()            { return call_user_func_array('\spectrum\constructionCommands\Manager::' . __FUNCTION__, func_get_args()); }
function fail()          { return call_user_func_array('\spectrum\constructionCommands\Manager::' . __FUNCTION__, func_get_args()); }
function message()       { return call_user_func_array('\spectrum\constructionCommands\Manager::' . __FUNCTION__, func_get_args()); }