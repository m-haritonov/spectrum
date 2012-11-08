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

class VerifyTest extends \spectrum\constructionCommands\baseCommands\Test
{
	public function testShouldBeAllowToCallAtRunningState()
	{
		$it = Manager::it('', function() use(&$isCalled) {
			Manager::verify(true, '==', true);
			$isCalled = true;
		});

		$it->run();
		$this->assertTrue($isCalled);
	}

	public function testShouldBeThrowExceptionIfCalledAtDeclaringState()
	{
		$this->assertThrowException('\spectrum\constructionCommands\Exception', '"verify" should be call only at running state', function(){
			Manager::describe('', function(){
				Manager::verify(true, '==', true);
			});
		});
	}

	public function testShouldBeReturnVerificationInstance()
	{
		$it = Manager::it('', function() use(&$result) {
			$result = Manager::verify(true, '==', true);
		});

		$it->run();
		$this->assertTrue($result instanceof \spectrum\core\verifications\Verification);
	}
	
//	public function testShouldBeCollectSourceCodeWhenCalledThroughVerifyFunction()
//	{
//
//	}

	public function testOneArgumentPassed_ShouldBeForwardArgumentsToVerificationInstance()
	{
		$it = Manager::it('', function() use(&$instance) {
			$instance = Manager::verify('aaa');
		});
		
		$it->run();
		$this->assertEquals('aaa', $instance->getCallDetails()->getValue1());
		$this->assertSame(null, $instance->getCallDetails()->getOperator());
		$this->assertSame(null, $instance->getCallDetails()->getValue2());
	}
	
	public function testTwoArgumentPassed_ShouldBeThrowException()
	{
		$this->assertThrowException('\spectrum\core\verifications\Exception', 'Verification can accept only 1 or 3 arguments (now 2 arguments passed)', function(){
			$it = Manager::it('', function() use(&$instance) {
				$instance = Manager::verify('aaa', '==');
			});
			$it->errorHandling->setCatchExceptions(false);
			$it->run();
		});
	}
	
	public function testThreeArgumentPassed_ShouldBeForwardArgumentsToVerificationInstance()
	{
		$it = Manager::it('', function() use(&$instance) {
			$instance = Manager::verify('aaa', '==', 'bbb');
		});
		
		$it->run();
		$this->assertEquals('aaa', $instance->getCallDetails()->getValue1());
		$this->assertEquals('==', $instance->getCallDetails()->getOperator());
		$this->assertEquals('bbb', $instance->getCallDetails()->getValue2());
	}
}