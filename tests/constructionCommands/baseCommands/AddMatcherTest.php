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

class AddMatcherTest extends \spectrum\constructionCommands\commands\Test
{
	protected function setUp()
	{
		parent::setUp();
		$this->assertMatcherNotExistsInDescribe('foo');
	}

	public function testShouldBeAllowToCallAtDeclaringState()
	{
		$describe = manager::describe('', function(){
			manager::addMatcher('foo', function(){});
		});

		$this->assertTrue($describe->matchers->isExists('foo'));
	}

	public function testShouldBeThrowExceptionIfCalledAtRunningState()
	{
		$this->assertThrowException('\spectrum\constructionCommands\Exception', '"addMatcher"', function()
		{
			$it = new \spectrum\core\SpecItemIt();
			$it->errorHandling->setCatchExceptions(false);
			$it->setTestCallback(function(){
				manager::addMatcher('foo', function(){});
			});
			$it->run();
		});
	}

	public function testShouldBeReturnAddedCallback()
	{
		$function = function(){};
		$describe = manager::describe('', function() use($function, &$return) {
			$return = manager::addMatcher('foo', $function);
		});

		$this->assertSame($function, $return);
	}

	public function testShouldNotBeCallCallbackDuringCall()
	{
		manager::addMatcher('foo', function() use(&$isCalled){
			$isCalled = true;
		});

		$this->assertNull($isCalled);
	}

	public function testNoParentCommand_ShouldBeAddMatcherToRootDescribe()
	{
		$function = function(){};
		manager::addMatcher('foo', $function);

		$this->assertSame($function, \spectrum\RootSpec::getOnceInstance()->matchers->get('foo'));
	}

	public function testInsideDescribeCommand_ShouldBeAddMatcherToParentDescribe()
	{
		$function = function(){};
		$describe = manager::describe('', function() use($function) {
			manager::addMatcher('foo', $function);
		});

		$this->assertSame($function, $describe->matchers->get('foo'));
	}

	public function testInsideContextCommand_ShouldBeAddMatcherToParentContext()
	{
		$function = function(){};
		$context = manager::context('', function() use($function) {
			manager::addMatcher('foo', $function);
		});

		$this->assertSame($function, $context->matchers->get('foo'));
	}

/**/

	public function assertMatcherNotExistsInDescribe($name)
	{
		$describe = manager::describe('', function(){});
		$this->assertFalse($describe->matchers->isExists($name));
	}
}