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
		'\spectrum\core\plugins\basePlugins\reports\drivers\html\html'                                  => '\spectrum\core\plugins\basePlugins\reports\drivers\html\html',
		'\spectrum\core\plugins\basePlugins\reports\drivers\html\text'                                  => '\spectrum\core\plugins\basePlugins\reports\drivers\html\text',
		'\spectrum\core\plugins\basePlugins\reports\drivers\html\components\detailsControl'             => '\spectrum\core\plugins\basePlugins\reports\drivers\html\components\detailsControl',
		'\spectrum\core\plugins\basePlugins\reports\drivers\html\components\messages'                   => '\spectrum\core\plugins\basePlugins\reports\drivers\html\components\messages',
		'\spectrum\core\plugins\basePlugins\reports\drivers\html\components\specList'                   => '\spectrum\core\plugins\basePlugins\reports\drivers\html\components\specList',
		'\spectrum\core\plugins\basePlugins\reports\drivers\html\components\totalInfo'                  => '\spectrum\core\plugins\basePlugins\reports\drivers\html\components\totalInfo',
		'\spectrum\core\plugins\basePlugins\reports\drivers\html\components\totalResult'                => '\spectrum\core\plugins\basePlugins\reports\drivers\html\components\totalResult',
		'\spectrum\core\plugins\basePlugins\reports\drivers\html\components\resultBuffer'               => '\spectrum\core\plugins\basePlugins\reports\drivers\html\components\resultBuffer',
		'\spectrum\core\plugins\basePlugins\reports\drivers\html\components\details\matcherCall'        => '\spectrum\core\plugins\basePlugins\reports\drivers\html\components\details\matcherCall',
		'\spectrum\core\plugins\basePlugins\reports\drivers\html\components\details\unknown'            => '\spectrum\core\plugins\basePlugins\reports\drivers\html\components\details\unknown',
		'\spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\method'                => '\spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\method',
		'\spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\operator'              => '\spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\operator',
		'\spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\property'              => '\spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\property',
		'\spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variable'              => '\spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variable',
		'\spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables\arrayVar'    => '\spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables\arrayVar',
		'\spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables\boolVar'     => '\spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables\boolVar',
		'\spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables\floatVar'    => '\spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables\floatVar',
		'\spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables\functionVar' => '\spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables\functionVar',
		'\spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables\intVar'      => '\spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables\intVar',
		'\spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables\nullVar'     => '\spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables\nullVar',
		'\spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables\objectVar'   => '\spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables\objectVar',
		'\spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables\resourceVar' => '\spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables\resourceVar',
		'\spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables\stringVar'   => '\spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables\stringVar',
		'\spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables\unknownVar'  => '\spectrum\core\plugins\basePlugins\reports\drivers\html\components\code\variables\unknownVar',
	);
	
	static private $functionReplacements = array(
		'\spectrum\tools\convertCharset'               => '\spectrum\tools\convertCharset',
		'\spectrum\tools\convertLatinCharsToLowerCase' => '\spectrum\tools\convertLatinCharsToLowerCase',
		'\spectrum\tools\formatTextForOutput'          => '\spectrum\tools\formatTextForOutput',
		'\spectrum\tools\translate'                    => '\spectrum\tools\translate',
	);
	
	static private $assertClass = '\spectrum\core\Assert';
	static private $matcherCallDetailsClass = '\spectrum\core\details\MatcherCall';
	static private $phpErrorDetailsClass = '\spectrum\core\details\PhpError';
	static private $userFailDetailsClass = '\spectrum\core\details\UserFail';
	static private $specClass = '\spectrum\core\Spec';
	static private $contextDataClass = '\spectrum\core\plugins\basePlugins\contexts\Data';
	static private $resultBufferClass = '\spectrum\core\ResultBuffer';
	
	static private $registeredSpecPlugins = array(
		'\spectrum\core\plugins\basePlugins\contexts\Contexts',
		'\spectrum\core\plugins\basePlugins\ErrorHandling',
		'\spectrum\core\plugins\basePlugins\reports\Reports',
		'\spectrum\core\plugins\basePlugins\Matchers',
		'\spectrum\core\plugins\basePlugins\Messages',
		'\spectrum\core\plugins\basePlugins\Test',
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
		static::setConfigValue(static::$inputCharset, $charsetName);
	}
	
	/**
	 * @return string Already set charset or "utf-8" by default
	 */
	static public function getInputCharset()
	{
		return static::$inputCharset;
	}
	
	/**
	 * Set charset for output text (now is used in "reports" plugin, see "\spectrum\core\plugins\basePlugins\reports\*" classes)
	 * @param string $charsetName
	 * @return void
	 */
	static public function setOutputCharset($charsetName)
	{
		static::setConfigValue(static::$outputCharset, $charsetName);
	}

	/**
	 * @return string Already set charset or "utf-8" by default
	 */
	static public function getOutputCharset()
	{
		return static::$outputCharset;
	}
	
	/**
	 * Set format for output text (now is used in "reports" plugin, see "\spectrum\core\plugins\basePlugins\reports\*" classes)
	 * @param $format "html"|"text"
	 * @return void
	 */
	static public function setOutputFormat($format)
	{
		static::setConfigValue(static::$outputFormat, $format);
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
		if (preg_match("/[^\t ]/s", $string))
			throw new Exception('Incorrect char is passed to "\\' . __METHOD__ . '" method (only "\t" and " " chars are allowed)');
		
		static::setConfigValue(static::$outputIndention, $string);
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
		if (preg_match("/[^\r\n]/s", $string))
			throw new Exception('Incorrect char is passed to "\\' . __METHOD__ . '" method (only "\r" and "\n" chars are allowed)');
		
		static::setConfigValue(static::$outputNewline, $string);
	}
	
	/**
	 * @return string Already set newline or "\n" by default
	 */
	static public function getOutputNewline()
	{
		return static::$outputNewline;
	}
	
	/**
	 * Allow or deny change of "errorHandling" plugin settings modify (see "\spectrum\core\plugins\basePlugins\ErrorHandling" class)
	 * @param bool $isEnable
	 */
	static public function setAllowErrorHandlingModify($isEnable)
	{
		static::setConfigValue(static::$allowErrorHandlingModify, $isEnable);
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
	
	static public function setAssertClass($className){ static::setConfigClassValue(static::$assertClass, $className, '\spectrum\core\AssertInterface'); }
	static public function getAssertClass(){ return static::$assertClass; }
	
	static public function setMatcherCallDetailsClass($className){ static::setConfigClassValue(static::$matcherCallDetailsClass, $className, '\spectrum\core\details\MatcherCallInterface'); }
	static public function getMatcherCallDetailsClass(){ return static::$matcherCallDetailsClass; }
	
	static public function setPhpErrorDetailsClass($className){ static::setConfigClassValue(static::$phpErrorDetailsClass, $className, '\spectrum\core\details\PhpErrorInterface'); }
	static public function getPhpErrorDetailsClass(){ return static::$phpErrorDetailsClass; }
	
	static public function setUserFailDetailsClass($className){ static::setConfigClassValue(static::$userFailDetailsClass, $className, '\spectrum\core\details\UserFailInterface'); }
	static public function getUserFailDetailsClass(){ return static::$userFailDetailsClass; }
	
	static public function setSpecClass($className){ static::setConfigClassValue(static::$specClass, $className, '\spectrum\core\SpecInterface'); }
	static public function getSpecClass(){ return static::$specClass; }

	static public function setContextDataClass($className){ static::setConfigClassValue(static::$contextDataClass, $className, '\spectrum\core\plugins\basePlugins\contexts\DataInterface'); }
	static public function getContextDataClass(){ return static::$contextDataClass; }
	
	static public function setResultBufferClass($className){ static::setConfigClassValue(static::$resultBufferClass, $className, '\spectrum\core\ResultBufferInterface'); }
	static public function getResultBufferClass(){ return static::$resultBufferClass; }
	
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

		static::setConfigValue($var, $className);
	}

	static private function setConfigValue(&$var, $value)
	{
		if (static::$locked)
			throw new Exception('\spectrum\config is locked');

		$var = $value;
	}
	
	static private function convertLatinCharsToLowerCase($string)
	{
		$convertLatinCharsToLowerCaseFunction = static::getFunctionReplacement('\spectrum\tools\convertLatinCharsToLowerCase');
		return $convertLatinCharsToLowerCaseFunction($string);
	}
}