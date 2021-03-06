<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\models;

use spectrum\core\BreakException;
use spectrum\core\config;
use spectrum\core\Exception;

/**
 * @property Assertion $not
 * @method Assertion eq($expected)
 * @method Assertion gt($expected)
 * @method Assertion gte($expected)
 * @method Assertion ident($expected)
 * @method Assertion is($expected)
 * @method Assertion lt($expected)
 * @method Assertion lte($expected)
 * @method Assertion throwsException($expectedClass = null, $expectedStringInMessage = null, $expectedCode = null)
 */
class Assertion implements AssertionInterface {
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
		
		$dispatchEventFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\dispatchEvent');
		$dispatchEventFunction('onMatcherCallStart', array($this->ownerSpec, $this, $matcherCallDetails));
		
		$matcherFunction = $this->ownerSpec->getMatchers()->getThroughRunningAncestors($matcherName);
		if ($matcherFunction === null) {
			$this->ownerSpec->getResults()->add(false, new Exception('Matcher "' . $matcherName . '" not exists'));
			return $this;
		}
		
		try {
			$matcherReturnValue = call_user_func_array($matcherFunction, array_merge(array($matcherCallDetails, $this->testedValue), $matcherArguments));
			$matcherCallDetails->setMatcherReturnValue($matcherReturnValue);
			$result = ($this->notFlag ? !$matcherReturnValue : (bool) $matcherReturnValue);
		} catch (\Exception $e) {
			$result = false;
			$matcherCallDetails->setMatcherException($e);
		}
		
		$matcherCallDetails->setResult($result);
		$this->ownerSpec->getResults()->add($result, $matcherCallDetails);
		$this->notFlag = false;
		$dispatchEventFunction('onMatcherCallFinish', array($this->ownerSpec, $this, $matcherCallDetails));
		
		if (!$matcherCallDetails->getResult() && $this->ownerSpec->getErrorHandling()->getBreakOnFirstMatcherFailThroughRunningAncestors()) {
			throw new BreakException();
		}
		
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
	 * @return \spectrum\core\models\details\MatcherCallInterface
	 */
	protected function createMatcherCallDetails() {
		$callDetailsClass = config::getCoreClassReplacement('\spectrum\core\models\details\MatcherCall');
		return new $callDetailsClass();
	}
}