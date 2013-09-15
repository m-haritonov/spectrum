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

class TheTest extends \spectrum\constructionCommands\commands\Test
{
	public function testShouldBeAllowToCallAtRunningState()
	{
		$it = manager::it('', function() use(&$assert) {
			$assert = manager::the('');
		});

		$it->run();
		$this->assertTrue($assert instanceof \spectrum\core\Assert);
	}

	public function testShouldBeThrowExceptionIfCalledAtDeclaringState()
	{
		$this->assertThrowException('\spectrum\constructionCommands\Exception', '"the" should be call only at running state', function(){
			manager::describe('', function(){
				manager::the('');
			});
		});
	}

	public function testShouldBeReturnAssertInstance()
	{
		$it = manager::it('', function() use(&$assert) {
			$assert = manager::the('');
		});

		$it->run();
		$this->assertTrue($assert instanceof \spectrum\core\Assert);
	}

	public function testShouldBeSetActualValueToAssertInstance()
	{
		$it = manager::it('', function() use(&$assert) {
			$assert = manager::the('foo');
		});

		$it->run();
		$this->assertEquals('foo', $assert->getActualValue());
	}
}