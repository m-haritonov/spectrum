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

class GetCurrentItemTest extends \spectrum\constructionCommands\commands\Test
{
	public function testShouldBeAllowToCallAtRunningState()
	{
		$it = manager::it('', function() use(&$return) {
			$return = manager::getCurrentItem('');
		});

		$it->run();
		$this->assertSame($it, $return);
	}

	public function testShouldBeThrowExceptionIfCalledAtDeclaringState()
	{
		$this->assertThrowException('\spectrum\constructionCommands\Exception', '"getCurrentItem"', function(){
			manager::describe('', function(){
				manager::getCurrentItem('');
			});
		});
	}
	
	public function testShouldBeReturnRunningSpecItemInstance()
	{
		$it = manager::it('', function() use(&$return) {
			$return = manager::getCurrentItem('');
		});

		$it->run();
		$this->assertSame($it, $return);
	}
}