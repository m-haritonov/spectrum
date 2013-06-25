<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core\plugins\events\onRun;
use spectrum\core\plugins\manager;
use spectrum\core\SpecItemIt;
use spectrum\core\ResultBuffer;
use spectrum\core\Context;

require_once __DIR__ . '/../../../../init.php';

class SpecItemItTest extends Test
{
	protected $currentSpecClass = '\spectrum\core\SpecItemIt';
	
	public function testBefore_ShouldBeTriggeredBeforeTestCallbackExecution()
	{
		manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = new SpecItemIt();
		$spec->setTestCallback(function() use(&$triggeredEventsBeforeExecution){
			$triggeredEventsBeforeExecution = \spectrum\tests\Test::$tmp['triggeredEvents']['onRun'];
		});

		$spec->run();

		$this->assertEquals('onRunBefore', $triggeredEventsBeforeExecution[0]['name']);

		manager::unregisterPlugin('foo');
	}

/**/

	public function testAfter_ShouldBeTriggeredAfterTestCallbackExecution()
	{
		manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = new SpecItemIt();
		$spec->setTestCallback(function() use(&$triggeredEventsBeforeExecution){
			$triggeredEventsBeforeExecution = \spectrum\tests\Test::$tmp['triggeredEvents']['onRun'];
		});

		$spec->run();

		$this->assertEquals(1, count($triggeredEventsBeforeExecution));
		$this->assertNotEquals('onRunAfter', $triggeredEventsBeforeExecution[0]['name']);

		$this->assertEquals('onRunAfter', \spectrum\tests\Test::$tmp['triggeredEvents']['onRun'][1]['name']);

		manager::unregisterPlugin('foo');
	}

	public function testAfter_SuccessResult_ShouldBePassResultToArguments()
	{
		manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = new SpecItemIt();
		$spec->setTestCallback(function() use($spec){
			$spec->getResultBuffer()->addResult(true);
		});

		$spec->run();

		$this->assertSame(array(true), \spectrum\tests\Test::$tmp['triggeredEvents']['onRun'][1]['arguments']);

		manager::unregisterPlugin('foo');
	}

	public function testAfter_FailResult_ShouldBePassResultToArguments()
	{
		manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = new SpecItemIt();
		$spec->setTestCallback(function() use($spec){
			$spec->getResultBuffer()->addResult(false);
		});

		$spec->run();

		$this->assertSame(array(false), \spectrum\tests\Test::$tmp['triggeredEvents']['onRun'][1]['arguments']);

		manager::unregisterPlugin('foo');
	}

	public function testAfter_EmptyResult_ShouldBePassResultToArguments()
	{
		manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = new SpecItemIt();
		$spec->setTestCallback(function(){});

		$spec->run();

		$this->assertSame(array(null), \spectrum\tests\Test::$tmp['triggeredEvents']['onRun'][1]['arguments']);

		manager::unregisterPlugin('foo');
	}
}