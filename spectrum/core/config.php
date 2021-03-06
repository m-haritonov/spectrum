<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core;

class config {
	/**
	 * @var string
	 */
	static protected $inputCharset = 'utf-8';
	
	/**
	 * @var string
	 */
	static protected $outputCharset = 'utf-8';
	
	/**
	 * @var string
	 */
	static protected $outputFormat = 'html';
	
	/**
	 * @var string
	 */
	static protected $outputIndention = "\t";
	
	/**
	 * @var string
	 */
	static protected $outputNewline = "\n";
	
	/**
	 * @var string Any of (separated by space if multiple): "all", "fail", "success", "empty", "unknown"
	 */
	static protected $outputResults = 'fail empty unknown';
	
	/**
	 * @var bool
	 */
	static protected $allowErrorHandlingModify = true;

	/**
	 * @var array
	 */
	static protected $coreClassReplacements = array(
		'\spectrum\core\_private\reports\html\driver'                                 => '\spectrum\core\_private\reports\html\driver',
		'\spectrum\core\_private\reports\html\components\detailsControl'              => '\spectrum\core\_private\reports\html\components\detailsControl',
		'\spectrum\core\_private\reports\html\components\messages'                    => '\spectrum\core\_private\reports\html\components\messages',
		'\spectrum\core\_private\reports\html\components\specList'                    => '\spectrum\core\_private\reports\html\components\specList',
		'\spectrum\core\_private\reports\html\components\totalInfo'                   => '\spectrum\core\_private\reports\html\components\totalInfo',
		'\spectrum\core\_private\reports\html\components\totalResult'                 => '\spectrum\core\_private\reports\html\components\totalResult',
		'\spectrum\core\_private\reports\html\components\results'                     => '\spectrum\core\_private\reports\html\components\results',
		'\spectrum\core\_private\reports\html\components\details\matcherCall'         => '\spectrum\core\_private\reports\html\components\details\matcherCall',
		'\spectrum\core\_private\reports\html\components\details\phpError'            => '\spectrum\core\_private\reports\html\components\details\phpError',
		'\spectrum\core\_private\reports\html\components\details\unknown'             => '\spectrum\core\_private\reports\html\components\details\unknown',
		'\spectrum\core\_private\reports\html\components\details\userFail'            => '\spectrum\core\_private\reports\html\components\details\userFail',
		'\spectrum\core\_private\reports\html\components\code\keyword'                => '\spectrum\core\_private\reports\html\components\code\keyword',
		'\spectrum\core\_private\reports\html\components\code\method'                 => '\spectrum\core\_private\reports\html\components\code\method',
		'\spectrum\core\_private\reports\html\components\code\operator'               => '\spectrum\core\_private\reports\html\components\code\operator',
		'\spectrum\core\_private\reports\html\components\code\property'               => '\spectrum\core\_private\reports\html\components\code\property',
		'\spectrum\core\_private\reports\html\components\code\variable'               => '\spectrum\core\_private\reports\html\components\code\variable',
		'\spectrum\core\_private\reports\html\components\code\variables\arrayVar'     => '\spectrum\core\_private\reports\html\components\code\variables\arrayVar',
		'\spectrum\core\_private\reports\html\components\code\variables\boolVar'      => '\spectrum\core\_private\reports\html\components\code\variables\boolVar',
		'\spectrum\core\_private\reports\html\components\code\variables\floatVar'     => '\spectrum\core\_private\reports\html\components\code\variables\floatVar',
		'\spectrum\core\_private\reports\html\components\code\variables\functionVar'  => '\spectrum\core\_private\reports\html\components\code\variables\functionVar',
		'\spectrum\core\_private\reports\html\components\code\variables\intVar'       => '\spectrum\core\_private\reports\html\components\code\variables\intVar',
		'\spectrum\core\_private\reports\html\components\code\variables\nullVar'      => '\spectrum\core\_private\reports\html\components\code\variables\nullVar',
		'\spectrum\core\_private\reports\html\components\code\variables\objectVar'    => '\spectrum\core\_private\reports\html\components\code\variables\objectVar',
		'\spectrum\core\_private\reports\html\components\code\variables\recursionVar' => '\spectrum\core\_private\reports\html\components\code\variables\recursionVar',
		'\spectrum\core\_private\reports\html\components\code\variables\resourceVar'  => '\spectrum\core\_private\reports\html\components\code\variables\resourceVar',
		'\spectrum\core\_private\reports\html\components\code\variables\stringVar'    => '\spectrum\core\_private\reports\html\components\code\variables\stringVar',
		'\spectrum\core\_private\reports\html\components\code\variables\unknownVar'   => '\spectrum\core\_private\reports\html\components\code\variables\unknownVar',
		
		'\spectrum\core\_private\reports\text\driver'                                 => '\spectrum\core\_private\reports\text\driver',
		'\spectrum\core\_private\reports\text\components\messages'                    => '\spectrum\core\_private\reports\text\components\messages',
		'\spectrum\core\_private\reports\text\components\specList'                    => '\spectrum\core\_private\reports\text\components\specList',
		'\spectrum\core\_private\reports\text\components\totalInfo'                   => '\spectrum\core\_private\reports\text\components\totalInfo',
		'\spectrum\core\_private\reports\text\components\totalResult'                 => '\spectrum\core\_private\reports\text\components\totalResult',
		'\spectrum\core\_private\reports\text\components\results'                     => '\spectrum\core\_private\reports\text\components\results',
		'\spectrum\core\_private\reports\text\components\details\matcherCall'         => '\spectrum\core\_private\reports\text\components\details\matcherCall',
		'\spectrum\core\_private\reports\text\components\details\phpError'            => '\spectrum\core\_private\reports\text\components\details\phpError',
		'\spectrum\core\_private\reports\text\components\details\unknown'             => '\spectrum\core\_private\reports\text\components\details\unknown',
		'\spectrum\core\_private\reports\text\components\details\userFail'            => '\spectrum\core\_private\reports\text\components\details\userFail',
		'\spectrum\core\_private\reports\text\components\code\keyword'                => '\spectrum\core\_private\reports\text\components\code\keyword',
		'\spectrum\core\_private\reports\text\components\code\method'                 => '\spectrum\core\_private\reports\text\components\code\method',
		'\spectrum\core\_private\reports\text\components\code\operator'               => '\spectrum\core\_private\reports\text\components\code\operator',
		'\spectrum\core\_private\reports\text\components\code\property'               => '\spectrum\core\_private\reports\text\components\code\property',
		'\spectrum\core\_private\reports\text\components\code\variable'               => '\spectrum\core\_private\reports\text\components\code\variable',
		'\spectrum\core\_private\reports\text\components\code\variables\arrayVar'     => '\spectrum\core\_private\reports\text\components\code\variables\arrayVar',
		'\spectrum\core\_private\reports\text\components\code\variables\boolVar'      => '\spectrum\core\_private\reports\text\components\code\variables\boolVar',
		'\spectrum\core\_private\reports\text\components\code\variables\floatVar'     => '\spectrum\core\_private\reports\text\components\code\variables\floatVar',
		'\spectrum\core\_private\reports\text\components\code\variables\functionVar'  => '\spectrum\core\_private\reports\text\components\code\variables\functionVar',
		'\spectrum\core\_private\reports\text\components\code\variables\intVar'       => '\spectrum\core\_private\reports\text\components\code\variables\intVar',
		'\spectrum\core\_private\reports\text\components\code\variables\nullVar'      => '\spectrum\core\_private\reports\text\components\code\variables\nullVar',
		'\spectrum\core\_private\reports\text\components\code\variables\objectVar'    => '\spectrum\core\_private\reports\text\components\code\variables\objectVar',
		'\spectrum\core\_private\reports\text\components\code\variables\recursionVar' => '\spectrum\core\_private\reports\text\components\code\variables\recursionVar',
		'\spectrum\core\_private\reports\text\components\code\variables\resourceVar'  => '\spectrum\core\_private\reports\text\components\code\variables\resourceVar',
		'\spectrum\core\_private\reports\text\components\code\variables\stringVar'    => '\spectrum\core\_private\reports\text\components\code\variables\stringVar',
		'\spectrum\core\_private\reports\text\components\code\variables\unknownVar'   => '\spectrum\core\_private\reports\text\components\code\variables\unknownVar',
		
		'\spectrum\core\models\details\MatcherCall' => '\spectrum\core\models\details\MatcherCall',
		'\spectrum\core\models\details\PhpError'    => '\spectrum\core\models\details\PhpError',
		'\spectrum\core\models\details\UserFail'    => '\spectrum\core\models\details\UserFail',
		
		'\spectrum\core\models\Assertion'        => '\spectrum\core\models\Assertion',
		'\spectrum\core\models\ContextModifiers' => '\spectrum\core\models\ContextModifiers',
		'\spectrum\core\models\Data'             => '\spectrum\core\models\Data',
		'\spectrum\core\models\ErrorHandling'    => '\spectrum\core\models\ErrorHandling',
		'\spectrum\core\models\Executor'         => '\spectrum\core\models\Executor',
		'\spectrum\core\models\Matchers'         => '\spectrum\core\models\Matchers',
		'\spectrum\core\models\Messages'         => '\spectrum\core\models\Messages',
		'\spectrum\core\models\Result'           => '\spectrum\core\models\Result',
		'\spectrum\core\models\Results'          => '\spectrum\core\models\Results',
		'\spectrum\core\models\Spec'             => '\spectrum\core\models\Spec',
	);

	/**
	 * @var array
	 */
	static protected $coreFunctionReplacements = array(
		'\spectrum\core\_private\addTestSpec' => '\spectrum\core\_private\addTestSpec',
		'\spectrum\core\_private\callFunctionOnCurrentBuildingSpec' => '\spectrum\core\_private\callFunctionOnCurrentBuildingSpec',
		'\spectrum\core\_private\callMethodThroughRunningAncestorSpecs' => '\spectrum\core\_private\callMethodThroughRunningAncestorSpecs',
		'\spectrum\core\_private\convertArguments' => '\spectrum\core\_private\convertArguments',
		'\spectrum\core\_private\convertArgumentsForSpec' => '\spectrum\core\_private\convertArgumentsForSpec',
		'\spectrum\core\_private\convertArrayWithContextsToSpecs' => '\spectrum\core\_private\convertArrayWithContextsToSpecs',
		'\spectrum\core\_private\convertCharset' => '\spectrum\core\_private\convertCharset',
		'\spectrum\core\_private\convertLatinCharsToLowerCase' => '\spectrum\core\_private\convertLatinCharsToLowerCase',
		'\spectrum\core\_private\dispatchEvent' => '\spectrum\core\_private\dispatchEvent',
		'\spectrum\core\_private\formatTextForOutput' => '\spectrum\core\_private\formatTextForOutput',
		'\spectrum\core\_private\getArrayWithContextsElementTitle' => '\spectrum\core\_private\getArrayWithContextsElementTitle',
		'\spectrum\core\_private\getCurrentBuildingSpec' => '\spectrum\core\_private\getCurrentBuildingSpec',
		'\spectrum\core\_private\getCurrentData' => '\spectrum\core\_private\getCurrentData',
		'\spectrum\core\_private\getCurrentRunningEndingSpec' => '\spectrum\core\_private\getCurrentRunningEndingSpec',
		'\spectrum\core\_private\getLastErrorHandler' => '\spectrum\core\_private\getLastErrorHandler',
		'\spectrum\core\_private\getReportClass' => '\spectrum\core\_private\getReportClass',
		'\spectrum\core\_private\getRootSpec' => '\spectrum\core\_private\getRootSpec',
		'\spectrum\core\_private\getTestSpecs' => '\spectrum\core\_private\getTestSpecs',
		'\spectrum\core\_private\handleSpecModifyDeny' => '\spectrum\core\_private\handleSpecModifyDeny',
		'\spectrum\core\_private\isRunningState' => '\spectrum\core\_private\isRunningState',
		'\spectrum\core\_private\getBaseMatchers' => '\spectrum\core\_private\getBaseMatchers',
		'\spectrum\core\_private\normalizeSettings' => '\spectrum\core\_private\normalizeSettings',
		'\spectrum\core\_private\removeSubsequentErrorHandlers' => '\spectrum\core\_private\removeSubsequentErrorHandlers',
		'\spectrum\core\_private\setCurrentBuildingSpec' => '\spectrum\core\_private\setCurrentBuildingSpec',
		'\spectrum\core\_private\setSettingsToSpec' => '\spectrum\core\_private\setSettingsToSpec',
		'\spectrum\core\_private\translate' => '\spectrum\core\_private\translate',
		'\spectrum\core\_private\usortWithOriginalSequencePreserving' => '\spectrum\core\_private\usortWithOriginalSequencePreserving',
		
		'\spectrum\core\constructs\after' => '\spectrum\core\constructs\after',
		'\spectrum\core\constructs\be' => '\spectrum\core\constructs\be',
		'\spectrum\core\constructs\before' => '\spectrum\core\constructs\before',
		'\spectrum\core\constructs\data' => '\spectrum\core\constructs\data',
		'\spectrum\core\constructs\fail' => '\spectrum\core\constructs\fail',
		'\spectrum\core\constructs\group' => '\spectrum\core\constructs\group',
		'\spectrum\core\constructs\matcher' => '\spectrum\core\constructs\matcher',
		'\spectrum\core\constructs\message' => '\spectrum\core\constructs\message',
		'\spectrum\core\constructs\run' => '\spectrum\core\constructs\run',
		'\spectrum\core\constructs\self' => '\spectrum\core\constructs\self',
		'\spectrum\core\constructs\test' => '\spectrum\core\constructs\test',
		
		'\spectrum\core\matchers\eq' => '\spectrum\core\matchers\eq',
		'\spectrum\core\matchers\gt' => '\spectrum\core\matchers\gt',
		'\spectrum\core\matchers\gte' => '\spectrum\core\matchers\gte',
		'\spectrum\core\matchers\ident' => '\spectrum\core\matchers\ident',
		'\spectrum\core\matchers\is' => '\spectrum\core\matchers\is',
		'\spectrum\core\matchers\lt' => '\spectrum\core\matchers\lt',
		'\spectrum\core\matchers\lte' => '\spectrum\core\matchers\lte',
		'\spectrum\core\matchers\throwsException' => '\spectrum\core\matchers\throwsException',
	);
	
	/**
	 * @var array
	 */
	static protected $eventListeners = array();

	/**
	 * @var bool
	 */
	static protected $locked = false;

	/**
	 * Set charset of tests
	 * @param string $charsetName
	 */
	static public function setInputCharset($charsetName) {
		static::throwExceptionIfLocked();
		static::$inputCharset = $charsetName;
	}
	
	/**
	 * @return string Already set charset or "utf-8" by default
	 */
	static public function getInputCharset() {
		return static::$inputCharset;
	}
	
	/**
	 * Set charset for output text
	 * @param string $charsetName
	 */
	static public function setOutputCharset($charsetName) {
		static::throwExceptionIfLocked();
		static::$outputCharset = $charsetName;
	}

	/**
	 * @return string Already set charset or "utf-8" by default
	 */
	static public function getOutputCharset() {
		return static::$outputCharset;
	}
	
	/**
	 * Set format for output text
	 * @param string $format "html"|"text"
	 */
	static public function setOutputFormat($format) {
		static::throwExceptionIfLocked();
		static::$outputFormat = $format;
	}

	/**
	 * @return string Already set format or "html" by default
	 */
	static public function getOutputFormat() {
		return static::$outputFormat;
	}

	/**
	 * @param string $string String with "\t" or " " chars
	 */
	static public function setOutputIndention($string) {
		static::throwExceptionIfLocked();
		
		if (preg_match("/[^\t ]/s", $string)) {
			throw new Exception('Incorrect char is passed to "\\' . __METHOD__ . '" method (only "\t" and " " chars are allowed)');
		}
		
		static::$outputIndention = $string;
	}
	
	/**
	 * @return string Already set indention or "\t" by default
	 */
	static public function getOutputIndention() {
		return static::$outputIndention;
	}

	/**
	 * @param string $string String with "\r" or "\n" chars
	 */
	static public function setOutputNewline($string) {
		static::throwExceptionIfLocked();
		
		if (preg_match("/[^\r\n]/s", $string)) {
			throw new Exception('Incorrect char is passed to "\\' . __METHOD__ . '" method (only "\r" and "\n" chars are allowed)');
		}
		
		static::$outputNewline = $string;
	}
	
	/**
	 * @return string Already set newline or "\n" by default
	 */
	static public function getOutputNewline() {
		return static::$outputNewline;
	}
	
	/**
	 * @param string $value Any of (separated by space if multiple): "all", "fail", "success", "empty", "unknown"
	 */
	static public function setOutputResults($value) {
		static::throwExceptionIfLocked();
		
		if (!preg_match("/^((all|fail|success|empty|unknown)( |$))+$/s", $value)) {
			throw new Exception('Incorrect value is passed to "\\' . __METHOD__ . '" method (only combination of "all", "fail", "success", "empty", "unknown" strings are allowed)');
		}
		
		static::$outputResults = $value;
	}
	
	/**
	 * @return string Already set value or "fail empty unknown" by default
	 */
	static public function getOutputResults() {
		return static::$outputResults;
	}
	
	/**
	 * @return bool True when set value contains any of values from $string, false otherwise
	 */
	static public function hasOutputResults($string) {
		if (!preg_match("/^((all|fail|success|empty|unknown)( |$))+$/s", $string)) {
			throw new Exception('Incorrect value is passed to "\\' . __METHOD__ . '" method (only combination of "all", "fail", "success", "empty", "unknown" strings are allowed)');
		}
		
		foreach (explode(' ', $string) as $value) {
			if (preg_match('/(^| )' . preg_quote($value, '/') . '( |$)/s', static::$outputResults)) {
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Allow or deny error handling settings modify
	 * @param bool $isEnable
	 */
	static public function setAllowErrorHandlingModify($isEnable) {
		static::throwExceptionIfLocked();
		static::$allowErrorHandlingModify = $isEnable;
	}
	
	/**
	 * @return bool Already set value or "true" by default
	 */
	static public function getAllowErrorHandlingModify() {
		return static::$allowErrorHandlingModify;
	}
	
/**/

	/**
	 * @param string $className
	 * @param string $newClassName
	 */
	static public function setCoreClassReplacement($className, $newClassName) {
		static::throwExceptionIfLocked();
		
		$interface = $className . 'Interface';
		if (interface_exists($interface)) {
			$reflection = new \ReflectionClass($newClassName);
			if (!$reflection->implementsInterface($interface)) {
				throw new Exception('Class "' . $newClassName . '" does not implement "' . $interface . '"');
			}
		}
		
		static::$coreClassReplacements[$className] = $newClassName;
	}

	/**
	 * @param string $className
	 * @return string
	 */
	static public function getCoreClassReplacement($className) {
		return static::$coreClassReplacements[$className];
	}

	/**
	 * @return array
	 */
	static public function getAllCoreClassReplacements() {
		return static::$coreClassReplacements;
	}
	
/**/

	/**
	 * @param string $functionName
	 * @param callable $newFunction
	 */
	static public function setCoreFunctionReplacement($functionName, $newFunction) {
		static::throwExceptionIfLocked();
		static::$coreFunctionReplacements[$functionName] = $newFunction;
	}

	/**
	 * @param string $functionName
	 * @return callable
	 */
	static public function getCoreFunctionReplacement($functionName) {
		return static::$coreFunctionReplacements[$functionName];
	}

	/**
	 * @return array
	 */
	static public function getAllCoreFunctionReplacements() {
		return static::$coreFunctionReplacements;
	}
	
/**/
	
	static public function registerEventListener($event, $callback, $order = 100) {
		static::throwExceptionIfLocked();
		
		static::$eventListeners[] = array('event' => $event, 'callback' => $callback, 'order' => $order);
		
		$usortWithOriginalSequencePreservingFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\usortWithOriginalSequencePreserving');
		$usortWithOriginalSequencePreservingFunction(static::$eventListeners, function($a, $b) {
			if ($a['order'] == $b['order']) {
				return 0;
			}
			
			return ($a['order'] < $b['order'] ? -1 : 1);
		});
		
		static::$eventListeners = array_values(static::$eventListeners);
	}

	static public function unregisterEventListener($event, $callback = null) {
		static::throwExceptionIfLocked();
		
		if (is_string($callback)) {
			// Function names are case-insensitive for A-Z chars and case-sensitive for chars with codes from 127 through 255 (0x7f-0xff)
			$callback = (string) static::convertLatinCharsToLowerCase($callback);
		}

		foreach (static::$eventListeners as $key => $registeredEvent) {
			$registeredEventCallback = $registeredEvent['callback'];
			if (is_string($registeredEventCallback)) {
				// Function names are case-insensitive for A-Z chars and case-sensitive for chars with codes from 127 through 255 (0x7f-0xff)
				$registeredEventCallback = (string) static::convertLatinCharsToLowerCase($registeredEventCallback);
			}
			
			if ($event === $registeredEvent['event'] && ($registeredEventCallback === $callback || $callback === null)) {
				unset(static::$eventListeners[$key]);
			}
		}
		
		static::$eventListeners = array_values(static::$eventListeners);
	}
	
	static public function unregisterEventListeners() {
		static::throwExceptionIfLocked();
		static::$eventListeners = array();
	}

	/**
	 * @return array
	 */
	static public function getRegisteredEventListeners() {
		return static::$eventListeners;
	}

	/**
	 * @return bool
	 */
	static public function hasRegisteredEventListener($event, $callback = null) {
		if (is_string($callback)) {
			// Function names are case-insensitive for A-Z chars and case-sensitive for chars with codes from 127 through 255 (0x7f-0xff)
			$callback = (string) static::convertLatinCharsToLowerCase($callback);
		}
		
		foreach (static::$eventListeners as $registeredEvent) {
			$registeredEventCallback = $registeredEvent['callback'];
			if (is_string($registeredEventCallback)) {
				// Function names are case-insensitive for A-Z chars and case-sensitive for chars with codes from 127 through 255 (0x7f-0xff)
				$registeredEventCallback = (string) static::convertLatinCharsToLowerCase($registeredEventCallback);
			}
			
			if ($event === $registeredEvent['event'] && ($registeredEventCallback === $callback || $callback === null)) {
				return true;
			}
		}
		
		return false;
	}
	
/**/
	
	static public function getVersion() {
		return '1.0 alpha';
	}
	
/**/

	static public function lock() {
		static::$locked = true;
	}

	/**
	 * @return bool
	 */
	static public function isLocked() {
		return static::$locked;
	}

/**/

	static protected function throwExceptionIfLocked() {
		if (static::$locked) {
			throw new Exception('\spectrum\core\config is locked');
		}
	}

	/**
	 * @param string $string
	 * @return string
	 */
	static protected function convertLatinCharsToLowerCase($string) {
		$convertLatinCharsToLowerCaseFunction = static::getCoreFunctionReplacement('\spectrum\core\_private\convertLatinCharsToLowerCase');
		return $convertLatinCharsToLowerCaseFunction($string);
	}
}