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
		$spec->builders->add(function($world){ $world->foo = 'bar'; });
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