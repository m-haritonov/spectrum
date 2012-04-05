<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\specItemIt\errorHandling\catchPhpErrors\enabled\breakOnFirstPhpError;
use spectrum\core\plugins\Manager;
use spectrum\core\SpecItem;

require_once dirname(__FILE__) . '/../../../../../../init.php';

abstract class Test extends \spectrum\core\specItemIt\errorHandling\catchPhpErrors\enabled\Test
{
	public function testShouldBeReturnFalse()
	{
		$it = $this->it;
		$it->setTestCallback(function() use($it)
		{
			$it->getRunResultsBuffer()->addResult(true);
			trigger_error('');
		});

		$this->assertFalse($it->run());
	}

	public function testErrorLevelSets_ShouldBeCatchErrorsWithProperErrorLevel()
	{
		$it = $this->it;
		$it->errorHandling->setCatchPhpErrors(\E_USER_NOTICE);
		$it->setTestCallback(function()
		{
			trigger_error('', \E_USER_NOTICE);
		});

		$this->assertFalse($it->run());
	}

	public function testErrorLevelSets_ShouldNotBeCatchErrorsWithDifferentErrorLevel()
	{
		$it = $this->it;
		$it->errorHandling->setCatchPhpErrors(\E_USER_ERROR);
		$it->setTestCallback(function() use(&$isExecuted)
		{
			trigger_error('', \E_USER_NOTICE);
			$isExecuted = true;
		});

		$result = $it->run();

		$this->assertNull($result);
		$this->assertTrue($isExecuted);
	}

	public function testShouldBeIgnoreErrorsSuppressedByErrorControlOperators()
	{
		$it = $this->it;
		$it->setTestCallback(function() use(&$isExecuted, $it)
		{
			@trigger_error('');
			$it->getRunResultsBuffer()->addResult(true);
			$isExecuted = true;
		});

		$result = $it->run();

		$this->assertTrue($result);
		$this->assertTrue($isExecuted);
	}

	public function testShouldBeCatchWorldBuildersErrors()
	{
		$it = $this->it;
		$it->builders->add(function(){ trigger_error(''); });
		$it->setTestCallback(function(){});

		$this->assertFalse($it->run());
	}

	public function testShouldBeCatchWorldDestroyersErrors()
	{
		$it = $this->it;
		$it->setTestCallback(function(){});
		$it->destroyers->add(function(){ trigger_error(''); });

		$this->assertFalse($it->run());
	}

	public function testShouldBeRestoreErrorHandler()
	{
		$it = $this->it;
		$it->setTestCallback(function(){
			trigger_error('');
		});

		set_error_handler('trim');
		$it->run();
		$this->assertEquals('trim', $this->getErrorHandler());
		restore_error_handler();
	}

	public function testShouldBeRestoreErrorHandlerIfPropertyDisabledDuringRun()
	{
		$it = $this->it;
		$it->setTestCallback(function() use($it){
			$it->errorHandling->setCatchPhpErrors(false);
		});

		set_error_handler('trim');
		$it->run();
		$this->assertEquals('trim', $this->getErrorHandler());
		restore_error_handler();
	}

	public function testShouldBeUnsetRunResultsBuffer()
	{
		$it = $this->it;
		$it->setTestCallback(function(){ trigger_error(''); });
		$it->run();

		$this->assertNull($it->getRunResultsBuffer());
	}

	public function testShouldBeRestoreRunningSpecItemInRegistry()
	{
		$runningSpecItemBackup = \spectrum\core\Registry::getRunningSpecItem();

		$it = $this->it;
		$it->setTestCallback(function(){ trigger_error(''); });
		$it->run();

		$this->assertSame($runningSpecItemBackup, \spectrum\core\Registry::getRunningSpecItem());
	}

	public function testShouldBeStopRun()
	{
		$it = $this->it;
		$it->setTestCallback(function(){ trigger_error(''); });
		$it->run();

		$this->assertFalse($it->isRunning());
	}

	public function testShouldBeDispatchEventOnRunAfter()
	{
		Manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$it = $this->it;
		$it->setTestCallback(function(){ trigger_error(''); });
		$it->run();
		
		$this->assertEventTriggeredCount(1, 'onRunAfter');

		Manager::unregisterPlugin('foo');
	}
}