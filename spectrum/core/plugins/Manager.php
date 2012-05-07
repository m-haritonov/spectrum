<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins;
use spectrum\core\Config;

class Manager implements ManagerInterface
{
	static protected $registeredPlugins = array(
		'matchers' => array('class' => '\spectrum\core\plugins\basePlugins\Matchers', 'activateMoment' => 'firstAccess'),
		'builders' => array('class' => '\spectrum\core\plugins\basePlugins\worldCreators\Builders', 'activateMoment' => 'firstAccess'),
		'destroyers' => array('class' => '\spectrum\core\plugins\basePlugins\worldCreators\Destroyers', 'activateMoment' => 'firstAccess'),
		'selector' => array('class' => '\spectrum\core\plugins\basePlugins\Selector', 'activateMoment' => 'firstAccess'),
		'identify' => array('class' => '\spectrum\core\plugins\basePlugins\Identify', 'activateMoment' => 'firstAccess'),
		'errorHandling' => array('class' => '\spectrum\core\plugins\basePlugins\ErrorHandling', 'activateMoment' => 'firstAccess'),
		'output' => array('class' => '\spectrum\core\plugins\basePlugins\Output', 'activateMoment' => 'firstAccess'),
		'messages' => array('class' => '\spectrum\core\plugins\basePlugins\Messages', 'activateMoment' => 'firstAccess'),
		'patterns' => array('class' => '\spectrum\core\plugins\basePlugins\Patterns', 'activateMoment' => 'firstAccess'),
	);

	static public function registerPlugin($accessName, $class = '\spectrum\core\plugins\basePlugins\stack\Indexed', $activateMoment = 'firstAccess')
	{
		if (!Config::getAllowPluginsRegistration())
			throw new Exception('Plugins registration deny in Config');

		if (!Config::getAllowPluginsOverride() && static::hasRegisteredPlugin($accessName))
			throw new Exception('Plugins override deny in Config');

		$reflection = new \ReflectionClass($class);
		if (!$reflection->implementsInterface('\spectrum\core\plugins\PluginInterface'))
			throw new Exception('Class "' . $class . '" should be implements PluginInterface');

		if (!in_array($activateMoment, array('specConstruct', 'firstAccess', 'everyAccess')))
			throw new Exception('Wrong activateMoment "' . $activateMoment . '" for plugin with access name "' . $accessName . '"');

		static::$registeredPlugins[$accessName] = array('class' => $class, 'activateMoment' => $activateMoment);
	}

	static public function registerPlugins($plugins)
	{
		foreach ($plugins as $accessName => $pluginInfo)
		{
			if (@$pluginInfo['class'] !== null && @$pluginInfo['activateMoment'] !== null)
				static::registerPlugin($accessName, $pluginInfo['class'], $pluginInfo['activateMoment']);
			else if (@$pluginInfo['class'] !== null)
				static::registerPlugin($accessName, $pluginInfo['class']);
			else
				static::registerPlugin($accessName);
		}
	}

	static public function unregisterPlugin($accessName)
	{
		if (!Config::getAllowPluginsOverride())
			throw new Exception('Plugins override deny in Config');

		unset(static::$registeredPlugins[$accessName]);
	}

	static public function unregisterAllPlugins()
	{
		if (!Config::getAllowPluginsOverride())
			throw new Exception('Plugins override deny in Config');

		static::$registeredPlugins = array();
	}

	static public function getRegisteredPlugins()
	{
		return static::$registeredPlugins;
	}

	static public function getAccessNamesForEventPlugins($eventName)
	{
		$eventInterface = static::getEventInterfaceByEventName($eventName);
		
		$result = array();
		foreach (static::$registeredPlugins as $accessName => $pluginInfo)
		{
			$reflection = new \ReflectionClass($pluginInfo['class']);
			if ($reflection->implementsInterface($eventInterface))
				$result[] = $accessName;
		}

		return $result;
	}

	static protected function getEventInterfaceByEventName($eventName)
	{
		$interface = $eventName;
		$interface = preg_replace('/(Before|After)$/s', '', $interface);
		$interface = preg_replace('/^on/s', 'On', $interface);
		$interface = '\spectrum\core\plugins\events\\' . $interface . 'Interface';

		if (!interface_exists($interface))
			throw new Exception('Interface "' . $interface . '" for event "' . $eventName . '" not exists');

		return $interface;
	}

	static public function getRegisteredPlugin($accessName)
	{
		if (!static::hasRegisteredPlugin($accessName))
			throw new Exception('Plugin with access name "' . $accessName . '" not exists');

		return static::$registeredPlugins[$accessName];
	}

	static public function hasRegisteredPlugin($accessName)
	{
		return array_key_exists($accessName, static::$registeredPlugins);
	}

	static public function createPluginInstance(\spectrum\core\SpecInterface $ownerSpec, $accessName)
	{
		$pluginInfo = static::getRegisteredPlugin($accessName);
		return new $pluginInfo['class']($ownerSpec, $accessName);
	}
}