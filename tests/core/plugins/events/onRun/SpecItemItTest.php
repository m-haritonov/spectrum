<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\events\onRun;
use spectrum\core\plugins\Manager;
use spectrum\core\SpecItemIt;
use spectrum\core\RunResultsBuffer;
use spectrum\core\World;

require_once dirname(__FILE__) . '/../../../../init.php';

class SpecItemItTest extends Test
{
	protected $currentSpecClass = '\spectrum\core\SpecItemIt';
	
	public function testBefore_ShouldBeTriggeredBeforeTestCallbackExecution()
	{
		Manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = new SpecItemIt();
		$spec->setTestCallback(function() use(&$triggeredEventsBeforeExecution){
			$triggeredEventsBeforeExecution = \spectrum\Test::$tmp['triggeredEvents']['onRun'];
		});

		$spec->run();

		$this->assertEquals('onRunBefore', $triggeredEventsBeforeExecution[0]['name']);

		Manager::unregisterPlugin('foo');
	}

/**/

	public function testAfter_ShouldBeTriggeredAfterTestCallbackExecution()
	{
		Manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = new SpecItemIt();
		$spec->setTestCallback(function() use(&$triggeredEventsBeforeExecution){
			$triggeredEventsBeforeExecution = \spectrum\Test::$tmp['triggeredEvents']['onRun'];
		});

		$spec->run();

		$this->assertEquals(1, count($triggeredEventsBeforeExecution));
		$this->assertNotEquals('onRunAfter', $triggeredEventsBeforeExecution[0]['name']);

		$this->assertEquals('onRunAfter', \spectrum\Test::$tmp['triggeredEvents']['onRun'][1]['name']);

		Manager::unregisterPlugin('foo');
	}

	public function testAfter_SuccessResult_ShouldBePassResultToArguments()
	{
		Manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = new SpecItemIt();
		$spec->setTestCallback(function() use($spec){
			$spec->getRunResultsBuffer()->addResult(true);
		});

		$spec->run();

		$this->assertSame(array(true), \spectrum\Test::$tmp['triggeredEvents']['onRun'][1]['arguments']);

		Manager::unregisterPlugin('foo');
	}

	public function testAfter_FailResult_ShouldBePassResultToArguments()
	{
		Manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = new SpecItemIt();
		$spec->setTestCallback(function() use($spec){
			$spec->getRunResultsBuffer()->addResult(false);
		});

		$spec->run();

		$this->assertSame(array(false), \spectrum\Test::$tmp['triggeredEvents']['onRun'][1]['arguments']);

		Manager::unregisterPlugin('foo');
	}

	public function testAfter_EmptyResult_ShouldBePassResultToArguments()
	{
		Manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = new SpecItemIt();
		$spec->setTestCallback(function(){});

		$spec->run();

		$this->assertSame(array(null), \spectrum\Test::$tmp['triggeredEvents']['onRun'][1]['arguments']);

		Manager::unregisterPlugin('foo');
	}
}