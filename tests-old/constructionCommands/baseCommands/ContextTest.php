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

class ContextTest extends \spectrum\constructionCommands\commands\Test
{
	public function testShouldBeAllowToCallAtDeclaringState()
	{
		$context = manager::context('', function(){});
		$this->assertTrue($context instanceof \spectrum\core\SpecContainerContextInterface);
	}

	public function testShouldBeThrowExceptionIfCalledAtRunningState()
	{
		$this->assertThrowException('\spectrum\constructionCommands\Exception', '"context"', function()
		{
			$it = new \spectrum\core\SpecItemIt();
			$it->errorHandling->setCatchExceptions(false);
			$it->setTestCallback(function(){
				manager::context('', function(){});
			});
			$it->run();
		});
	}

	public function testShouldBeReturnNewSpecContainerContextInstance()
	{
		$describe = manager::context('', function(){});
		$this->assertTrue($describe instanceof \spectrum\core\SpecContainerContextInterface);
	}

//	public function testShouldBeThrowExceptionIfCalledAtRunningState()
//	{
//		$this->assertThrowException('\spectrum\constructionCommands\Exception', '"context"', function()
//		{
//			$it = manager::it('', function(){
//				manager::context('', function(){});
//			});
//
//			$it->run();
//		});
//	}
}