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

class BeforeTest extends Test
{
	private $eventName = 'onTestCallbackCallBefore';

	public function testShouldBeTriggeredAfterRunStart()
	{
		$this->createItWithPluginEventAndRun('\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');
		$event = $this->getFirstEvent($this->eventName);
		$this->assertTrue($event['isRunning']);
	}

	public function testShouldBeTriggeredAfterResultBufferCreation()
	{
		$this->createItWithPluginEventAndRun('\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');
		$event = $this->getFirstEvent($this->eventName);
		$this->assertTrue($event['resultBuffer'] instanceof ResultBuffer);
	}

	public function testShouldBeTriggeredAfterWorldBuildersApply()
	{
		manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');

		$spec = new SpecItemIt();
		$spec->builders->add(function(){ \spectrum\core\Registry::getWorld()->foo = 'bar'; });
		$spec->setTestCallback(function(){});
		$spec->run();

		manager::unregisterPlugin('foo');

		$event = $this->getFirstEvent($this->eventName);
		$this->assertEquals('bar', $event['worldFooValue']);
	}

	public function testShouldBeTriggeredBeforeTestCallbackExecution()
	{
		manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');

		$spec = new SpecItemIt();
		$spec->setTestCallback(function() use(&$triggeredEventsBeforeExecution){
			$triggeredEventsBeforeExecution = \spectrum\tests\Test::$tmp['triggeredEvents']['onTestCallbackCall'];
		});

		$spec->run();

		manager::unregisterPlugin('foo');

		$this->assertEquals($this->eventName, $triggeredEventsBeforeExecution[0]['name']);
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

		$event = $this->getFirstEvent($this->eventName);
		$this->assertEquals(1, count($event['arguments']));
		$this->assertTrue($event['arguments'][0] instanceof Context);
	}
}