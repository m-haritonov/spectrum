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

class VerifyTest extends \spectrum\constructionCommands\commands\Test
{
	public function testShouldBeAllowToCallAtRunningState()
	{
		$it = manager::it('', function() use(&$isCalled) {
			manager::verify(true, '==', true);
			$isCalled = true;
		});

		$it->run();
		$this->assertTrue($isCalled);
	}

	public function testShouldBeThrowExceptionIfCalledAtDeclaringState()
	{
		$this->assertThrowException('\spectrum\constructionCommands\Exception', '"verify" should be call only at running state', function(){
			manager::describe('', function(){
				manager::verify(true, '==', true);
			});
		});
	}

	public function testShouldBeReturnVerificationInstance()
	{
		$it = manager::it('', function() use(&$result) {
			$result = manager::verify(true, '==', true);
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
		$it = manager::it('', function() use(&$instance) {
			$instance = manager::verify('aaa');
		});
		
		$it->run();
		$this->assertEquals('aaa', $instance->getCallDetails()->getValue1());
		$this->assertSame(null, $instance->getCallDetails()->getOperator());
		$this->assertSame(null, $instance->getCallDetails()->getValue2());
	}
	
	public function testTwoArgumentPassed_ShouldBeThrowException()
	{
		$this->assertThrowException('\spectrum\core\verifications\Exception', 'Verification can accept only 1 or 3 arguments (now 2 arguments passed)', function(){
			$it = manager::it('', function() use(&$instance) {
				$instance = manager::verify('aaa', '==');
			});
			$it->errorHandling->setCatchExceptions(false);
			$it->run();
		});
	}
	
	public function testThreeArgumentPassed_ShouldBeForwardArgumentsToVerificationInstance()
	{
		$it = manager::it('', function() use(&$instance) {
			$instance = manager::verify('aaa', '==', 'bbb');
		});
		
		$it->run();
		$this->assertEquals('aaa', $instance->getCallDetails()->getValue1());
		$this->assertEquals('==', $instance->getCallDetails()->getOperator());
		$this->assertEquals('bbb', $instance->getCallDetails()->getValue2());
	}
}