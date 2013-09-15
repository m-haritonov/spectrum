<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\constructionCommands\commands;
use spectrum\constructionCommands\manager;

require_once __DIR__ . '/../../init.php';

class AfterEachTest extends \spectrum\constructionCommands\commands\Test
{
	protected function setUp()
	{
		parent::setUp();
		$this->assertDestroyerNotExistsInDescribe(0);
	}

	public function testShouldBeAllowToCallAtDeclaringState()
	{
		$describe = manager::describe('', function(){
			manager::afterEach(function(){});
		});

		$this->assertTrue($describe->destroyers->isExists(0));
	}

	public function testShouldBeThrowExceptionIfCalledAtRunningState()
	{
		$this->assertThrowException('\spectrum\constructionCommands\Exception', '"afterEach"', function()
		{
			$it = new \spectrum\core\SpecItemIt();
			$it->errorHandling->setCatchExceptions(false);
			$it->setTestCallback(function(){
				manager::afterEach(function(){});
			});
			$it->run();
		});
	}

	public function testShouldBeReturnAddedValue()
	{
		$function = function(){};
		$describe = manager::describe('', function() use($function, &$return) {
			$return = manager::afterEach($function);
		});

		$this->assertSame($function, $return);
	}

	public function testShouldNotBeCallCallbackDuringCall()
	{
		manager::afterEach(function() use(&$isCalled){
			$isCalled = true;
		});

		$this->assertNull($isCalled);
	}

	public function testNoParentCommand_ShouldBeAddDestroyerToRootDescribe()
	{
		$function = function(){};
		manager::afterEach($function);

		$destroyer = \spectrum\RootSpec::getOnceInstance()->destroyers->get(0);
		$this->assertSame($function, $destroyer['callback']);
		$this->assertSame('each', $destroyer['type']);
	}

	public function testInsideDescribeCommand_ShouldBeAddDestroyerToParentDescribe()
	{
		$function = function(){};
		$describe = manager::describe('', function() use($function) {
			manager::afterEach($function);
		});

		$destroyer = $describe->destroyers->get(0);
		$this->assertSame($function, $destroyer['callback']);
		$this->assertSame('each', $destroyer['type']);
	}

	public function testInsideContextCommand_ShouldBeAddDestroyerToParentContext()
	{
		$function = function(){};
		$context = manager::context('', function() use($function) {
			manager::afterEach($function);
		});

		$destroyer = $context->destroyers->get(0);
		$this->assertSame($function, $destroyer['callback']);
		$this->assertSame('each', $destroyer['type']);
	}

/**/

	public function assertDestroyerNotExistsInDescribe($name)
	{
		$describe = manager::describe('', function(){});
		$this->assertFalse($describe->destroyers->isExists($name));
	}
}