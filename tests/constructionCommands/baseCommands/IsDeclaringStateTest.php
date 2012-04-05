<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\baseCommands;
use spectrum\constructionCommands\Manager;

require_once dirname(__FILE__) . '/../../init.php';

class IsDeclaringStateTest extends \spectrum\constructionCommands\baseCommands\Test
{
	public function testNoParentCommand_ShouldBeReturnTrue()
	{
		$this->assertTrue(Manager::isDeclaringState());
	}

	public function testInsideDescribeCommand_ShouldBeReturnTrue()
	{
		Manager::describe('', function() use(&$result){
			$result = Manager::isDeclaringState();
		});

		$this->assertTrue($result);
	}

	public function testInsideContextCommand_ShouldBeReturnTrue()
	{
		Manager::context('', function() use(&$result){
			$result = Manager::isDeclaringState();
		});

		$this->assertTrue($result);
	}

/**/

	public function testInsideAddMatcherCommand_ShouldBeReturnFalse()
	{
		Manager::addMatcher('foo', function() use(&$result){
			$result = Manager::isDeclaringState();
		});

		$it = Manager::it('', function(){
			be('')->foo();
		});

		$it->run();

		$this->assertFalse($result);
	}

	public function testInsideBeforeEachCommand_ShouldBeReturnFalse()
	{
		Manager::beforeEach(function() use(&$result){
			$result = Manager::isDeclaringState();
		});

		$it = Manager::it('', function(){});
		$it->run();

		$this->assertFalse($result);
	}

	public function testInsideAfterEachCommand_ShouldBeReturnFalse()
	{
		Manager::afterEach(function() use(&$result){
			$result = Manager::isDeclaringState();
		});

		$it = Manager::it('', function(){});
		$it->run();

		$this->assertFalse($result);
	}

	public function testInsideItCommand_ShouldBeReturnFalse()
	{
		$it = Manager::it('', function() use(&$result){
			$result = Manager::isDeclaringState();
		});
		$it->run();

		$this->assertFalse($result);
	}

	public function testInsideItCommand_InsideDescribeCommand_ShouldBeReturnFalse()
	{
		$describe = Manager::describe('', function() use(&$result)
		{
			Manager::it('', function() use(&$result){
				$result = Manager::isDeclaringState();
			});
		});
		$describe->run();

		$this->assertFalse($result);
	}
}