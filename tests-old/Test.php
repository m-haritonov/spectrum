<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\testsOld;

require_once __DIR__ . '/init.php';

abstract class Test extends \PHPUnit_Framework_TestCase
{
/*
	protected function assertEventTriggeredCount($expectedCount, $eventName)
	{
		$eventClassName = $this->getEventClassNameByEventName($eventName);

		$count = 0;
		foreach (\spectrum\tests\Test::$temp['triggeredEvents'][$eventClassName] as $event)
		{
			if ($event['name'] == $eventName)
				$count++;
		}

		$this->assertEquals($expectedCount, $count);
	}

	protected function getEventClassNameByEventName($eventName)
	{
		return preg_replace('/(Before|After)$/s', '', $eventName);
	}
	
	public function injectToRunStartCallsCounter(\spectrum\core\SpecInterface $spec, $counterName = 'callsCounter')
	{
		$spec->__injectFunctionToRunStart(function() use($counterName) {
			\spectrum\tests\Test::$temp[$counterName] = (int) \spectrum\tests\Test::$temp[$counterName] + 1;
		});
	}

	public function injectToRunStartSaveInstanceToCollection(\spectrum\core\SpecInterface $spec)
	{
		$spec->__injectFunctionToRunStart(function() use($spec) {
			\spectrum\tests\Test::$temp['instancesCollection'][] = $spec;
		});
	}

	public function injectToRunStartCallsOrderChecker(\spectrum\core\SpecInterface $spec, $expectedZeroBasedIndex)
	{
		$spec->__injectFunctionToRunStart(function() use($spec, $expectedZeroBasedIndex) {
			\spectrum\tests\Test::$temp['callsOrderChecker'][] = $expectedZeroBasedIndex;
		});
	}

	public function assertCallsCounterEquals($expectedCount, $counterName = 'callsCounter')
	{
		$this->assertEquals($expectedCount, (int) @\spectrum\tests\Test::$temp[$counterName]);
	}

	public function assertCallsInOrder($expectedCount)
	{
		$this->assertEquals($expectedCount, count((array) @\spectrum\tests\Test::$temp['callsOrderChecker']));

		foreach ((array) \spectrum\tests\Test::$temp['callsOrderChecker'] as $actualIndex => $expectedIndex)
		{
			$this->assertEquals($expectedIndex, $actualIndex);
		}
	}

	public function assertInstanceInCollection(\spectrum\core\SpecInterface $spec)
	{
		$this->assertTrue(in_array($spec, (array) \spectrum\tests\Test::$temp['instancesCollection'], true));
	}

	public function assertInstanceNotInCollection(\spectrum\core\SpecInterface $spec)
	{
		$this->assertFalse(in_array($spec, (array) \spectrum\tests\Test::$temp['instancesCollection'], true));
	}
	
*/
}