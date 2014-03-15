<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum;

final class config
{
	static private $inputCharset = 'utf-8';
	static private $outputCharset = 'utf-8';
	static private $outputFormat = 'html';
	static private $outputIndention = "\t";
	static private $outputNewline = "\n";
	static private $allowErrorHandlingModify = true;

	static private $classReplacements = array(
		'\spectrum\core\Assert' => '\spectrum\core\Assert',
		'\spectrum\core\ContextData' => '\spectrum\core\ContextData',
		'\spectrum\core\ResultBuffer' => '\spectrum\core\ResultBuffer',
		'\spectrum\core\Spec' => '\spectrum\core\Spec',
		
		'\spectrum\core\details\MatcherCall' => '\spectrum\core\details\MatcherCall',
		'\spectrum\core\details\PhpError' => '\spectrum\core\details\PhpError',
		'\spectrum\core\details\UserFail' => '\spectrum\core\details\UserFail',
		
		'\spectrum\core\plugins\reports\drivers\html\html'                                  => '\spectrum\core\plugins\reports\drivers\html\html',
		'\spectrum\core\plugins\reports\drivers\html\text'                                  => '\spectrum\core\plugins\reports\drivers\html\text',
		'\spectrum\core\plugins\reports\drivers\html\components\detailsControl'             => '\spectrum\core\plugins\reports\drivers\html\components\detailsControl',
		'\spectrum\core\plugins\reports\drivers\html\components\messages'                   => '\spectrum\core\plugins\reports\drivers\html\components\messages',
		'\spectrum\core\plugins\reports\drivers\html\components\specList'                   => '\spectrum\core\plugins\reports\drivers\html\components\specList',
		'\spectrum\core\plugins\reports\drivers\html\components\totalInfo'                  => '\spectrum\core\plugins\reports\drivers\html\components\totalInfo',
		'\spectrum\core\plugins\reports\drivers\html\components\totalResult'                => '\spectrum\core\plugins\reports\drivers\html\components\totalResult',
		'\spectrum\core\plugins\reports\drivers\html\components\resultBuffer'               => '\spectrum\core\plugins\reports\drivers\html\components\resultBuffer',
		'\spectrum\core\plugins\reports\drivers\html\components\details\matcherCall'        => '\spectrum\core\plugins\reports\drivers\html\components\details\matcherCall',
		'\spectrum\core\plugins\reports\drivers\html\components\details\phpError'           => '\spectrum\core\plugins\reports\drivers\html\components\details\phpError',
		'\spectrum\core\plugins\reports\drivers\html\components\details\unknown'            => '\spectrum\core\plugins\reports\drivers\html\components\details\unknown',
		'\spectrum\core\plugins\reports\drivers\html\components\details\userFail'           => '\spectrum\core\plugins\reports\drivers\html\components\details\userFail',
		'\spectrum\core\plugins\reports\drivers\html\components\code\method'                => '\spectrum\core\plugins\reports\drivers\html\components\code\method',
		'\spectrum\core\plugins\reports\drivers\html\components\code\operator'              => '\spectrum\core\plugins\reports\drivers\html\components\code\operator',
		'\spectrum\core\plugins\reports\drivers\html\components\code\property'              => '\spectrum\core\plugins\reports\drivers\html\components\code\property',
		'\spectrum\core\plugins\reports\drivers\html\components\code\variable'              => '\spectrum\core\plugins\reports\drivers\html\components\code\variable',
		'\spectrum\core\plugins\reports\drivers\html\components\code\variables\arrayVar'    => '\spectrum\core\plugins\reports\drivers\html\components\code\variables\arrayVar',
		'\spectrum\core\plugins\reports\drivers\html\components\code\variables\boolVar'     => '\spectrum\core\plugins\reports\drivers\html\components\code\variables\boolVar',
		'\spectrum\core\plugins\reports\drivers\html\components\code\variables\floatVar'    => '\spectrum\core\plugins\reports\drivers\html\components\code\variables\floatVar',
		'\spectrum\core\plugins\reports\drivers\html\components\code\variables\functionVar' => '\spectrum\core\plugins\reports\drivers\html\components\code\variables\functionVar',
		'\spectrum\core\plugins\reports\drivers\html\components\code\variables\intVar'      => '\spectrum\core\plugins\reports\drivers\html\components\code\variables\intVar',
		'\spectrum\core\plugins\reports\drivers\html\components\code\variables\nullVar'     => '\spectrum\core\plugins\reports\drivers\html\components\code\variables\nullVar',
		'\spectrum\core\plugins\reports\drivers\html\components\code\variables\objectVar'   => '\spectrum\core\plugins\reports\drivers\html\components\code\variables\objectVar',
		'\spectrum\core\plugins\reports\drivers\html\components\code\variables\resourceVar' => '\spectrum\core\plugins\reports\drivers\html\components\code\variables\resourceVar',
		'\spectrum\core\plugins\reports\drivers\html\components\code\variables\stringVar'   => '\spectrum\core\plugins\reports\drivers\html\components\code\variables\stringVar',
		'\spectrum\core\plugins\reports\drivers\html\components\code\variables\unknownVar'  => '\spectrum\core\plugins\reports\drivers\html\components\code\variables\unknownVar',
	);
	
	static private $functionReplacements = array(
		'\spectrum\_internal\addExclusionSpec' => '\spectrum\_internal\addExclusionSpec',
		'\spectrum\_internal\callFunctionOnBuildingSpec' => '\spectrum\_internal\callFunctionOnBuildingSpec',
		'\spectrum\_internal\callFunctionOnContextData' => '\spectrum\_internal\callFunctionOnContextData',
		'\spectrum\_internal\convertArguments' => '\spectrum\_internal\convertArguments',
		'\spectrum\_internal\convertArrayWithContextsToSpecs' => '\spectrum\_internal\convertArrayWithContextsToSpecs',
		'\spectrum\_internal\convertCharset' => '\spectrum\_internal\convertCharset',
		'\spectrum\_internal\convertLatinCharsToLowerCase' => '\spectrum\_internal\convertLatinCharsToLowerCase',
		'\spectrum\_internal\filterOutExclusionSpecs' => '\spectrum\_internal\filterOutExclusionSpecs',
		'\spectrum\_internal\formatTextForOutput' => '\spectrum\_internal\formatTextForOutput',
		'\spectrum\_internal\getBuildingSpec' => '\spectrum\_internal\getBuildingSpec',
		'\spectrum\_internal\getContextData' => '\spectrum\_internal\getContextData',
		'\spectrum\_internal\getExclusionSpecs' => '\spectrum\_internal\getExclusionSpecs',
		'\spectrum\_internal\getRootSpec' => '\spectrum\_internal\getRootSpec',
		'\spectrum\_internal\getRunningEndingSpec' => '\spectrum\_internal\getRunningEndingSpec',
		'\spectrum\_internal\isRunningState' => '\spectrum\_internal\isRunningState',
		'\spectrum\_internal\loadBaseMatchers' => '\spectrum\_internal\loadBaseMatchers',
		'\spectrum\_internal\normalizeSettings' => '\spectrum\_internal\normalizeSettings',
		'\spectrum\_internal\setBuildingSpec' => '\spectrum\_internal\setBuildingSpec',
		'\spectrum\_internal\translate' => '\spectrum\_internal\translate',
		
		'\spectrum\builders\addMatcher' => '\spectrum\builders\addMatcher',
		'\spectrum\builders\after' => '\spectrum\builders\after',
		'\spectrum\builders\be' => '\spectrum\builders\be',
		'\spectrum\builders\before' => '\spectrum\builders\before',
		'\spectrum\builders\fail' => '\spectrum\builders\fail',
		'\spectrum\builders\group' => '\spectrum\builders\group',
		'\spectrum\builders\message' => '\spectrum\builders\message',
		'\spectrum\builders\test' => '\spectrum\builders\test',
		'\spectrum\builders\this' => '\spectrum\builders\this',
	);
	
	static private $registeredSpecPlugins = array(
		'\spectrum\core\plugins\ContextModifiers',
		'\spectrum\core\plugins\ErrorHandling',
		'\spectrum\core\plugins\reports\Reports',
		'\spectrum\core\plugins\Matchers',
		'\spectrum\core\plugins\Messages',
		'\spectrum\core\plugins\Test',
	);
	
	static private $locked = false;

	/**
	 * Use private constructor for abstract class imitation (using of "abstract" and "final" keywords together is not allowed)
	 */
	private function __construct(){}

	/**
	 * Set charset of tests
	 * @param string $charsetName
	 * @return void
	 */
	static public function setInputCharset($charsetName)
	{
		static::throwExceptionIfLocked();
		static::$inputCharset = $charsetName;
	}
	
	/**
	 * @return string Already set charset or "utf-8" by default
	 */
	static public function getInputCharset()
	{
		return static::$inputCharset;
	}
	
	/**
	 * Set charset for output text (now is used in "reports" plugin, see "\spectrum\core\plugins\reports\*" classes)
	 * @param string $charsetName
	 * @return void
	 */
	static public function setOutputCharset($charsetName)
	{
		static::throwExceptionIfLocked();
		static::$outputCharset = $charsetName;
	}

	/**
	 * @return string Already set charset or "utf-8" by default
	 */
	static public function getOutputCharset()
	{
		return static::$outputCharset;
	}
	
	/**
	 * Set format for output text (now is used in "reports" plugin, see "\spectrum\core\plugins\reports\*" classes)
	 * @param $format "html"|"text"
	 * @return void
	 */
	static public function setOutputFormat($format)
	{
		static::throwExceptionIfLocked();
		static::$outputFormat = $format;
	}

	/**
	 * @return string Already set format or "html" by default
	 */
	static public function getOutputFormat()
	{
		return static::$outputFormat;
	}

	/**
	 * @param $string String with "\t" or " " chars
	 * @return void
	 */
	static public function setOutputIndention($string)
	{
		static::throwExceptionIfLocked();
		
		if (preg_match("/[^\t ]/s", $string))
			throw new Exception('Incorrect char is passed to "\\' . __METHOD__ . '" method (only "\t" and " " chars are allowed)');
		
		static::$outputIndention = $string;
	}
	
	/**
	 * @return string Already set indention or "\t" by default
	 */
	static public function getOutputIndention()
	{
		return static::$outputIndention;
	}

	/**
	 * @param $string String with "\r" or "\n" chars
	 * @return void
	 */
	static public function setOutputNewline($string)
	{
		static::throwExceptionIfLocked();
		
		if (preg_match("/[^\r\n]/s", $string))
			throw new Exception('Incorrect char is passed to "\\' . __METHOD__ . '" method (only "\r" and "\n" chars are allowed)');
		
		static::$outputNewline = $string;
	}
	
	/**
	 * @return string Already set newline or "\n" by default
	 */
	static public function getOutputNewline()
	{
		return static::$outputNewline;
	}
	
	/**
	 * Allow or deny change of "errorHandling" plugin settings modify (see "\spectrum\core\plugins\ErrorHandling" class)
	 * @param bool $isEnable
	 */
	static public function setAllowErrorHandlingModify($isEnable)
	{
		static::throwExceptionIfLocked();
		static::$allowErrorHandlingModify = $isEnable;
	}
	
	/**
	 * @return bool Already set value or "true" by default
	 */
	static public function getAllowErrorHandlingModify()
	{
		return static::$allowErrorHandlingModify;
	}
	
/**/
	
	static public function setClassReplacement($className, $newClassName)
	{
		if (static::$locked)
			throw new Exception('\spectrum\config is locked');
		
		$interface = $className . 'Interface';
		if (interface_exists($interface))
		{
			$reflection = new \ReflectionClass($newClassName);
			if (!$reflection->implementsInterface($interface))
				throw new Exception('Class "' . $newClassName . '" does not implement "' . $interface . '"');
		}
		
		static::$classReplacements[$className] = $newClassName;
	}
	
	static public function getClassReplacement($className)
	{
		return static::$classReplacements[$className];
	}
	
	static public function getAllClassReplacements()
	{
		return static::$classReplacements;
	}
	
/**/
	
	static public function setFunctionReplacement($functionName, $newFunctionName)
	{
		if (static::$locked)
			throw new Exception('\spectrum\config is locked');
		
		static::$functionReplacements[$functionName] = $newFunctionName;
	}
	
	static public function getFunctionReplacement($functionName)
	{
		return static::$functionReplacements[$functionName];
	}
	
	static public function getAllFunctionReplacements()
	{
		return static::$functionReplacements;
	}
	
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
		
		$accessName = $class::getAccessName();
		if ($accessName != '' && static::getRegisteredSpecPluginClassByAccessName($accessName))
			throw new Exception('Plugin with accessName "' . $accessName . '" is already registered (remove registered plugin before register new)');
		
		$activateMoment = $class::getActivateMoment();
		if (!in_array($activateMoment, array('firstAccess', 'everyAccess')))
			throw new Exception('Wrong activate moment "' . $activateMoment . '" in plugin with class "' . $class . '"');

		$num = 0;
		foreach ((array) $class::getEventListeners() as $eventListener)
		{
			$num++;
			
			if ((string) $eventListener['event'] === '')
				throw new Exception('Event for event listener #' . $num . ' does not set in plugin with class "' . $class . '"');
			
			if ((string) $eventListener['method'] === '')
				throw new Exception('Method for event listener #' . $num . ' does not set in plugin with class "' . $class . '"');
			
			if ((string) $eventListener['order'] === '')
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
					// Class names are case-insensitive for A-Z chars and case-sensitive for chars with codes from 127 through 255 (0x7f-0xff)
					if (static::convertLatinCharsToLowerCase($class) == static::convertLatinCharsToLowerCase($registeredPluginClass))
						unset(static::$registeredSpecPlugins[$key]);
				}
			}
			
			static::$registeredSpecPlugins = array_values(static::$registeredSpecPlugins);
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
			// Class names are case-insensitive for A-Z chars and case-sensitive for chars with codes from 127 through 255 (0x7f-0xff)
			if (static::convertLatinCharsToLowerCase($class) == static::convertLatinCharsToLowerCase($registeredPluginClass))
				return true;
		}
		
		return false;
	}

	static public function lock()
	{
		static::$locked = true;
	}
	
	static public function isLocked()
	{
		return static::$locked;
	}

/**/

	static private function throwExceptionIfLocked()
	{
		if (static::$locked)
			throw new Exception('\spectrum\config is locked');
	}
	
	static private function convertLatinCharsToLowerCase($string)
	{
		$convertLatinCharsToLowerCaseFunction = static::getFunctionReplacement('\spectrum\_internal\convertLatinCharsToLowerCase');
		return $convertLatinCharsToLowerCaseFunction($string);
	}
}