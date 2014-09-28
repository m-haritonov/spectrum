<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core;

use spectrum\Exception;

/**
 * @property Assert $not
 * @method Assert eq($expected)
 * @method Assert gt($expected)
 * @method Assert gte($expected)
 * @method Assert ident($expected)
 * @method Assert is($expected)
 * @method Assert lt($expected)
 * @method Assert lte($expected)
 * @method Assert throwsException($expectedClass = null, $expectedStringInMessage = null, $expectedCode = null)
 */
class Assert implements AssertInterface {
	protected $testedValue;
	protected $notFlag = false;
	
	/**
	 * @var SpecInterface
	 */
	protected $ownerSpec;

	/**
	 * @param mixed $testedValue
	 */
	public function __construct(SpecInterface $ownerSpec, $testedValue) {
		$this->ownerSpec = $ownerSpec;
		$this->testedValue = $testedValue;
	}

	/**
	 * @param string $matcherName
	 * @return $this
	 */
	public function __call($matcherName, array $matcherArguments = array()) {
		if (!$this->ownerSpec->isRunning()) {
			throw new Exception('Matcher call is denied on not running spec (now spec "' . $this->ownerSpec->getName() . '" is not running)');
		}
		
		$matcherCallDetails = $this->createMatcherCallDetails();
		$matcherCallDetails->setTestedValue($this->testedValue);
		$matcherCallDetails->setNot($this->notFlag);
		$matcherCallDetails->setMatcherName($matcherName);
		$matcherCallDetails->setMatcherArguments($matcherArguments);
		
		$trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		$matcherCallDetails->setFile($trace[0]['file']);
		$matcherCallDetails->setLine($trace[0]['line']);
		
		$this->dispatchPluginEvent('onMatcherCallStart', array($matcherCallDetails));
		
		$matcherFunction = $this->ownerSpec->matchers->getThroughRunningAncestors($matcherName);
		if ($matcherFunction === null) {
			$this->ownerSpec->getResultBuffer()->addResult(false, new Exception('Matcher "' . $matcherName . '" not exists'));
			return $this;
		}
		
		try {
			$matcherReturnValue = call_user_func_array($matcherFunction, array_merge(array($this->testedValue), $matcherArguments));
			$matcherCallDetails->setMatcherReturnValue($matcherReturnValue);
			$result = ($this->notFlag ? !$matcherReturnValue : (bool) $matcherReturnValue);
		} catch (\Exception $e) {
			$result = false;
			$matcherCallDetails->setMatcherException($e);
		}
		
		$matcherCallDetails->setResult($result);
		$this->ownerSpec->getResultBuffer()->addResult($result, $matcherCallDetails);
		
		$this->notFlag = false;
		$this->dispatchPluginEvent('onMatcherCallFinish', array($matcherCallDetails));
		return $this;
	}

	/**
	 * @param string $name
	 */
	public function __get($name) {
		if ((string) $name === 'not') {
			$this->notFlag = !$this->notFlag;
			return $this;
		}
		
		throw new Exception('Undefined property "' . $name . '" in "\\' . __CLASS__ . '" class');
	}

	/**
	 * @return \spectrum\core\details\MatcherCallInterface
	 */
	protected function createMatcherCallDetails() {
		$callDetailsClass = \spectrum\config::getClassReplacement('\spectrum\core\details\MatcherCall');
		return new $callDetailsClass();
	}

	/**
	 * @param string $eventName
	 */
	protected function dispatchPluginEvent($eventName, array $arguments = array()) {
		$reflectionClass = new \ReflectionClass($this->ownerSpec);
		$reflectionMethod = $reflectionClass->getMethod('dispatchPluginEvent');
		$reflectionMethod->setAccessible(true);
		$reflectionMethod->invokeArgs($this->ownerSpec, array($eventName, $arguments));
	}
}