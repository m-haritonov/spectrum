<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\verifications\verification;
use spectrum\core\verifications\Verification;
use spectrum\core\verifications\CallDetails;

require_once __DIR__ . '/../../../init.php';

class ConstructorTest extends Test
{
	public function testConstructor_OneArgumentPassed_ShouldBeUseEqualOperatorAndTrueAsValue2InResultEvaluation()
	{
		$this->runInTestCallback($this->createSpecIt(), function($test, $it) use(&$verify)
		{
			$verify = new Verification(1);
		});
		
		$this->assertTrue($verify->getCallDetails()->getResult());
	}
	
	public function testConstructor_OneArgumentPassed_ShouldBeSetNullAsOperatorAndValue2ToCallDetails()
	{
		$this->runInTestCallback($this->createSpecIt(), function($test, $it) use(&$verify)
		{
			$verify = new Verification(1);
		});
		
		$this->assertSame(null, $verify->getCallDetails()->getOperator());
		$this->assertSame(null, $verify->getCallDetails()->getValue2());
	}
	
	public function testConstructor_OneArgumentPassed_ShouldBeProvideCorrectCallDetails()
	{
		$this->runInTestCallback($this->createSpecIt(), function($test, $it) use(&$verify)
		{
			$verify = new Verification('aaa');
		});
		
		$this->assertSame('aaa', $verify->getCallDetails()->getValue1());
		$this->assertSame(null, $verify->getCallDetails()->getOperator());
		$this->assertSame(null, $verify->getCallDetails()->getValue2());
		$this->assertSame(true, $verify->getCallDetails()->getResult());
	}
	
/**/
	
	public function testConstructor_TwoArgumentPassed_ShouldBeThrowException()
	{
		$test = $this;
		$this->assertThrowException('\spectrum\core\verifications\Exception', 'Verification can accept only 1 or 3 arguments (now 2 arguments passed)', function() use($test){
			$it = $test->createSpecIt();
			$it->errorHandling->setCatchExceptions(false);
			$test->runInTestCallback($it, function($test, $it){
				new Verification('aaa', '!=');
			});
		});
	}
	
/**/
	
	public function testConstructor_ThreeArgumentPassed_ShouldBeUsePassedOperatorAnValue2InResultEvaluation()
	{
		$this->runInTestCallback($this->createSpecIt(), function($test, $it) use(&$verify)
		{
			$verify = new Verification(11, '===', '11');
		});
		
		$this->assertFalse($verify->getCallDetails()->getResult());
	}
	
	public function testConstructor_ThreeArgumentPassed_ShouldBeProvideCorrectCallDetails()
	{
		$this->runInTestCallback($this->createSpecIt(), function($test, $it) use(&$verify)
		{
			$verify = new Verification('aaa', '!=', 'bbb');
		});
		
		$this->assertSame('aaa', $verify->getCallDetails()->getValue1());
		$this->assertSame('!=', $verify->getCallDetails()->getOperator());
		$this->assertSame('bbb', $verify->getCallDetails()->getValue2());
		$this->assertSame(true, $verify->getCallDetails()->getResult());
	}
	
/**/
	
	public function testConstructor_ShouldBeThrowExceptionIfPassedForbiddenOperator()
	{
		$test = $this;
		$this->assertThrowException('\spectrum\core\verifications\Exception', 'Operator "=====" forbidden in verification', function() use($test){
			$it = $test->createSpecIt();
			$it->errorHandling->setCatchExceptions(false);
			$test->runInTestCallback($it, function($test, $it){
				new Verification('aaa', '=====', 'bbb');
			});
		});
	}
	
	public function testConstructor_ShouldBeThrowExceptionIfPassedAssignmentOperator()
	{
		$test = $this;
		$this->assertThrowException('\spectrum\core\verifications\Exception', 'Operator "=" forbidden in verification', function() use($test){
			$it = $test->createSpecIt();
			$it->errorHandling->setCatchExceptions(false);
			$test->runInTestCallback($it, function($test, $it){
				new Verification('aaa', '=', 'bbb');
			});
		});
	}
	
	public function testConstructor_ShouldBeTrimSpacesFromOperator()
	{
		$this->runInTestCallback($this->createSpecIt(), function($test, $it) use(&$verify)
		{
			$verify = new Verification('aaa', '   ==  ', 'aaa');
		});
		
		$this->assertSame(true, $verify->getCallDetails()->getResult());
	}
}