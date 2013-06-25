<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core\specItemIt\errorHandling\catchExceptions;
require_once __DIR__ . '/../../../../init.php';

class DisabledTest extends Test
{
	protected function setUp()
	{
		parent::setUp();
		$this->it->errorHandling->setCatchExceptions(false);
	}

	public function testShouldBeThrowExceptionAbove()
	{
		$it = $this->it;
		$it->setTestCallback(function(){
			throw new \Exception('foo');
		});

		$this->assertThrowException('\Exception', 'foo', function() use($it){
			$it->run();
		});
	}

	public function testShouldNotBeCatchPhpErrorException()
	{
		$it = $this->it;
		$it->setTestCallback(function(){
			throw new \spectrum\core\ExceptionPhpError('foo');
		});

		$this->assertThrowException('\spectrum\core\ExceptionPhpError', 'foo', function() use($it){
			$it->run();
		});
	}

	public function testShouldBeRestoreErrorHandler()
	{
		$it = $this->it;
		$it->errorHandling->setCatchPhpErrors(true);
		$it->setTestCallback(function(){
			throw new \Exception('foo');
		});

		set_error_handler('trim');
		$this->assertThrowException('\Exception', 'foo', function() use($it){ $it->run(); });
		$this->assertEquals('trim', $this->getErrorHandler());
		restore_error_handler();
	}

/*	public function testShouldBeUnsetResultBuffer()
	{
		$it = new SpecItemIt();
		$it->errorHandling->setCatchExceptions(false);
		$it->setTestCallback(function(){
			throw new \Exception('foo');
		});

		$this->assertThrowException('\Exception', 'foo', function() use($it){ $it->run(); });
		$this->assertNull($it->getResultBuffer());
	}

	public function testShouldBeRestoreRunningSpecItemInRegistry()
	{
		$runningSpecItemBackup = \spectrum\core\Registry::getRunningSpecItem();

		$it = new SpecItemIt();
		$it->errorHandling->setCatchExceptions(false);
		$it->setTestCallback(function(){
			throw new \Exception('foo');
		});

		$this->assertThrowException('\Exception', 'foo', function() use($it){ $it->run(); });
		$this->assertSame($runningSpecItemBackup, \spectrum\core\Registry::getRunningSpecItem());
	}

	public function testShouldBeStopRun()
	{
		$it = new SpecItemIt();
		$it->errorHandling->setCatchExceptions(false);
		$it->setTestCallback(function(){
			throw new \Exception('foo');
		});

		$this->assertThrowException('\Exception', 'foo', function() use($it){ $it->run(); });
		$this->assertFalse($it->isRunning());
	}

	public function testShouldBeDispatchEventOnRunAfter()
	{
		manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');

		$it = new SpecItemIt();
		$it->errorHandling->setCatchExceptions(false);
		$it->setTestCallback(function(){
			throw new \Exception('foo');
		});

		$this->assertThrowException('\Exception', 'foo', function() use($it){ $it->run(); });
		$this->assertEventTriggeredCount(1, 'onRunAfter');

		manager::unregisterPlugin('foo');
	}*/
}