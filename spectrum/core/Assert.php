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
		
		$callDetailsClass = \spectrum\config::getMatcherCallDetailsClass();
		/** @var MatcherCallDetailsInterface $callDetails */
		$callDetails = new $callDetailsClass();
		$callDetails->setTestedValue($this->testedValue);
		$callDetails->setNot($this->notFlag);
		$callDetails->setMatcherName($matcherName);
		$callDetails->setMatcherArguments($matcherArguments);
	
		$this->dispatchPluginEvent('onMatcherCallBefore', array($callDetails, $this));
		
		$matcherFunction = $this->ownerSpec->matchers->getThroughRunningAncestors($matcherName);
		if ($matcherFunction === null)
			throw new Exception('Matcher "' . $matcherName . '" not found');
		
		try
		{
			$matcherReturnValue = call_user_func_array($matcherFunction, array_merge(array($this->testedValue), $matcherArguments));
			$callDetails->setMatcherReturnValue($matcherReturnValue);
			$result = ($this->notFlag ? !$matcherReturnValue : (bool) $matcherReturnValue);
		}
		catch (\Exception $e)
		{
			$result = false;
			$callDetails->setMatcherException($e);
		}
		
		$callDetails->setResult($result);
		$this->ownerSpec->getResultBuffer()->addResult($result, $callDetails);
		
		$this->notFlag = false;
		$this->dispatchPluginEvent('onMatcherCallAfter', array($callDetails, $this));
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
	
	protected function dispatchPluginEvent($eventName, array $arguments = array())
	{
		$reflectionClass = new \ReflectionClass($this->ownerSpec);
		$reflectionMethod = $reflectionClass->getMethod('dispatchPluginEvent');
		$reflectionMethod->setAccessible(true);
		$reflectionMethod->invokeArgs($this->ownerSpec, array($eventName, $arguments));
	}
}