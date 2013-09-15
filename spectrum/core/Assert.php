<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core;
use spectrum\core\SpecInterface;
use spectrum\core\Spec;

/**
 * @property Assert $not
 * @method eq($expected)
 * @method false()
 * @method gt($expected)
 * @method gte($expected)
 * @method ident($expected)
 * @method instanceof($expected)
 * @method lt($expected)
 * @method lte($expected)
 * @method null()
 * @method throwsException($expectedClass = '\Exception', $expectedStringInMessage = null, $expectedCode = null)
 * @method true()
 */
class Assert implements AssertInterface
{
	protected $testedValue;
	protected $notFlag = false;
	/**
	 * @var SpecInterface|Spec
	 */
	protected $ownerSpec;

	public function __construct(SpecInterface $ownerSpec, $testedValue)
	{
		$this->ownerSpec = $ownerSpec;
		$this->testedValue = $testedValue;
	}

	public function __call($matcherName, array $matcherArguments = array())
	{
//		$argumentsSourceCode = $this->parseArgumentsSourceCode($this->getCurrentVerifyCallSourceCode($verifyFunctionName), $verifyFunctionName);
		
		$matcherCallDetails = $this->createMatcherCallDetails();
		$matcherCallDetails->setTestedValue($this->testedValue);
		$matcherCallDetails->setNot($this->notFlag);
		$matcherCallDetails->setMatcherName($matcherName);
		$matcherCallDetails->setMatcherArguments($matcherArguments);
	
		$this->dispatchPluginEvent('onMatcherCallStart', array($matcherCallDetails, $this));
		
		$matcherFunction = $this->ownerSpec->matchers->getThroughRunningAncestors($matcherName);
		if ($matcherFunction === null)
			throw new Exception('Matcher "' . $matcherName . '" not found');
		
		try
		{
			$matcherReturnValue = call_user_func_array($matcherFunction, array_merge(array($this->testedValue), $matcherArguments));
			$matcherCallDetails->setMatcherReturnValue($matcherReturnValue);
			$result = ($this->notFlag ? !$matcherReturnValue : (bool) $matcherReturnValue);
		}
		catch (\Exception $e)
		{
			$result = false;
			$matcherCallDetails->setMatcherException($e);
		}
		
		$matcherCallDetails->setResult($result);
		$this->ownerSpec->getResultBuffer()->addResult($result, $matcherCallDetails);
		
		$this->notFlag = false;
		$this->dispatchPluginEvent('onMatcherCallFinish', array($matcherCallDetails, $this));
		return $this;
	}
	
	public function __get($name)
	{
		if ($name == 'not')
		{
			$this->notFlag = !$this->notFlag;
			return $this;
		}
		
		throw new Exception('Undefined property "Assert->' . $name . '" in method "' . __METHOD__ . '"');
	}

	/**
	 * @return MatcherCallDetailsInterface
	 */
	protected function createMatcherCallDetails()
	{
		$callDetailsClass = \spectrum\config::getMatcherCallDetailsClass();
		return new $callDetailsClass();
	}
	
	protected function dispatchPluginEvent($eventName, array $arguments = array())
	{
		$reflectionClass = new \ReflectionClass($this->ownerSpec);
		$reflectionMethod = $reflectionClass->getMethod('dispatchPluginEvent');
		$reflectionMethod->setAccessible(true);
		$reflectionMethod->invokeArgs($this->ownerSpec, array($eventName, $arguments));
	}
}