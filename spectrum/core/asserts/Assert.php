<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\asserts;
use spectrum\core\Exception;

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
 * @method throwException($expectedClass = '\Exception', $expectedStringInMessage = null, $expectedCode = null)
 * @method true()
 */
class Assert implements AssertInterface
{
	protected $actualValue;
	protected $notEnabled = false;

	public function __construct($actualValue)
	{
		$this->actualValue = $actualValue;
	}

	public function __call($name, array $expectedArgs = array())
	{
		$this->callMatcher($name, $expectedArgs);
		return $this;
	}

	protected function callMatcher($matcherName, array $expectedArgs)
	{
		$specItem = $this->getRunningSpecItem();
		$matcherCallDetails = $this->createMatcherCallDetails();
		$matcherCallDetails->setMatcherName($matcherName);
		$matcherCallDetails->setMatcherArgs($expectedArgs);

		try
		{
			$result = $specItem->matchers->callMatcher($matcherName, array_merge(array($this->getActualValue()), $expectedArgs));
			$matcherCallDetails->setMatcherReturnValue($result);

			if ($this->getNot())
				$result = !$result;
		}
		catch (\Exception $e)
		{
			if ($specItem->errorHandling->getCatchExceptionsCascade())
			{
				$result = false;
				$matcherCallDetails->setException($e);
			}
			else
				throw $e;
		}

		$specItem->getRunResultsBuffer()->addResult($result, $matcherCallDetails);
		$this->resetNot();

		if (!$result && $specItem->errorHandling->getBreakOnFirstMatcherFailCascade())
			throw new \spectrum\core\ExceptionBreak();
	}

	/**
	 * @return \spectrum\core\SpecItemIt
	 */
	protected function getRunningSpecItem()
	{
		$registryClass = \spectrum\core\Config::getRegistryClass();
		return $registryClass::getRunningSpecItem();
	}

	/**
	 * @return \spectrum\core\asserts\MatcherCallDetails
	 */
	protected function createMatcherCallDetails()
	{
		$class = \spectrum\core\Config::getMatcherCallDetailsClass();
		$matcherCallDetails = new $class();
		$matcherCallDetails->setActualValue($this->getActualValue());
		$matcherCallDetails->setNot($this->getNot());
		return $matcherCallDetails;
	}

	public function __get($name)
	{
		if ($name == 'not')
			$this->invertNot();
		else
		{
			$specItem = $this->getRunningSpecItem();

			$e = new \spectrum\core\asserts\Exception('Undefined property "Assert->' . $name . '"');

			if ($specItem->errorHandling->getCatchExceptionsCascade())
				$specItem->getRunResultsBuffer()->addResult(false, $e);
			else
				throw $e;

			if ($specItem->errorHandling->getBreakOnFirstMatcherFailCascade())
				throw new \spectrum\core\ExceptionBreak();
		}

		return $this;
	}

	public function getActualValue()
	{
		return $this->actualValue;
	}

	public function getNot()
	{
		return $this->notEnabled;
	}

	public function invertNot()
	{
		$this->notEnabled = !$this->notEnabled;
	}

	public function resetNot()
	{
		$this->notEnabled = false;
	}
}