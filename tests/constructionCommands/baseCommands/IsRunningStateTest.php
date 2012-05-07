<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\baseCommands;
use spectrum\constructionCommands\Manager;

require_once __DIR__ . '/../../init.php';

class IsRunningStateTest extends \spectrum\constructionCommands\baseCommands\Test
{
	public function testNoParentCommand_ShouldBeReturnFalse()
	{
		$this->assertFalse(Manager::isRunningState());
	}

	public function testInsideDescribeCommand_ShouldBeReturnFalse()
	{
		Manager::describe('', function() use(&$result){
			$result = Manager::isRunningState();
		});

		$this->assertFalse($result);
	}

	public function testInsideContextCommand_ShouldBeReturnFalse()
	{
		Manager::context('', function() use(&$result){
			$result = Manager::isRunningState();
		});

		$this->assertFalse($result);
	}

/**/

	public function testInsideAddMatcherCommand_ShouldBeReturnTrue()
	{
		Manager::addMatcher('foo', function() use(&$result){
			$result = Manager::isRunningState();
		});

		$it = Manager::it('', function(){
			the('')->foo();
		});

		$it->run();

		$this->assertTrue($result);
	}

	public function testInsideBeforeEachCommand_ShouldBeReturnTrue()
	{
		Manager::beforeEach(function() use(&$result){
			$result = Manager::isRunningState();
		});

		$it = Manager::it('', function(){});
		$it->run();

		$this->assertTrue($result);
	}

	public function testInsideAfterEachCommand_ShouldBeReturnTrue()
	{
		Manager::afterEach(function() use(&$result){
			$result = Manager::isRunningState();
		});

		$it = Manager::it('', function(){});
		$it->run();

		$this->assertTrue($result);
	}

	public function testInsideItCommand_ShouldBeReturnTrue()
	{
		$it = Manager::it('', function() use(&$result){
			$result = Manager::isRunningState();
		});
		$it->run();

		$this->assertTrue($result);
	}

	public function testInsideItCommand_InsideDescribeCommand_ShouldBeReturnTrue()
	{
		$describe = Manager::describe('', function() use(&$result)
		{
			Manager::it('', function() use(&$result){
				$result = Manager::isRunningState();
			});
		});
		$describe->run();

		$this->assertTrue($result);
	}
}