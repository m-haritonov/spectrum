<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core\plugins\events;
use spectrum\core\plugins\manager;

require_once __DIR__ . '/../../../init.php';

abstract class Test extends \spectrum\core\Test
{
	protected function createItWithPluginEventAndRun($pluginEventClass)
	{
		manager::registerPlugin('foo', $pluginEventClass);

		$spec = new \spectrum\core\SpecItemIt();
		$spec->setTestCallback(function(){});
		$spec->run();

		manager::unregisterPlugin('foo');
	}

	protected function getFirstEvent($eventName)
	{
		return $this->getEventByIndex($eventName, 0);
	}

	protected function getSecondEvent($eventName)
	{
		return $this->getEventByIndex($eventName, 1);
	}

	protected function getEventByIndex($eventName, $index)
	{
		$eventClassName = $this->getEventClassNameByEventName($eventName);

		$event = \spectrum\tests\Test::$temp['triggeredEvents'][$eventClassName][$index];
		if ($event['name'] == $eventName)
			return $event;
		else
			return array();
	}

	protected function getContainerEvent($eventName)
	{
		 // 0 - onRunBefore from SpecContainer
		 // 1 - onRunBefore from SpecItemIt
		 // 2 - onRunAfter from SpecContainer
		 // 3 - onRunAfter from SpecItemIt
		if ($eventName == 'onRunBefore')
			return \spectrum\tests\Test::$temp['triggeredEvents']['onRun'][0];
		else if ($eventName == 'onRunAfter')
			return \spectrum\tests\Test::$temp['triggeredEvents']['onRun'][2];
		else
			return array();
	}
}