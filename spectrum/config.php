<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum;

final class config
{
	static private $constructionCommandsCallBrokerClass = '\spectrum\constructionCommands\callBroker';
	static private $assertClass = '\spectrum\core\Assert';
	static private $matcherCallDetailsClass = '\spectrum\core\MatcherCallDetails';
	static private $specClass = '\spectrum\core\Spec';
	static private $contextDataClass = '\spectrum\core\ContextData';
	static private $resultBufferClass = '\spectrum\core\ResultBuffer';
	
	static private $allowBaseMatchersOverride = false;
	static private $allowErrorHandlingModify = true;
	static private $allowInputEncodingModify = true;
	static private $allowOutputEncodingModify = true;
	static private $allowReportSettingsModify = true;

	static private $registeredSpecPlugins = array(
		'\spectrum\core\plugins\basePlugins\reports\Reports',
		'\spectrum\core\plugins\basePlugins\Contexts',
		'\spectrum\core\plugins\basePlugins\ErrorHandling',
		'\spectrum\core\plugins\basePlugins\TestFunction',
		'\spectrum\core\plugins\basePlugins\Matchers',
		'\spectrum\core\plugins\basePlugins\Messages',
		'\spectrum\core\plugins\basePlugins\Output',
	);

	static private $registeredConstructionCommands = array(
		'addMatcher'                           => '\spectrum\constructionCommands\commands\addMatcher',
		'afterEach'                            => '\spectrum\constructionCommands\commands\afterEach',
		'be'                                   => '\spectrum\constructionCommands\commands\be',
		'beforeEach'                           => '\spectrum\constructionCommands\commands\beforeEach',
		'fail'                                 => '\spectrum\constructionCommands\commands\fail',
		'group'                                => '\spectrum\constructionCommands\commands\group',
		'message'                              => '\spectrum\constructionCommands\commands\message',
		'test'                                 => '\spectrum\constructionCommands\commands\test',
		'this'                                 => '\spectrum\constructionCommands\commands\this',
		'internal_addMultiplierExclusionSpec'  => '\spectrum\constructionCommands\commands\internal\addMultiplierExclusionSpec',
		'internal_getArgumentsForGroupCommand' => '\spectrum\constructionCommands\commands\internal\getArgumentsForGroupCommand',
		'internal_getArgumentsForTestCommand'  => '\spectrum\constructionCommands\commands\internal\getArgumentsForTestCommand',
		'internal_getCurrentDeclaringSpec'     => '\spectrum\constructionCommands\commands\internal\getCurrentDeclaringSpec',
		'internal_getCurrentRunningSpec'       => '\spectrum\constructionCommands\commands\internal\getCurrentRunningSpec',
		'internal_getCurrentSpec'              => '\spectrum\constructionCommands\commands\internal\getCurrentSpec',
		'internal_getInitialSpec'              => '\spectrum\constructionCommands\commands\internal\getInitialSpec',
		'internal_getMultiplierEndingSpecs'    => '\spectrum\constructionCommands\commands\internal\getMultiplierEndingSpecs',
		'internal_getMultiplierExclusionSpecs' => '\spectrum\constructionCommands\commands\internal\getMultiplierExclusionSpecs',
		'internal_getNameForArguments'         => '\spectrum\constructionCommands\commands\internal\getNameForArguments',
		'internal_isRunningState'              => '\spectrum\constructionCommands\commands\internal\isRunningState',
		'internal_loadBaseMatchers'            => '\spectrum\constructionCommands\commands\internal\loadBaseMatchers',
		'internal_setCurrentDeclaringSpec'     => '\spectrum\constructionCommands\commands\internal\setCurrentDeclaringSpec',
		'internal_setSpecSettings'             => '\spectrum\constructionCommands\commands\internal\setSpecSettings',
	);
	
	static private $locked = false;
	
	// For abstract class imitation (using "abstract" keyword with "final" not allowed)
	private function __construct(){}

	static public function setConstructionCommandsCallBrokerClass($className){ return static::setConfigClassValue(static::$constructionCommandsCallBrokerClass, $className, '\spectrum\constructionCommands\callBrokerInterface'); }
	static public function getConstructionCommandsCallBrokerClass(){ return static::$constructionCommandsCallBrokerClass; }

	static public function setAssertClass($className){ return static::setConfigClassValue(static::$assertClass, $className, '\spectrum\core\AssertInterface'); }
	static public function getAssertClass(){ return static::$assertClass; }
	
	static public function setMatcherCallDetailsClass($className){ return static::setConfigClassValue(static::$matcherCallDetailsClass, $className, '\spectrum\core\MatcherCallDetailsInterface'); }
	static public function getMatcherCallDetailsClass(){ return static::$matcherCallDetailsClass; }
	
	static public function setSpecClass($className){ return static::setConfigClassValue(static::$specClass, $className, '\spectrum\core\SpecInterface'); }
	static public function getSpecClass(){ return static::$specClass; }

	static public function setContextDataClass($className){ return static::setConfigClassValue(static::$contextDataClass, $className, '\spectrum\core\ContextDataInterface'); }
	static public function getContextDataClass(){ return static::$contextDataClass; }
	
	static public function setResultBufferClass($className){ return static::setConfigClassValue(static::$resultBufferClass, $className, '\spectrum\core\ResultBufferInterface'); }
	static public function getResultBufferClass(){ return static::$resultBufferClass; }
	
	/**/
	
	static public function setAllowBaseMatchersOverride($isEnable){ return static::setConfigValue(static::$allowBaseMatchersOverride, $isEnable); }
	static public function getAllowBaseMatchersOverride(){ return static::$allowBaseMatchersOverride; }

	static public function setAllowErrorHandlingModify($isEnable){ return static::setConfigValue(static::$allowErrorHandlingModify, $isEnable); }
	static public function getAllowErrorHandlingModify(){ return static::$allowErrorHandlingModify; }

	static public function setAllowInputEncodingModify($isEnable){ return static::setConfigValue(static::$allowInputEncodingModify, $isEnable); }
	static public function getAllowInputEncodingModify(){ return static::$allowInputEncodingModify; }

	static public function setAllowOutputEncodingModify($isEnable){ return static::setConfigValue(static::$allowOutputEncodingModify, $isEnable); }
	static public function getAllowOutputEncodingModify(){ return static::$allowOutputEncodingModify; }
	
	static public function setAllowReportSettingsModify($isEnable){ return static::setConfigValue(static::$allowReportSettingsModify, $isEnable); }
	static public function getAllowReportSettingsModify(){ return static::$allowReportSettingsModify; }

/**/
	
	static public function registerSpecPlugin($class)
	{
		if (static::$locked)
			throw new Exception('\spectrum\config is locked');
		
		// Get origin class name (in origin case)
		$reflectionClass = new \ReflectionClass($class);
		$class = '\\' . $reflectionClass->getName();
		
		$reflection = new \ReflectionClass($class);
		if (!$reflection->implementsInterface('\spectrum\core\plugins\PluginInterface'))
			throw new Exception('Plugin class "' . $class . '" does not implement PluginInterface');
		
		if (static::hasRegisteredSpecPlugin($class))
			throw new Exception('Plugin with class "' . $class . '" is already registered');
		
		if (static::getRegisteredSpecPluginClassByAccessName($class::getAccessName()))
			throw new Exception('Plugin with accessName "' . $class::getAccessName() . '" is already registered (remove registered plugin before register new)');
		
		$activateMoment = $class::getActivateMoment();
		if (!in_array($activateMoment, array('firstAccess', 'everyAccess', 'specConstruct')))
			throw new Exception('Wrong activate moment "' . $activateMoment . '" in plugin with class "' . $class . '"');

		$num = 0;
		foreach ((array) $class::getEventListeners() as $eventListener)
		{
			$num++;
			
			if ($eventListener['event'] == '')
				throw new Exception('Event for event listener #' . $num . ' does not set in plugin with class "' . $class . '"');
			
			if ($eventListener['method'] == '')
				throw new Exception('Method for event listener #' . $num . ' does not set in plugin with class "' . $class . '"');
			
			if ($eventListener['order'] == '')
				throw new Exception('Order for event listener #' . $num . ' does not set in plugin with class "' . $class . '"');
		}
		
		static::$registeredSpecPlugins[] = $class;
	}

	static public function unregisterSpecPlugins($classes = null)
	{
		if (static::$locked)
			throw new Exception('\spectrum\config is locked');

		$classes = (array) $classes;
		if (!$classes)
			static::$registeredSpecPlugins = array();
		else
		{
			foreach (static::$registeredSpecPlugins as $key => $registeredPluginClass)
			{
				foreach ($classes as $class)
				{
					if (mb_strtolower($class) == mb_strtolower($registeredPluginClass))
						unset(static::$registeredSpecPlugins[$key]);
				}
			}
		}
	}

	static public function getRegisteredSpecPlugins()
	{
		return static::$registeredSpecPlugins;
	}
	
	static public function getRegisteredSpecPluginClassByAccessName($pluginAccessName)
	{
		foreach (static::getRegisteredSpecPlugins() as $pluginClass)
		{
			if ($pluginClass::getAccessName() == $pluginAccessName)
				return $pluginClass;
		}
		
		return null;
	}
	
	static public function hasRegisteredSpecPlugin($class)
	{
		foreach (static::$registeredSpecPlugins as $registeredPluginClass)
		{
			if (mb_strtolower($class) == mb_strtolower($registeredPluginClass))
				return true;
		}
		
		return false;
	}

/**/

	static public function registerConstructionCommand($name, $function)
	{
		if (static::$locked)
			throw new Exception('\spectrum\config is locked');
		
		if (static::hasRegisteredConstructionCommand($name))
			throw new Exception('Construction command with name "' . $name . '" is already registered (remove registered construction command before register new)');
		
		// RegExp from http://www.php.net/manual/en/functions.user-defined.php
		if (!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/is', $name))
			throw new Exception('Construction command name "' . $name . '" has deny symbols');

		static::$registeredConstructionCommands[$name] = $function;
	}

	static public function unregisterConstructionCommands($names = null)
	{
		if (static::$locked)
			throw new Exception('\spectrum\config is locked');
		
		$names = (array) $names;
		if (!$names)
			static::$registeredConstructionCommands = array();
		else
		{
			foreach ($names as $name)
				unset(static::$registeredConstructionCommands[$name]);
		}
	}

	static public function getRegisteredConstructionCommands()
	{
		return static::$registeredConstructionCommands;
	}

	static public function getRegisteredConstructionCommandFunction($name)
	{
		return @static::$registeredConstructionCommands[$name];
	}

	static public function hasRegisteredConstructionCommand($name)
	{
		return array_key_exists($name, static::$registeredConstructionCommands);
	}

/**/
	
	static public function lock(){ static::$locked = true; }
	static public function isLocked(){ return static::$locked; }

	static private function setConfigClassValue(&$var, $className, $requiredInterface = null)
	{
		if (!class_exists($className))
			throw new Exception('Class "' . $className . '" not exists');
		else if ($requiredInterface != null)
		{
			$reflection = new \ReflectionClass($className);
			if (!$reflection->implementsInterface($requiredInterface))
				throw new Exception('Class "' . $className . '" should be implement interface "' . $requiredInterface . '"');
		}

		return static::setConfigValue($var, $className);
	}

	static private function setConfigValue(&$var, $value)
	{
		if (static::$locked)
			throw new Exception('\spectrum\config is locked');

		$var = $value;
	}
}