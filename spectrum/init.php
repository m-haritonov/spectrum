<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

require_once __DIR__ . '/autoload.php';
\spectrum\core\plugins\Manager::registerPlugin('reports', '\spectrum\reports\Plugin');


/* Functions declaring for IDE autocompletion support */

/**
 * @return \spectrum\core\SpecContainerDescribe
 */
function describe()      { return call_user_func_array('\spectrum\constructionCommands\Manager::' . __FUNCTION__, func_get_args()); }

/**
 * @return \spectrum\core\SpecContainerContext
 */
function context()       { return call_user_func_array('\spectrum\constructionCommands\Manager::' . __FUNCTION__, func_get_args()); }

/**
 * @return \spectrum\core\SpecItemIt
 */
function it()            { return call_user_func_array('\spectrum\constructionCommands\Manager::' . __FUNCTION__, func_get_args()); }

/**
 * @return \spectrum\core\SpecContainerPattern
 */
function itLikePattern() { return call_user_func_array('\spectrum\constructionCommands\Manager::' . __FUNCTION__, func_get_args()); }
function addPattern()    { return call_user_func_array('\spectrum\constructionCommands\Manager::' . __FUNCTION__, func_get_args()); }

function addMatcher()    { return call_user_func_array('\spectrum\constructionCommands\Manager::' . __FUNCTION__, func_get_args()); }

function beforeEach()    { return call_user_func_array('\spectrum\constructionCommands\Manager::' . __FUNCTION__, func_get_args()); }
function afterEach()     { return call_user_func_array('\spectrum\constructionCommands\Manager::' . __FUNCTION__, func_get_args()); }

/**
 * @return \spectrum\core\asserts\Assert
 */
function the()           { return call_user_func_array('\spectrum\constructionCommands\Manager::' . __FUNCTION__, func_get_args()); }

/**
 * @return \spectrum\core\WorldInterface
 */
function world()           { return call_user_func_array('\spectrum\constructionCommands\Manager::' . __FUNCTION__, func_get_args()); }
function fail()          { return call_user_func_array('\spectrum\constructionCommands\Manager::' . __FUNCTION__, func_get_args()); }
function message()       { return call_user_func_array('\spectrum\constructionCommands\Manager::' . __FUNCTION__, func_get_args()); }