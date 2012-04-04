<?php
/*
 * Spectrum
 *
 * Copyright (c) 2011 Mikhail Kharitonov <mvkharitonov@gmail.com>
 * All rights reserved.
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 */

namespace spectrum\core;
require_once dirname(__FILE__) . '/../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
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