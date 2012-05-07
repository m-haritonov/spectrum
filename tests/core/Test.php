<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core;
require_once __DIR__ . '/../init.php';

abstract class Test extends \spectrum\Test
{
	protected $currentSpecClass;
	protected $currentSpecMockClass;

/*** Test ware ***/
	
	/**
	 * @return SpecContainerContext|
	 *         SpecContainerDescribe|
	 *         SpecItemIt
	 */
	protected function createCurrentSpec()
	{
		$args = func_get_args();
		$reflection = new \ReflectionClass($this->currentSpecClass);
		return $reflection->newInstanceArgs($args);
	}

	/**
	 * @return \spectrum\core\testEnv\SpecContainerContextMock|
	 *         \spectrum\core\testEnv\SpecContainerDescribeMock|
	 *         \spectrum\core\testEnv\SpecItemItMock
	 */
	protected function createCurrentSpecMock()
	{
		$args = func_get_args();
		$reflection = new \ReflectionClass($this->currentSpecMockClass);
		return $reflection->newInstanceArgs($args);
	}

	protected function assertEventTriggeredCount($expectedCount, $eventName)
	{
		$eventClassName = $this->getEventClassNameByEventName($eventName);

		$count = 0;
		foreach (\spectrum\Test::$tmp['triggeredEvents'][$eventClassName] as $event)
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
}