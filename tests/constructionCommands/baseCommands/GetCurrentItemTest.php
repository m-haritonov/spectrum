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

class GetCurrentItemTest extends \spectrum\constructionCommands\baseCommands\Test
{
	public function testShouldBeAllowToCallAtRunningState()
	{
		$it = Manager::it('', function() use(&$return) {
			$return = Manager::getCurrentItem('');
		});

		$it->run();
		$this->assertSame($it, $return);
	}

	public function testShouldBeThrowExceptionIfCalledAtDeclaringState()
	{
		$this->assertThrowException('\spectrum\constructionCommands\Exception', '"getCurrentItem"', function(){
			Manager::describe('', function(){
				Manager::getCurrentItem('');
			});
		});
	}
	
	public function testShouldBeReturnRunningSpecItemInstance()
	{
		$it = Manager::it('', function() use(&$return) {
			$return = Manager::getCurrentItem('');
		});

		$it->run();
		$this->assertSame($it, $return);
	}
}