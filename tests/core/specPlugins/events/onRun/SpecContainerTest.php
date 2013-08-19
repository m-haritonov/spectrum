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

abstract class SpecContainerTest extends Test
{
	public function testBefore_ShouldBeTriggeredBeforeRunChildren()
	{
		manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->It
		');

		$specs[1]->setTestCallback(function() use(&$triggeredEventsBeforeExecution){
			$triggeredEventsBeforeExecution = \spectrum\tests\Test::$temp['triggeredEvents']['onRun'];
		});

		$specs[0]->run();

		$this->assertEquals('onRunBefore', $triggeredEventsBeforeExecution[0]['name']);

		manager::unregisterPlugin('foo');
	}

/**/

	public function testAfter_ShouldBeTriggeredAfterRunChildren()
	{
		manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->It
		');

		$specs[1]->setTestCallback(function() use(&$triggeredEventsBeforeExecution){
			$triggeredEventsBeforeExecution = \spectrum\tests\Test::$temp['triggeredEvents']['onRun'];
		});

		$specs[0]->run();

		$this->assertGreaterThan(0, count($triggeredEventsBeforeExecution));
		foreach ($triggeredEventsBeforeExecution as $event)
		{
			$this->assertNotEquals('onRunAfter', $event['name']);
		}

		$event = $this->getContainerEvent('onRunAfter');
		$this->assertEquals('onRunAfter', $event['name']);

		manager::unregisterPlugin('foo');
	}

	public function testAfter_SuccessResult_ShouldBePassResultToArguments()
	{
		manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->It
		');

		$specs[1]->setTestCallback(function() use($specs){
			$specs[1]->getResultBuffer()->addResult(true);
		});

		$specs[0]->run();

		$event = $this->getContainerEvent('onRunAfter');
		$this->assertSame(array(true), $event['arguments']);

		manager::unregisterPlugin('foo');
	}

	public function testAfter_FailResult_ShouldBePassResultToArguments()
	{
		manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->It
		');

		$specs[1]->setTestCallback(function() use($specs){
			$specs[1]->getResultBuffer()->addResult(false);
		});

		$specs[0]->run();

		$event = $this->getContainerEvent('onRunAfter');
		$this->assertSame(array(false), $event['arguments']);

		manager::unregisterPlugin('foo');
	}

	public function testAfter_EmptyResult_ShouldBePassResultToArguments()
	{
		manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$specs = $this->createSpecsTree('
			' . $this->currentSpecClass . '
			->It
		');

		$specs[1]->setTestCallback(function(){});

		$specs[0]->run();

		$event = $this->getContainerEvent('onRunAfter');
		$this->assertSame(array(null), $event['arguments']);

		manager::unregisterPlugin('foo');
	}
}