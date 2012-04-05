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

class AfterEachTest extends \spectrum\constructionCommands\baseCommands\Test
{
	protected function setUp()
	{
		parent::setUp();
		$this->assertDestroyerNotExistsInDescribe(0);
	}

	public function testShouldBeAllowToCallAtDeclaringState()
	{
		$describe = Manager::describe('', function(){
			Manager::afterEach(function(){});
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
				Manager::afterEach(function(){});
			});
			$it->run();
		});
	}

	public function testShouldBeReturnAddedValue()
	{
		$function = function(){};
		$describe = Manager::describe('', function() use($function, &$return) {
			$return = Manager::afterEach($function);
		});

		$this->assertSame($function, $return);
	}

	public function testShouldNotBeCallCallbackDuringCall()
	{
		Manager::afterEach(function() use(&$isCalled){
			$isCalled = true;
		});

		$this->assertNull($isCalled);
	}

	public function testNoParentCommand_ShouldBeAddDestroyerToRootDescribe()
	{
		$function = function(){};
		Manager::afterEach($function);

		$destroyer = \spectrum\RootDescribe::getOnceInstance()->destroyers->get(0);
		$this->assertSame($function, $destroyer['callback']);
		$this->assertSame('each', $destroyer['type']);
	}

	public function testInsideDescribeCommand_ShouldBeAddDestroyerToParentDescribe()
	{
		$function = function(){};
		$describe = Manager::describe('', function() use($function) {
			Manager::afterEach($function);
		});

		$destroyer = $describe->destroyers->get(0);
		$this->assertSame($function, $destroyer['callback']);
		$this->assertSame('each', $destroyer['type']);
	}

	public function testInsideContextCommand_ShouldBeAddDestroyerToParentContext()
	{
		$function = function(){};
		$context = Manager::context('', function() use($function) {
			Manager::afterEach($function);
		});

		$destroyer = $context->destroyers->get(0);
		$this->assertSame($function, $destroyer['callback']);
		$this->assertSame('each', $destroyer['type']);
	}

/**/

	public function assertDestroyerNotExistsInDescribe($name)
	{
		$describe = Manager::describe('', function(){});
		$this->assertFalse($describe->destroyers->isExists($name));
	}
}