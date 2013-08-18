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
	protected $notValue = false;
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
		$this->dispatchPluginEvent('onMatcherCallBefore', array($this->testedValue, $matcherName, $matcherArguments, $this));
		
		try
		{
//			$argumentsSourceCode = $this->parseArgumentsSourceCode($this->getCurrentVerifyCallSourceCode($verifyFunctionName), $verifyFunctionName);
			
			$callDetailsClass = \spectrum\config::getMatcherCallDetailsClass();
			$callDetails = new $callDetailsClass();
			$callDetails->setTestedValue($this->getTestedValue());
			$callDetails->setNot($this->getNot());
			$callDetails->setMatcherName($matcherName);
			$callDetails->setMatcherArguments($matcherArguments);
			
			$matcherFunction = $this->ownerSpec->matchers->getThroughRunningAncestors($matcherName);
			
			if ($matcherFunction === null)
				throw new Exception('Matcher "' . $matcherName . '" not found');
			
			$result = call_user_func_array($matcherFunction, array_merge(array($this->getTestedValue()), $matcherArguments));
			$callDetails->setMatcherReturnValue($result);
		}
		catch (\Exception $e)
		{
			$result = false;
			$callDetails = $e;
		}
		
		if ($this->getNot())
			$result = !$result;

		if ($result)
			$this->ownerSpec->getResultBuffer()->addSuccessResult($callDetails);
		else
			$this->ownerSpec->getResultBuffer()->addFailResult($callDetails);
		
		$this->resetNot();
		$this->dispatchPluginEvent('onMatcherCallAfter', array((bool) $result, $callDetails, $this));
		return $this;
	}
	
	public function __get($name)
	{
		if ($name == 'not')
		{
			$this->invertNot();
			return $this;
		}
		
		throw new Exception('Undefined property "Assert->' . $name . '" in method "' . __METHOD__ . '"');
	}
	
	protected function invertNot()
	{
		$this->notValue = !$this->notValue;
	}

	protected function getTestedValue()
	{
		return $this->testedValue;
	}

	protected function getNot()
	{
		return $this->notValue;
	}

	protected function resetNot()
	{
		$this->notValue = false;
	}
	
	protected function dispatchPluginEvent($eventName, array $arguments = array())
	{
		$reflectionClass = new \ReflectionClass($this->ownerSpec);
		$reflectionMethod = $reflectionClass->getMethod('dispatchPluginEvent');
		$reflectionMethod->setAccessible(true);
		$reflectionMethod->invokeArgs($this->ownerSpec, array($eventName, $arguments));
	}
}