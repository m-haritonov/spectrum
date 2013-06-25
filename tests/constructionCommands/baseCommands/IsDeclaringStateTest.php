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

class IsDeclaringStateTest extends \spectrum\constructionCommands\commands\Test
{
	public function testNoParentCommand_ShouldBeReturnTrue()
	{
		$this->assertTrue(manager::isDeclaringState());
	}

	public function testInsideDescribeCommand_ShouldBeReturnTrue()
	{
		manager::describe('', function() use(&$result){
			$result = manager::isDeclaringState();
		});

		$this->assertTrue($result);
	}

	public function testInsideContextCommand_ShouldBeReturnTrue()
	{
		manager::context('', function() use(&$result){
			$result = manager::isDeclaringState();
		});

		$this->assertTrue($result);
	}

/**/

	public function testInsideAddMatcherCommand_ShouldBeReturnFalse()
	{
		manager::addMatcher('foo', function() use(&$result){
			$result = manager::isDeclaringState();
		});

		$it = manager::it('', function(){
			the('')->foo();
		});

		$it->run();

		$this->assertFalse($result);
	}

	public function testInsideBeforeEachCommand_ShouldBeReturnFalse()
	{
		manager::beforeEach(function() use(&$result){
			$result = manager::isDeclaringState();
		});

		$it = manager::it('', function(){});
		$it->run();

		$this->assertFalse($result);
	}

	public function testInsideAfterEachCommand_ShouldBeReturnFalse()
	{
		manager::afterEach(function() use(&$result){
			$result = manager::isDeclaringState();
		});

		$it = manager::it('', function(){});
		$it->run();

		$this->assertFalse($result);
	}

	public function testInsideItCommand_ShouldBeReturnFalse()
	{
		$it = manager::it('', function() use(&$result){
			$result = manager::isDeclaringState();
		});
		$it->run();

		$this->assertFalse($result);
	}

	public function testInsideItCommand_InsideDescribeCommand_ShouldBeReturnFalse()
	{
		$describe = manager::describe('', function() use(&$result)
		{
			manager::it('', function() use(&$result){
				$result = manager::isDeclaringState();
			});
		});
		$describe->run();

		$this->assertFalse($result);
	}
}