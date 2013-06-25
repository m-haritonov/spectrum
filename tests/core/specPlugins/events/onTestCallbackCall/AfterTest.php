<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core\plugins\events\onTestCallbackCall;
use spectrum\core\plugins\manager;
use spectrum\core\SpecItemIt;
use spectrum\core\ResultBuffer;
use spectrum\core\Context;

require_once __DIR__ . '/../../../../init.php';

class AfterTest extends Test
{
	private $eventName = 'onTestCallbackCallAfter';
	
	public function testShouldBeTriggeredAfterBeforeEvent()
	{
		$this->createItWithPluginEventAndRun('\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');

		$events = \spectrum\tests\Test::$tmp['triggeredEvents']['onTestCallbackCall'];
		$this->assertEquals('onTestCallbackCallBefore', $events[0]['name']);
		$this->assertEquals($this->eventName, $events[1]['name']);
	}

	public function testShouldBeTriggeredAfterTestCallbackExecution()
	{
		manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');

		$spec = new SpecItemIt();
		$spec->setTestCallback(function() use(&$triggeredEventsBeforeExecution){
			$triggeredEventsBeforeExecution = \spectrum\tests\Test::$tmp['triggeredEvents']['onTestCallbackCall'];
		});

		$spec->run();

		manager::unregisterPlugin('foo');

		$this->assertEquals(1, count($triggeredEventsBeforeExecution));
		$this->assertNotEquals($this->eventName, $triggeredEventsBeforeExecution[0]['name']);
		$this->assertEquals($this->eventName, \spectrum\tests\Test::$tmp['triggeredEvents']['onTestCallbackCall'][1]['name']);
	}

	public function testShouldBeTriggeredBeforeWorldDestroyersApply()
	{
		manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');

		$spec = new SpecItemIt();
		$spec->setTestCallback(function(){});
		$spec->destroyers->add(function(){ \spectrum\core\Registry::getWorld()->foo = 'bar'; });
		$spec->run();

		manager::unregisterPlugin('foo');

		$event = $this->getSecondEvent($this->eventName);
		$this->assertNull($event['worldFooValue']);
	}

	public function testShouldBeTriggeredBeforeResultBufferUnset()
	{
		manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');

		$spec = new SpecItemIt();
		$spec->setTestCallback(function() use($spec){
			$spec->getResultBuffer()->addResult(false);
		});
		$spec->run();

		manager::unregisterPlugin('foo');

		$event = $this->getSecondEvent($this->eventName);
		$this->assertTrue($event['resultBuffer'] instanceof ResultBuffer);
		$this->assertSame(array(
			array('result' => false, 'details' => null),
		), $event['resultBuffer']->getResults());
	}

	public function testShouldBeTriggeredBeforeRunStop()
	{
		$this->createItWithPluginEventAndRun('\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');
		$event = $this->getSecondEvent($this->eventName);
		$this->assertTrue($event['isRunning']);
	}

	public function testShouldBeTriggeredOnce()
	{
		$this->createItWithPluginEventAndRun('\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');
		$this->assertEventTriggeredCount(1, $this->eventName);
	}

	public function testAdditionalArgumentsNotSet_ShouldBePassWorldToArguments()
	{
		manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');

		$spec = new SpecItemIt();
		$spec->setTestCallback(function() use($spec){});

		$spec->run();

		manager::unregisterPlugin('foo');

		$event = $this->getSecondEvent($this->eventName);
		$this->assertEquals(1, count($event['arguments']));
		$this->assertTrue($event['arguments'][0] instanceof Context);
	}
}