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

abstract class Test extends \spectrum\core\plugins\events\Test
{
	public function testBefore_ShouldBeTriggeredAfterRunStart()
	{
		manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = $this->createCurrentSpec();
		$spec->run();

		$this->assertEquals('onRunBefore', \spectrum\tests\Test::$tmp['triggeredEvents']['onRun'][0]['name']);
		$this->assertTrue(\spectrum\tests\Test::$tmp['triggeredEvents']['onRun'][0]['isRunning']);

		manager::unregisterPlugin('foo');
	}

	public function testBefore_ShouldNotBeTriggeredBeforeRunStart()
	{
		manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = $this->createCurrentSpec();
		$this->assertNull(\spectrum\tests\Test::$tmp['triggeredEvents']['onRun']);

		manager::unregisterPlugin('foo');
	}

	public function testBefore_ShouldNotBeTriggeredOnce()
	{
		manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = $this->createCurrentSpec();
		$spec->run();
		$this->assertEquals(2, count(\spectrum\tests\Test::$tmp['triggeredEvents']['onRun']));
		$this->assertEquals('onRunBefore',\spectrum\tests\Test::$tmp['triggeredEvents']['onRun'][0]['name']);
		$this->assertEquals('onRunAfter', \spectrum\tests\Test::$tmp['triggeredEvents']['onRun'][1]['name']);

		manager::unregisterPlugin('foo');
	}

	public function testBefore_ShouldNotBePassArguments()
	{
		manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = $this->createCurrentSpec();
		$spec->run();
		$this->assertSame(array(), \spectrum\tests\Test::$tmp['triggeredEvents']['onRun'][0]['arguments']);

		manager::unregisterPlugin('foo');
	}

/**/

	public function testAfter_ShouldBeTriggeredAfterOnRunBeforeEvent()
	{
		manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = $this->createCurrentSpec();
		$spec->run();

		$this->assertEquals('onRunBefore', \spectrum\tests\Test::$tmp['triggeredEvents']['onRun'][0]['name']);
		$this->assertEquals('onRunAfter', \spectrum\tests\Test::$tmp['triggeredEvents']['onRun'][1]['name']);

		manager::unregisterPlugin('foo');
	}

	public function testAfter_ShouldBeTriggeredBeforeRunStop()
	{
		manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = $this->createCurrentSpec();
		$spec->run();

		$this->assertEquals('onRunAfter', \spectrum\tests\Test::$tmp['triggeredEvents']['onRun'][1]['name']);
		$this->assertTrue(\spectrum\tests\Test::$tmp['triggeredEvents']['onRun'][1]['isRunning']);

		manager::unregisterPlugin('foo');
	}

	public function testAfter_ShouldBeTriggeredOnce()
	{
		manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$spec = $this->createCurrentSpec();
		$spec->run();
		$this->assertEquals(2, count(\spectrum\tests\Test::$tmp['triggeredEvents']['onRun']));
		$this->assertEquals('onRunBefore', \spectrum\tests\Test::$tmp['triggeredEvents']['onRun'][0]['name']);
		$this->assertEquals('onRunAfter', \spectrum\tests\Test::$tmp['triggeredEvents']['onRun'][1]['name']);

		manager::unregisterPlugin('foo');
	}
}