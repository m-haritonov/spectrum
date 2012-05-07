<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands;

// Include base commands by default for available through "Manager" class without additional movement
require_once __DIR__ . '/baseCommands/addPattern.php';
require_once __DIR__ . '/baseCommands/addMatcher.php';
require_once __DIR__ . '/baseCommands/beforeEach.php';
require_once __DIR__ . '/baseCommands/afterEach.php';

require_once __DIR__ . '/baseCommands/container.php';
require_once __DIR__ . '/baseCommands/describe.php';
require_once __DIR__ . '/baseCommands/context.php';
require_once __DIR__ . '/baseCommands/it.php';
require_once __DIR__ . '/baseCommands/itLikePattern.php';

require_once __DIR__ . '/baseCommands/the.php';

require_once __DIR__ . '/baseCommands/world.php';
require_once __DIR__ . '/baseCommands/fail.php';
require_once __DIR__ . '/baseCommands/message.php';

require_once __DIR__ . '/baseCommands/setDeclaringContainer.php';
require_once __DIR__ . '/baseCommands/getDeclaringContainer.php';
require_once __DIR__ . '/baseCommands/getCurrentContainer.php';
require_once __DIR__ . '/baseCommands/getCurrentItem.php';

require_once __DIR__ . '/baseCommands/setSettings.php';

require_once __DIR__ . '/baseCommands/isDeclaringState.php';
require_once __DIR__ . '/baseCommands/isRunningState.php';

/**
 * @method \spectrum\core\asserts\Assert the()
 * @method addMatcher()
 * @method afterEach()
 * @method beforeEach()
 * @method \spectrum\core\SpecContainerInterface container()
 * @method \spectrum\core\SpecContainerContext context()
 * @method \spectrum\core\SpecContainerDescribe describe()
 * @method \spectrum\core\WorldInterface world()
 * @method fail()
 * @method message()
 * @method \spectrum\core\SpecContainerInterface getCurrentContainer()
 * @method \spectrum\core\SpecItemInterface getCurrentItem()
 * @method isDeclaringState()
 * @method isRunningState()
 * @method \spectrum\core\SpecItemIt it()
 * @method setDeclaringContainer()
 * @method getDeclaringContainer()
 * @method setSettings()
 */
class Manager implements ManagerInterface
{
	static protected $registeredCommands = array(
		'addPattern' => '\spectrum\constructionCommands\baseCommands\addPattern',
		'addMatcher' => '\spectrum\constructionCommands\baseCommands\addMatcher',
		'beforeEach' => '\spectrum\constructionCommands\baseCommands\beforeEach',
		'afterEach' => '\spectrum\constructionCommands\baseCommands\afterEach',

		'container' => '\spectrum\constructionCommands\baseCommands\container',
		'describe' => '\spectrum\constructionCommands\baseCommands\describe',
		'context' => '\spectrum\constructionCommands\baseCommands\context',
		'it' => '\spectrum\constructionCommands\baseCommands\it',
		'itLikePattern' => '\spectrum\constructionCommands\baseCommands\itLikePattern',

		'the' => '\spectrum\constructionCommands\baseCommands\the',

		'world' => '\spectrum\constructionCommands\baseCommands\world',
		'fail' => '\spectrum\constructionCommands\baseCommands\fail',
		'message' => '\spectrum\constructionCommands\baseCommands\message',

		'getCurrentContainer' => '\spectrum\constructionCommands\baseCommands\getCurrentContainer',
		'setDeclaringContainer' => '\spectrum\constructionCommands\baseCommands\setDeclaringContainer',
		'getDeclaringContainer' => '\spectrum\constructionCommands\baseCommands\getDeclaringContainer',
		'getCurrentItem' => '\spectrum\constructionCommands\baseCommands\getCurrentItem',

		'setSettings' => '\spectrum\constructionCommands\baseCommands\setSettings',

		'isDeclaringState' => '\spectrum\constructionCommands\baseCommands\isDeclaringState',
		'isRunningState' => '\spectrum\constructionCommands\baseCommands\isRunningState',
	);
	
	static public function __callStatic($name, $args = array())
	{
		return static::callCommand($name, $args);
	}

	static public function callCommand($name, $args = array())
	{
		return call_user_func_array(static::getRegisteredCommandCallback($name), $args);
	}

	static public function registerCommand($name, $callback)
	{
		if (!Config::getAllowConstructionCommandsRegistration())
			throw new Exception('Construction commands registration deny in Config');

		// RegExp from http://www.php.net/manual/en/functions.user-defined.php
		if (!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/is', $name))
			throw new Exception('Bad name for command "' . $name . '"');

		if (!Config::getAllowConstructionCommandsOverride() && static::hasRegisteredCommand($name))
			throw new Exception('Construction commands override deny in Config');

		static::$registeredCommands[$name] = $callback;
	}

	static public function registerCommands($commands)
	{
		foreach ($commands as $name => $callback)
		{
			static::registerCommand($name, $callback);
		}
	}

	static public function unregisterCommand($name)
	{
		if (!Config::getAllowConstructionCommandsOverride())
			throw new Exception('Construction commands override deny in Config');

		unset(static::$registeredCommands[$name]);
	}

	static public function unregisterAllCommands()
	{
		if (!Config::getAllowConstructionCommandsOverride())
			throw new Exception('Construction commands override deny in Config');

		static::$registeredCommands = array();
	}

	static public function getRegisteredCommands()
	{
		return static::$registeredCommands;
	}

	static public function getRegisteredCommandCallback($name)
	{
		if (!static::hasRegisteredCommand($name))
			throw new Exception('Command "' . $name . '" not exists');

		return static::$registeredCommands[$name];
	}

	static public function hasRegisteredCommand($name)
	{
		return array_key_exists($name, static::$registeredCommands);
	}
}