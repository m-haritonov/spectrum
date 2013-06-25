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

class IsRunningStateTest extends \spectrum\constructionCommands\commands\Test
{
	public function testNoParentCommand_ShouldBeReturnFalse()
	{
		$this->assertFalse(manager::isRunningState());
	}

	public function testInsideDescribeCommand_ShouldBeReturnFalse()
	{
		manager::describe('', function() use(&$result){
			$result = manager::isRunningState();
		});

		$this->assertFalse($result);
	}

	public function testInsideContextCommand_ShouldBeReturnFalse()
	{
		manager::context('', function() use(&$result){
			$result = manager::isRunningState();
		});

		$this->assertFalse($result);
	}

/**/

	public function testInsideAddMatcherCommand_ShouldBeReturnTrue()
	{
		manager::addMatcher('foo', function() use(&$result){
			$result = manager::isRunningState();
		});

		$it = manager::it('', function(){
			the('')->foo();
		});

		$it->run();

		$this->assertTrue($result);
	}

	public function testInsideBeforeEachCommand_ShouldBeReturnTrue()
	{
		manager::beforeEach(function() use(&$result){
			$result = manager::isRunningState();
		});

		$it = manager::it('', function(){});
		$it->run();

		$this->assertTrue($result);
	}

	public function testInsideAfterEachCommand_ShouldBeReturnTrue()
	{
		manager::afterEach(function() use(&$result){
			$result = manager::isRunningState();
		});

		$it = manager::it('', function(){});
		$it->run();

		$this->assertTrue($result);
	}

	public function testInsideItCommand_ShouldBeReturnTrue()
	{
		$it = manager::it('', function() use(&$result){
			$result = manager::isRunningState();
		});
		$it->run();

		$this->assertTrue($result);
	}

	public function testInsideItCommand_InsideDescribeCommand_ShouldBeReturnTrue()
	{
		$describe = manager::describe('', function() use(&$result)
		{
			manager::it('', function() use(&$result){
				$result = manager::isRunningState();
			});
		});
		$describe->run();

		$this->assertTrue($result);
	}
}