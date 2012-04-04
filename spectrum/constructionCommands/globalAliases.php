<?php
/*
 * Spectrum
 *
 * Copyright (c) 2011 Mikhail Kharitonov <mvkharitonov@gmail.com>
 * All rights reserved.
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
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