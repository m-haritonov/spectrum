<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\events\onTestCallbackCall;
use spectrum\core\plugins\Manager;
use spectrum\core\SpecItemIt;
use spectrum\core\RunResultsBuffer;
use spectrum\core\World;

require_once dirname(__FILE__) . '/../../../../init.php';

class BeforeTest extends Test
{
	private $eventName = 'onTestCallbackCallBefore';

	public function testShouldBeTriggeredAfterRunStart()
	{
		$this->createItWithPluginEventAndRun('\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');
		$event = $this->getFirstEvent($this->eventName);
		$this->assertTrue($event['isRunning']);
	}

	public function testShouldBeTriggeredAfterRunResultsBufferCreation()
	{
		$this->createItWithPluginEventAndRun('\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');
		$event = $this->getFirstEvent($this->eventName);
		$this->assertTrue($event['runResultsBuffer'] instanceof RunResultsBuffer);
	}

	public function testShouldBeTriggeredAfterWorldBuildersApply()
	{
		Manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');

		$spec = new SpecItemIt();
		$spec->builders->add(function(){ $this->foo = 'bar'; });
		$spec->setTestCallback(function(){});
		$spec->run();

		Manager::unregisterPlugin('foo');

		$event = $this->getFirstEvent($this->eventName);
		$this->assertEquals('bar', $event['worldFooValue']);
	}

	public function testShouldBeTriggeredBeforeTestCallbackExecution()
	{
		Manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');

		$spec = new SpecItemIt();
		$spec->setTestCallback(function() use(&$triggeredEventsBeforeExecution){
			$triggeredEventsBeforeExecution = \spectrum\Test::$tmp['triggeredEvents']['onTestCallbackCall'];
		});

		$spec->run();

		Manager::unregisterPlugin('foo');

		$this->assertEquals($this->eventName, $triggeredEventsBeforeExecution[0]['name']);
	}

	public function testShouldBeTriggeredOnce()
	{
		$this->createItWithPluginEventAndRun('\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');
		$this->assertEventTriggeredCount(1, $this->eventName);
	}

	public function testAdditionalArgumentsNotSet_ShouldBePassWorldToArguments()
	{
		Manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnTestCallbackCallStub');

		$spec = new SpecItemIt();
		$spec->setTestCallback(function() use($spec){});

		$spec->run();

		Manager::unregisterPlugin('foo');

		$event = $this->getFirstEvent($this->eventName);
		$this->assertEquals(1, count($event['arguments']));
		$this->assertTrue($event['arguments'][0] instanceof World);
	}
}