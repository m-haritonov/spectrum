<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\baseCommands;
use spectrum\constructionCommands\Manager;

require_once dirname(__FILE__) . '/../../init.php';

class BeforeEachTest extends \spectrum\constructionCommands\baseCommands\Test
{
	protected function setUp()
	{
		parent::setUp();
		$this->assertBuilderNotExistsInDescribe(0);
	}

	public function testShouldBeAllowToCallAtDeclaringState()
	{
		$describe = Manager::describe('', function(){
			Manager::beforeEach(function(){});
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
				Manager::beforeEach(function(){});
			});
			$it->run();
		});
	}

	public function testShouldBeReturnAddedValue()
	{
		$function = function(){};
		$describe = Manager::describe('', function() use($function, &$return) {
			$return = Manager::beforeEach($function);
		});

		$this->assertSame($function, $return);
	}

	public function testShouldNotBeCallCallbackDuringCall()
	{
		Manager::beforeEach(function() use(&$isCalled){
			$isCalled = true;
		});

		$this->assertNull($isCalled);
	}

	public function testNoParentCommand_ShouldBeAddBuilderToRootDescribe()
	{
		$function = function(){};
		Manager::beforeEach($function);

		$builder = \spectrum\RootDescribe::getOnceInstance()->builders->get(0);
		$this->assertSame($function, $builder['callback']);
		$this->assertSame('each', $builder['type']);
	}

	public function testInsideDescribeCommand_ShouldBeAddBuilderToParentDescribe()
	{
		$function = function(){};
		$describe = Manager::describe('', function() use($function) {
			Manager::beforeEach($function);
		});

		$builder = $describe->builders->get(0);
		$this->assertSame($function, $builder['callback']);
		$this->assertSame('each', $builder['type']);
	}

	public function testInsideContextCommand_ShouldBeAddBuilderToParentContext()
	{
		$function = function(){};
		$context = Manager::context('', function() use($function) {
			Manager::beforeEach($function);
		});

		$builder = $context->builders->get(0);
		$this->assertSame($function, $builder['callback']);
		$this->assertSame('each', $builder['type']);
	}

/**/

	public function assertBuilderNotExistsInDescribe($name)
	{
		$describe = Manager::describe('', function(){});
		$this->assertFalse($describe->builders->isExists($name));
	}
}