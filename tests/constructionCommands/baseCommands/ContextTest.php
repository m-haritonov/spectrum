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

class ContextTest extends \spectrum\constructionCommands\baseCommands\Test
{
	public function testShouldBeAllowToCallAtDeclaringState()
	{
		$context = Manager::context('', function(){});
		$this->assertTrue($context instanceof \spectrum\core\SpecContainerContextInterface);
	}

	public function testShouldBeThrowExceptionIfCalledAtRunningState()
	{
		$this->assertThrowException('\spectrum\constructionCommands\Exception', '"context"', function()
		{
			$it = new \spectrum\core\SpecItemIt();
			$it->errorHandling->setCatchExceptions(false);
			$it->setTestCallback(function(){
				Manager::context('', function(){});
			});
			$it->run();
		});
	}

	public function testShouldBeReturnNewSpecContainerContextInstance()
	{
		$describe = Manager::context('', function(){});
		$this->assertTrue($describe instanceof \spectrum\core\SpecContainerContextInterface);
	}

//	public function testShouldBeThrowExceptionIfCalledAtRunningState()
//	{
//		$this->assertThrowException('\spectrum\constructionCommands\Exception', '"context"', function()
//		{
//			$it = Manager::it('', function(){
//				Manager::context('', function(){});
//			});
//
//			$it->run();
//		});
//	}
}