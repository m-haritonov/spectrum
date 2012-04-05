<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\events;
use spectrum\core\plugins\Manager;

require_once dirname(__FILE__) . '/../../../init.php';

abstract class Test extends \spectrum\core\Test
{
	protected function createItWithPluginEventAndRun($pluginEventClass)
	{
		Manager::registerPlugin('foo', $pluginEventClass);

		$spec = new \spectrum\core\SpecItemIt();
		$spec->setTestCallback(function(){});
		$spec->run();

		Manager::unregisterPlugin('foo');
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

		$event = \spectrum\Test::$tmp['triggeredEvents'][$eventClassName][$index];
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
			return \spectrum\Test::$tmp['triggeredEvents']['onRun'][0];
		else if ($eventName == 'onRunAfter')
			return \spectrum\Test::$tmp['triggeredEvents']['onRun'][2];
		else
			return array();
	}
}