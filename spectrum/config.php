<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum;

final class config {
	/**
	 * @var string
	 */
	static private $inputCharset = 'utf-8';
	
	/**
	 * @var string
	 */
	static private $outputCharset = 'utf-8';
	
	/**
	 * @var string
	 */
	static private $outputFormat = 'html';
	
	/**
	 * @var string
	 */
	static private $outputIndention = "\t";
	
	/**
	 * @var string
	 */
	static private $outputNewline = "\n";
	
	/**
	 * @var string Any of (separated by space if multiple): "all", "fail", "success", "empty", "unknown"
	 */
	static private $outputResultBufferElements = 'fail empty unknown';
	
	/**
	 * @var bool
	 */
	static private $allowErrorHandlingModify = true;

	/**
	 * @var array
	 */
	static private $classReplacements = array(
		'\spectrum\_private\reports\html\driver'                                 => '\spectrum\_private\reports\html\driver',
		'\spectrum\_private\reports\html\components\detailsControl'              => '\spectrum\_private\reports\html\components\detailsControl',
		'\spectrum\_private\reports\html\components\messages'                    => '\spectrum\_private\reports\html\components\messages',
		'\spectrum\_private\reports\html\components\specList'                    => '\spectrum\_private\reports\html\components\specList',
		'\spectrum\_private\reports\html\components\totalInfo'                   => '\spectrum\_private\reports\html\components\totalInfo',
		'\spectrum\_private\reports\html\components\totalResult'                 => '\spectrum\_private\reports\html\components\totalResult',
		'\spectrum\_private\reports\html\components\resultBuffer'                => '\spectrum\_private\reports\html\components\resultBuffer',
		'\spectrum\_private\reports\html\components\details\matcherCall'         => '\spectrum\_private\reports\html\components\details\matcherCall',
		'\spectrum\_private\reports\html\components\details\phpError'            => '\spectrum\_private\reports\html\components\details\phpError',
		'\spectrum\_private\reports\html\components\details\unknown'             => '\spectrum\_private\reports\html\components\details\unknown',
		'\spectrum\_private\reports\html\components\details\userFail'            => '\spectrum\_private\reports\html\components\details\userFail',
		'\spectrum\_private\reports\html\components\code\keyword'                => '\spectrum\_private\reports\html\components\code\keyword',
		'\spectrum\_private\reports\html\components\code\method'                 => '\spectrum\_private\reports\html\components\code\method',
		'\spectrum\_private\reports\html\components\code\operator'               => '\spectrum\_private\reports\html\components\code\operator',
		'\spectrum\_private\reports\html\components\code\property'               => '\spectrum\_private\reports\html\components\code\property',
		'\spectrum\_private\reports\html\components\code\variable'               => '\spectrum\_private\reports\html\components\code\variable',
		'\spectrum\_private\reports\html\components\code\variables\arrayVar'     => '\spectrum\_private\reports\html\components\code\variables\arrayVar',
		'\spectrum\_private\reports\html\components\code\variables\boolVar'      => '\spectrum\_private\reports\html\components\code\variables\boolVar',
		'\spectrum\_private\reports\html\components\code\variables\floatVar'     => '\spectrum\_private\reports\html\components\code\variables\floatVar',
		'\spectrum\_private\reports\html\components\code\variables\functionVar'  => '\spectrum\_private\reports\html\components\code\variables\functionVar',
		'\spectrum\_private\reports\html\components\code\variables\intVar'       => '\spectrum\_private\reports\html\components\code\variables\intVar',
		'\spectrum\_private\reports\html\components\code\variables\nullVar'      => '\spectrum\_private\reports\html\components\code\variables\nullVar',
		'\spectrum\_private\reports\html\components\code\variables\objectVar'    => '\spectrum\_private\reports\html\components\code\variables\objectVar',
		'\spectrum\_private\reports\html\components\code\variables\recursionVar' => '\spectrum\_private\reports\html\components\code\variables\recursionVar',
		'\spectrum\_private\reports\html\components\code\variables\resourceVar'  => '\spectrum\_private\reports\html\components\code\variables\resourceVar',
		'\spectrum\_private\reports\html\components\code\variables\stringVar'    => '\spectrum\_private\reports\html\components\code\variables\stringVar',
		'\spectrum\_private\reports\html\components\code\variables\unknownVar'   => '\spectrum\_private\reports\html\components\code\variables\unknownVar',
		
		'\spectrum\_private\reports\text\driver'                                 => '\spectrum\_private\reports\text\driver',
		'\spectrum\_private\reports\text\components\messages'                    => '\spectrum\_private\reports\text\components\messages',
		'\spectrum\_private\reports\text\components\specList'                    => '\spectrum\_private\reports\text\components\specList',
		'\spectrum\_private\reports\text\components\totalInfo'                   => '\spectrum\_private\reports\text\components\totalInfo',
		'\spectrum\_private\reports\text\components\totalResult'                 => '\spectrum\_private\reports\text\components\totalResult',
		'\spectrum\_private\reports\text\components\resultBuffer'                => '\spectrum\_private\reports\text\components\resultBuffer',
		'\spectrum\_private\reports\text\components\details\matcherCall'         => '\spectrum\_private\reports\text\components\details\matcherCall',
		'\spectrum\_private\reports\text\components\details\phpError'            => '\spectrum\_private\reports\text\components\details\phpError',
		'\spectrum\_private\reports\text\components\details\unknown'             => '\spectrum\_private\reports\text\components\details\unknown',
		'\spectrum\_private\reports\text\components\details\userFail'            => '\spectrum\_private\reports\text\components\details\userFail',
		'\spectrum\_private\reports\text\components\code\keyword'                => '\spectrum\_private\reports\text\components\code\keyword',
		'\spectrum\_private\reports\text\components\code\method'                 => '\spectrum\_private\reports\text\components\code\method',
		'\spectrum\_private\reports\text\components\code\operator'               => '\spectrum\_private\reports\text\components\code\operator',
		'\spectrum\_private\reports\text\components\code\property'               => '\spectrum\_private\reports\text\components\code\property',
		'\spectrum\_private\reports\text\components\code\variable'               => '\spectrum\_private\reports\text\components\code\variable',
		'\spectrum\_private\reports\text\components\code\variables\arrayVar'     => '\spectrum\_private\reports\text\components\code\variables\arrayVar',
		'\spectrum\_private\reports\text\components\code\variables\boolVar'      => '\spectrum\_private\reports\text\components\code\variables\boolVar',
		'\spectrum\_private\reports\text\components\code\variables\floatVar'     => '\spectrum\_private\reports\text\components\code\variables\floatVar',
		'\spectrum\_private\reports\text\components\code\variables\functionVar'  => '\spectrum\_private\reports\text\components\code\variables\functionVar',
		'\spectrum\_private\reports\text\components\code\variables\intVar'       => '\spectrum\_private\reports\text\components\code\variables\intVar',
		'\spectrum\_private\reports\text\components\code\variables\nullVar'      => '\spectrum\_private\reports\text\components\code\variables\nullVar',
		'\spectrum\_private\reports\text\components\code\variables\objectVar'    => '\spectrum\_private\reports\text\components\code\variables\objectVar',
		'\spectrum\_private\reports\text\components\code\variables\recursionVar' => '\spectrum\_private\reports\text\components\code\variables\recursionVar',
		'\spectrum\_private\reports\text\components\code\variables\resourceVar'  => '\spectrum\_private\reports\text\components\code\variables\resourceVar',
		'\spectrum\_private\reports\text\components\code\variables\stringVar'    => '\spectrum\_private\reports\text\components\code\variables\stringVar',
		'\spectrum\_private\reports\text\components\code\variables\unknownVar'   => '\spectrum\_private\reports\text\components\code\variables\unknownVar',
		
		'\spectrum\core\details\MatcherCall' => '\spectrum\core\details\MatcherCall',
		'\spectrum\core\details\PhpError' => '\spectrum\core\details\PhpError',
		'\spectrum\core\details\UserFail' => '\spectrum\core\details\UserFail',
		
		'\spectrum\core\Assertion' => '\spectrum\core\Assertion',
		'\spectrum\core\ContextModifiers' => '\spectrum\core\ContextModifiers',
		'\spectrum\core\Data' => '\spectrum\core\Data',
		'\spectrum\core\ErrorHandling' => '\spectrum\core\ErrorHandling',
		'\spectrum\core\Matchers' => '\spectrum\core\Matchers',
		'\spectrum\core\Messages' => '\spectrum\core\Messages',
		'\spectrum\core\ResultBuffer' => '\spectrum\core\ResultBuffer',
		'\spectrum\core\Spec' => '\spectrum\core\Spec',
		'\spectrum\core\Test' => '\spectrum\core\Test',
	);

	/**
	 * @var array
	 */
	static private $functionReplacements = array(
		'\spectrum\_private\addTestSpec' => '\spectrum\_private\addTestSpec',
		'\spectrum\_private\callFunctionOnCurrentBuildingSpec' => '\spectrum\_private\callFunctionOnCurrentBuildingSpec',
		'\spectrum\_private\callMethodThroughRunningAncestorSpecs' => '\spectrum\_private\callMethodThroughRunningAncestorSpecs',
		'\spectrum\_private\convertArguments' => '\spectrum\_private\convertArguments',
		'\spectrum\_private\convertArgumentsForSpec' => '\spectrum\_private\convertArgumentsForSpec',
		'\spectrum\_private\convertArrayWithContextsToSpecs' => '\spectrum\_private\convertArrayWithContextsToSpecs',
		'\spectrum\_private\convertCharset' => '\spectrum\_private\convertCharset',
		'\spectrum\_private\convertLatinCharsToLowerCase' => '\spectrum\_private\convertLatinCharsToLowerCase',
		'\spectrum\_private\dispatchEvent' => '\spectrum\_private\dispatchEvent',
		'\spectrum\_private\formatTextForOutput' => '\spectrum\_private\formatTextForOutput',
		'\spectrum\_private\getArrayWithContextsElementTitle' => '\spectrum\_private\getArrayWithContextsElementTitle',
		'\spectrum\_private\getCurrentBuildingSpec' => '\spectrum\_private\getCurrentBuildingSpec',
		'\spectrum\_private\getCurrentData' => '\spectrum\_private\getCurrentData',
		'\spectrum\_private\getCurrentRunningEndingSpec' => '\spectrum\_private\getCurrentRunningEndingSpec',
		'\spectrum\_private\getLastErrorHandler' => '\spectrum\_private\getLastErrorHandler',
		'\spectrum\_private\getReportClass' => '\spectrum\_private\getReportClass',
		'\spectrum\_private\getRootSpec' => '\spectrum\_private\getRootSpec',
		'\spectrum\_private\getTestSpecs' => '\spectrum\_private\getTestSpecs',
		'\spectrum\_private\handleSpecModifyDeny' => '\spectrum\_private\handleSpecModifyDeny',
		'\spectrum\_private\isRunningState' => '\spectrum\_private\isRunningState',
		'\spectrum\_private\loadBaseMatchers' => '\spectrum\_private\loadBaseMatchers',
		'\spectrum\_private\normalizeSettings' => '\spectrum\_private\normalizeSettings',
		'\spectrum\_private\removeSubsequentErrorHandlers' => '\spectrum\_private\removeSubsequentErrorHandlers',
		'\spectrum\_private\setCurrentBuildingSpec' => '\spectrum\_private\setCurrentBuildingSpec',
		'\spectrum\_private\setSettingsToSpec' => '\spectrum\_private\setSettingsToSpec',
		'\spectrum\_private\translate' => '\spectrum\_private\translate',
		'\spectrum\_private\usortWithOriginalSequencePreserving' => '\spectrum\_private\usortWithOriginalSequencePreserving',
		'\spectrum\builders\addMatcher' => '\spectrum\builders\addMatcher',
		'\spectrum\builders\after' => '\spectrum\builders\after',
		'\spectrum\builders\be' => '\spectrum\builders\be',
		'\spectrum\builders\before' => '\spectrum\builders\before',
		'\spectrum\builders\fail' => '\spectrum\builders\fail',
		'\spectrum\builders\group' => '\spectrum\builders\group',
		'\spectrum\builders\message' => '\spectrum\builders\message',
		'\spectrum\builders\self' => '\spectrum\builders\self',
		'\spectrum\builders\test' => '\spectrum\builders\test',
		'\spectrum\builders\data' => '\spectrum\builders\data',
	);
	
	/**
	 * @var array
	 */
	static private $eventListeners = array();

	/**
	 * @var bool
	 */
	static private $locked = false;

	/**
	 * Use private constructor for abstract class imitation (using of "abstract" and "final" keywords together is not allowed)
	 */
	private function __construct(){}

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
	static public function setOutputResultBufferElements($value) {
		static::throwExceptionIfLocked();
		
		if (!preg_match("/^((all|fail|success|empty|unknown)( |$))+$/s", $value)) {
			throw new Exception('Incorrect value is passed to "\\' . __METHOD__ . '" method (only combination of "all", "fail", "success", "empty", "unknown" strings are allowed)');
		}
		
		static::$outputResultBufferElements = $value;
	}
	
	/**
	 * @return string Already set value or "fail empty unknown" by default
	 */
	static public function getOutputResultBufferElements() {
		return static::$outputResultBufferElements;
	}
	
	/**
	 * @return bool True when set value contains any of values from $string, false otherwise
	 */
	static public function hasOutputResultBufferElements($string) {
		if (!preg_match("/^((all|fail|success|empty|unknown)( |$))+$/s", $string)) {
			throw new Exception('Incorrect value is passed to "\\' . __METHOD__ . '" method (only combination of "all", "fail", "success", "empty", "unknown" strings are allowed)');
		}
		
		foreach (explode(' ', $string) as $value) {
			if (preg_match('/(^| )' . preg_quote($value, '/') . '( |$)/s', static::$outputResultBufferElements)) {
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
	static public function setClassReplacement($className, $newClassName) {
		if (static::$locked) {
			throw new Exception('\spectrum\config is locked');
		}
		
		$interface = $className . 'Interface';
		if (interface_exists($interface)) {
			$reflection = new \ReflectionClass($newClassName);
			if (!$reflection->implementsInterface($interface)) {
				throw new Exception('Class "' . $newClassName . '" does not implement "' . $interface . '"');
			}
		}
		
		static::$classReplacements[$className] = $newClassName;
	}

	/**
	 * @param string $className
	 * @return string
	 */
	static public function getClassReplacement($className) {
		return static::$classReplacements[$className];
	}

	/**
	 * @return array
	 */
	static public function getAllClassReplacements() {
		return static::$classReplacements;
	}
	
/**/

	/**
	 * @param string $functionName
	 * @param callable $newFunction
	 */
	static public function setFunctionReplacement($functionName, $newFunction) {
		if (static::$locked) {
			throw new Exception('\spectrum\config is locked');
		}
		
		static::$functionReplacements[$functionName] = $newFunction;
	}

	/**
	 * @param string $functionName
	 * @return callable
	 */
	static public function getFunctionReplacement($functionName) {
		return static::$functionReplacements[$functionName];
	}

	/**
	 * @return array
	 */
	static public function getAllFunctionReplacements() {
		return static::$functionReplacements;
	}
	
/**/
	
	static public function registerEventListener($event, $callback, $order = 100) {
		if (static::$locked) {
			throw new Exception('\spectrum\config is locked');
		}
		
		static::$eventListeners[] = array('event' => $event, 'callback' => $callback, 'order' => $order);
		
		$usortWithOriginalSequencePreservingFunction = config::getFunctionReplacement('\spectrum\_private\usortWithOriginalSequencePreserving');
		$usortWithOriginalSequencePreservingFunction(static::$eventListeners, function($a, $b) {
			if ($a['order'] == $b['order']) {
				return 0;
			}
			
			return ($a['order'] < $b['order'] ? -1 : 1);
		});
		
		static::$eventListeners = array_values(static::$eventListeners);
	}

	static public function unregisterEventListener($event, $callback = null) {
		if (static::$locked) {
			throw new Exception('\spectrum\config is locked');
		}
		
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
		if (static::$locked) {
			throw new Exception('\spectrum\config is locked');
		}

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

	static private function throwExceptionIfLocked() {
		if (static::$locked) {
			throw new Exception('\spectrum\config is locked');
		}
	}

	/**
	 * @param string $string
	 * @return string
	 */
	static private function convertLatinCharsToLowerCase($string) {
		$convertLatinCharsToLowerCaseFunction = static::getFunctionReplacement('\spectrum\_private\convertLatinCharsToLowerCase');
		return $convertLatinCharsToLowerCaseFunction($string);
	}
}