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

class BeforeEachTest extends \spectrum\constructionCommands\commands\Test
{
	protected function setUp()
	{
		parent::setUp();
		$this->assertBuilderNotExistsInDescribe(0);
	}

	public function testShouldBeAllowToCallAtDeclaringState()
	{
		$describe = manager::describe('', function(){
			manager::beforeEach(function(){});
		});

		$this->assertTrue($describe->builders->isExists(0));
	}

	public function testShouldBeThrowExceptionIfCalledAtRunningState()
	{
		$this->assertThrowException('\spectrum\constructionCommands\Exception', '"beforeEach"', function()
		{
			$it = new \spectrum\core\SpecItemIt();
			$it->errorHandling->setCatchExceptions(false);
			$it->setTestCallback(function(){
				manager::beforeEach(function(){});
			});
			$it->run();
		});
	}

	public function testShouldBeReturnAddedValue()
	{
		$function = function(){};
		$describe = manager::describe('', function() use($function, &$return) {
			$return = manager::beforeEach($function);
		});

		$this->assertSame($function, $return);
	}

	public function testShouldNotBeCallCallbackDuringCall()
	{
		manager::beforeEach(function() use(&$isCalled){
			$isCalled = true;
		});

		$this->assertNull($isCalled);
	}

	public function testNoParentCommand_ShouldBeAddBuilderToRootDescribe()
	{
		$function = function(){};
		manager::beforeEach($function);

		$builder = \spectrum\RootSpec::getOnceInstance()->builders->get(0);
		$this->assertSame($function, $builder['callback']);
		$this->assertSame('each', $builder['type']);
	}

	public function testInsideDescribeCommand_ShouldBeAddBuilderToParentDescribe()
	{
		$function = function(){};
		$describe = manager::describe('', function() use($function) {
			manager::beforeEach($function);
		});

		$builder = $describe->builders->get(0);
		$this->assertSame($function, $builder['callback']);
		$this->assertSame('each', $builder['type']);
	}

	public function testInsideContextCommand_ShouldBeAddBuilderToParentContext()
	{
		$function = function(){};
		$context = manager::context('', function() use($function) {
			manager::beforeEach($function);
		});

		$builder = $context->builders->get(0);
		$this->assertSame($function, $builder['callback']);
		$this->assertSame('each', $builder['type']);
	}

/**/

	public function assertBuilderNotExistsInDescribe($name)
	{
		$describe = manager::describe('', function(){});
		$this->assertFalse($describe->builders->isExists($name));
	}
}