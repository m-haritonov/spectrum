<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core\verifications\verification\operators;
use spectrum\core\verifications\Verification;
use spectrum\core\verifications\CallDetails;

require_once __DIR__ . '/../../../../init.php';

class NotThrowsTest extends \spectrum\core\verifications\verification\Test
{
	public function testValue1IsNotCallable_ShouldBeThrowException()
	{
		$test = $this;
		$this->assertThrowException('\spectrum\core\verifications\Exception', '"!throws" operator in verification can accept only callable function as first operand', function() use($test){
			$it = $test->createSpecIt();
			$it->errorHandling->setCatchExceptions(false);
			$test->runInTestCallback($it, function($test, $it){
				new Verification(123, '!throws', '\Exception');
			});
		});
	}
	
/**/
	
	public function testValue2IsNotString_ShouldBeThrowException()
	{
		$test = $this;
		$this->assertThrowException('\spectrum\core\verifications\Exception', '"!throws" operator in verification can accept only string or array as second operand', function() use($test){
			$it = $test->createSpecIt();
			$it->errorHandling->setCatchExceptions(false);
			$test->runInTestCallback($it, function($test, $it){
				new Verification(function(){}, '!throws', 123);
			});
		});
	}
	
	public function testValue2IsStringWithNotSubclassOfExceptionClass_ShouldBeThrowException()
	{
		$test = $this;
		$this->assertThrowException('\spectrum\core\verifications\Exception', 'Excepted class in "!throws" verification should be subclass of "\Exception" (now class "\stdClass" not subclass of "\Exception")', function() use($test){
			$it = $test->createSpecIt();
			$it->errorHandling->setCatchExceptions(false);
			$test->runInTestCallback($it, function($test, $it){
				new Verification(function(){}, '!throws', '\stdClass');
			});
		});
	}
	
	public function testValue2IsStringWithExceptionClass_ShouldNotBeThrowException()
	{
		$it = $this->createSpecIt();
		$it->errorHandling->setCatchExceptions(false);
		$this->runInTestCallback($it, function($test, $it) use(&$isCalled){
			new Verification(function(){}, '!throws', '\Exception');
			$isCalled = true;
		});
		
		$this->assertTrue($isCalled);
	}

/**/
	
	public function testValue2IsStringWithSomeExceptionClass_Value1ThrowsExceptionOfValue2Class_ShouldBeReturnFalseAndSetThrownExceptionToValue1InCallDetailsAndNotChangeValue2()
	{
		$this->runInTestCallback($this->createSpecIt(), function($test, $it) use(&$verification){
			$verification = new Verification(function(){
				throw new \spectrum\core\verifications\Exception();
			}, '!throws', '\spectrum\core\verifications\Exception');
		});

		$this->assertFalse($verification->getCallDetails()->getResult());
		$this->assertTrue($verification->getCallDetails()->getValue1() instanceof \spectrum\core\verifications\Exception);
		$this->assertEquals('\spectrum\core\verifications\Exception', $verification->getCallDetails()->getValue2());
	}
	
	public function testValue2IsStringWithSomeExceptionClass_Value1ThrowsExceptionOfSubclassOfValue2Class_ShouldBeReturnFalseAndSetThrownExceptionToValue1InCallDetailsAndNotChangeValue2()
	{
		$this->runInTestCallback($this->createSpecIt(), function($test, $it) use(&$verification){
			$verification = new Verification(function(){
				throw new \spectrum\core\verifications\Exception();
			}, '!throws', '\spectrum\core\Exception');
		});
		
		$this->assertFalse($verification->getCallDetails()->getResult());
		$this->assertTrue($verification->getCallDetails()->getValue1() instanceof \spectrum\core\verifications\Exception);
		$this->assertEquals('\spectrum\core\Exception', $verification->getCallDetails()->getValue2());
	}
	
	public function testValue2IsStringWithRootExceptionClass_Value1ThrowsExceptionOfRootExceptionClass_ShouldBeReturnFalseAndSetThrownExceptionToValue1InCallDetailsAndNotChangeValue2()
	{
		$this->runInTestCallback($this->createSpecIt(), function($test, $it) use(&$verification){
			$verification = new Verification(function(){
				throw new \Exception();
			}, '!throws', '\Exception');
		});
		
		$this->assertFalse($verification->getCallDetails()->getResult());
		$this->assertTrue($verification->getCallDetails()->getValue1() instanceof \Exception);
		$this->assertEquals('\Exception', $verification->getCallDetails()->getValue2());
	}
	
	public function testValue2IsStringWithSomeExceptionClass_Value1NotThrowsAnyException_ShouldBeReturnTrueAndNotChangeValuesInCallDetails()
	{
		$this->runInTestCallback($this->createSpecIt(), function($test, $it) use(&$verification){
			$verification = new Verification(function(){}, '!throws', '\Exception');
		});
		
		$this->assertTrue($verification->getCallDetails()->getResult());
		$this->assertTrue($verification->getCallDetails()->getValue1() instanceof \Closure);
		$this->assertEquals('\Exception', $verification->getCallDetails()->getValue2());
	}
	
	public function testValue2IsStringWithSomeExceptionClass_Value1ThrowsExceptionOfParentClassOfValue2Class_ShouldBeReturnTrueAndSetThrownExceptionToValue1InCallDetailsAndNotChangeValue2()
	{
		$this->runInTestCallback($this->createSpecIt(), function($test, $it) use(&$verification){
			$verification = new Verification(function(){
				throw new \spectrum\core\Exception();
			}, '!throws', '\spectrum\core\verifications\Exception');
		});
		
		$this->assertTrue($verification->getCallDetails()->getResult());
		$this->assertTrue($verification->getCallDetails()->getValue1() instanceof \spectrum\core\Exception);
		$this->assertEquals('\spectrum\core\verifications\Exception', $verification->getCallDetails()->getValue2());
	}
	
	public function testValue2IsStringWithSomeExceptionClass_Value1ThrowsExceptionOfAncestorClassOfValue2Class_ShouldBeReturnTrueAndSetThrownExceptionToValue1InCallDetailsAndNotChangeValue2()
	{
		$this->runInTestCallback($this->createSpecIt(), function($test, $it) use(&$verification){
			$verification = new Verification(function(){
				throw new \Exception();
			}, '!throws', '\spectrum\core\verifications\Exception');
		});
		
		$this->assertTrue($verification->getCallDetails()->getResult());
		$this->assertTrue($verification->getCallDetails()->getValue1() instanceof \Exception);
		$this->assertEquals('\spectrum\core\verifications\Exception', $verification->getCallDetails()->getValue2());
	}
	
/**/

	public function testValue2IsEmptyArray_ShouldBeThrowException()
	{
		$test = $this;
		$this->assertThrowException('\spectrum\core\verifications\Exception', '"!throws" operator in verification can\'t accept empty array as second operand', function() use($test){
			$it = $test->createSpecIt();
			$it->errorHandling->setCatchExceptions(false);
			$test->runInTestCallback($it, function($test, $it){
				new Verification(function(){}, '!throws', array());
			});
		});
	}
	
	public function testValue2IsArrayWithNull_ShouldBeThrowException()
	{
		$test = $this;
		$this->assertThrowException('\spectrum\core\verifications\Exception', '"!throws" operator in verification can\'t accept array with all null elements as second operand', function() use($test){
			$it = $test->createSpecIt();
			$it->errorHandling->setCatchExceptions(false);
			$test->runInTestCallback($it, function($test, $it){
				new Verification(function(){}, '!throws', array(null));
			});
		});
	}
	
	public function testValue2IsArrayWithNullAndNull_ShouldBeThrowException()
	{
		$test = $this;
		$this->assertThrowException('\spectrum\core\verifications\Exception', '"!throws" operator in verification can\'t accept array with all null elements as second operand', function() use($test){
			$it = $test->createSpecIt();
			$it->errorHandling->setCatchExceptions(false);
			$test->runInTestCallback($it, function($test, $it){
				new Verification(function(){}, '!throws', array(null, null));
			});
		});
	}
	
	public function testValue2IsArrayWithNullAndNullAndNull_ShouldBeThrowException()
	{
		$test = $this;
		$this->assertThrowException('\spectrum\core\verifications\Exception', '"!throws" operator in verification can\'t accept array with all null elements as second operand', function() use($test){
			$it = $test->createSpecIt();
			$it->errorHandling->setCatchExceptions(false);
			$test->runInTestCallback($it, function($test, $it){
				new Verification(function(){}, '!throws', array(null, null, null));
			});
		});
	}

/**/

	public function dataProviderValue2IsArray()
	{
		return array(
			array(array('\Exception'), new \Exception(), false),
			array(array('\spectrum\core\Exception'), new \spectrum\core\verifications\Exception(), false),
			array(array('\spectrum\core\verifications\Exception'), new \spectrum\core\verifications\Exception(), false),
			array(array('\Exception'), null, true),
			array(array('\spectrum\core\verifications\Exception'), new \Exception(), true),
			array(array('\spectrum\core\verifications\Exception'), new \spectrum\core\Exception(), true),

			array(array('\Exception', 'aaa bbb'), new \Exception('aaa bbb'), false),
			array(array('\spectrum\core\Exception', 'aaa bbb'), new \spectrum\core\verifications\Exception('aaa bbb'), false),
			array(array('\spectrum\core\verifications\Exception', 'aaa bbb'), new \spectrum\core\verifications\Exception('aaa bbb'), false),
			array(array('\spectrum\core\verifications\Exception', 'aaa bbb'), new \spectrum\core\verifications\Exception('AaA BBB'), false),
			array(array('\spectrum\core\verifications\Exception', 'AaA BBB'), new \spectrum\core\verifications\Exception('aaa bbb'), false),
			array(array('\spectrum\core\verifications\Exception', 'bbb ccc'), new \spectrum\core\verifications\Exception('aaa bbb ccc ddd'), false),
			array(array('\spectrum\core\verifications\Exception', 'bbb ccc'), new \spectrum\core\verifications\Exception('bbb ccc ddd'), false),
			array(array('\spectrum\core\verifications\Exception', 'bbb ccc'), new \spectrum\core\verifications\Exception('aaa bbb ccc'), false),
			array(array('\Exception', 'aaa bbb'), null, true),
			array(array('\spectrum\core\verifications\Exception', 'aaa bbb'), new \Exception(), true),
			array(array('\spectrum\core\verifications\Exception', 'aaa bbb'), new \spectrum\core\verifications\Exception(), true),
			array(array('\spectrum\core\verifications\Exception', 'aaa bbb'), new \spectrum\core\verifications\Exception('ccc'), true),
			array(array('\spectrum\core\verifications\Exception', 'aaa bbb'), new \Exception('ccc'), true),
		
			array(array('\Exception', null), new \Exception(), false),
			array(array('\spectrum\core\Exception', null), new \spectrum\core\verifications\Exception(), false),
			array(array('\spectrum\core\verifications\Exception', null), new \spectrum\core\verifications\Exception(), false),
			array(array('\Exception', null), null, true),
			array(array('\spectrum\core\verifications\Exception', null), new \Exception(), true),
			array(array('\spectrum\core\verifications\Exception', null), new \spectrum\core\Exception(), true),
			
			array(array(null, 'aaa bbb'), new \Exception('aaa bbb'), false),
			array(array(null, 'aaa bbb'), new \spectrum\core\verifications\Exception('aaa bbb'), false),
			array(array(null, 'aaa bbb'), new \spectrum\core\verifications\Exception('aaa bbb'), false),
			array(array(null, 'aaa bbb'), new \spectrum\core\verifications\Exception('AaA BBB'), false),
			array(array(null, 'AaA BBB'), new \spectrum\core\verifications\Exception('aaa bbb'), false),
			array(array(null, 'bbb ccc'), new \spectrum\core\verifications\Exception('aaa bbb ccc ddd'), false),
			array(array(null, 'bbb ccc'), new \spectrum\core\verifications\Exception('bbb ccc ddd'), false),
			array(array(null, 'bbb ccc'), new \spectrum\core\verifications\Exception('aaa bbb ccc'), false),
			array(array(null, 'aaa bbb'), null, true),
			array(array(null, 'aaa bbb'), new \Exception(), true),
			array(array(null, 'aaa bbb'), new \spectrum\core\verifications\Exception(), true),
			array(array(null, 'aaa bbb'), new \spectrum\core\verifications\Exception('ccc'), true),
			array(array(null, 'aaa bbb'), new \Exception('ccc'), true),
			
			array(array('\Exception', 'aaa bbb', 123), new \Exception('aaa bbb', 123), false),
			array(array('\spectrum\core\Exception', 'aaa bbb', 123), new \spectrum\core\verifications\Exception('aaa bbb', 123), false),
			array(array('\spectrum\core\verifications\Exception', 'aaa bbb', 123), new \spectrum\core\verifications\Exception('aaa bbb', 123), false),
			array(array('\Exception', 'aaa bbb', 123), null, true),
			array(array('\Exception', 'aaa bbb', 123), new \Exception(), true),
			array(array('\Exception', 'aaa bbb', 123), new \Exception(null, 111), true),
			array(array('\Exception', 'aaa bbb', 123), new \Exception('aaa bbb', 111), true),
		
			array(array('\Exception', 'aaa bbb', null), new \Exception('aaa bbb'), false),
			array(array('\spectrum\core\Exception', 'aaa bbb', null), new \spectrum\core\verifications\Exception('aaa bbb'), false),
			array(array('\spectrum\core\verifications\Exception', 'aaa bbb', null), new \spectrum\core\verifications\Exception('aaa bbb'), false),
			array(array('\spectrum\core\verifications\Exception', 'aaa bbb', null), new \spectrum\core\verifications\Exception('AaA BBB'), false),
			array(array('\spectrum\core\verifications\Exception', 'AaA BBB', null), new \spectrum\core\verifications\Exception('aaa bbb'), false),
			array(array('\spectrum\core\verifications\Exception', 'bbb ccc', null), new \spectrum\core\verifications\Exception('aaa bbb ccc ddd'), false),
			array(array('\spectrum\core\verifications\Exception', 'bbb ccc', null), new \spectrum\core\verifications\Exception('bbb ccc ddd'), false),
			array(array('\spectrum\core\verifications\Exception', 'bbb ccc', null), new \spectrum\core\verifications\Exception('aaa bbb ccc'), false),
			array(array('\Exception', 'aaa bbb', null), null, true),
			array(array('\spectrum\core\verifications\Exception', 'aaa bbb', null), new \Exception(), true),
			array(array('\spectrum\core\verifications\Exception', 'aaa bbb', null), new \spectrum\core\verifications\Exception(), true),
			array(array('\spectrum\core\verifications\Exception', 'aaa bbb', null), new \spectrum\core\verifications\Exception('ccc'), true),
			array(array('\spectrum\core\verifications\Exception', 'aaa bbb', null), new \Exception('ccc'), true),
		
			array(array('\Exception', null, 123), new \Exception(null, 123), false),
			array(array('\Exception', null, 123), new \Exception('aaa bbb', 123), false),
			array(array('\spectrum\core\Exception', null, 123), new \spectrum\core\verifications\Exception('aaa bbb', 123), false),
			array(array('\spectrum\core\verifications\Exception', null, 123), new \spectrum\core\verifications\Exception('aaa bbb', 123), false),
			array(array('\Exception', null, 123), null, true),
			array(array('\Exception', null, 123), new \Exception(), true),
			array(array('\Exception', null, 123), new \Exception(null, 111), true),
			array(array('\Exception', null, 123), new \Exception('aaa bbb', 111), true),
		
			array(array('\Exception', null, null), new \Exception(), false),
			array(array('\spectrum\core\Exception', null, null), new \spectrum\core\verifications\Exception(), false),
			array(array('\spectrum\core\verifications\Exception', null, null), new \spectrum\core\verifications\Exception(), false),
			array(array('\Exception', null, null), null, true),
			array(array('\spectrum\core\verifications\Exception', null, null), new \Exception(), true),
			array(array('\spectrum\core\verifications\Exception', null, null), new \spectrum\core\Exception(), true),
		
			array(array(null, 'aaa bbb', 123), new \Exception('aaa bbb', 123), false),
			array(array(null, 'aaa bbb', 123), new \spectrum\core\verifications\Exception('aaa bbb', 123), false),
			array(array(null, 'aaa bbb', 123), null, true),
			array(array(null, 'aaa bbb', 123), new \Exception(), true),
			array(array(null, 'aaa bbb', 123), new \Exception(null, 111), true),
			array(array(null, 'aaa bbb', 123), new \Exception('aaa bbb', 111), true),
			array(array(null, 'ссс', 123), new \Exception('aaa bbb', 111), true),
			array(array(null, 'ссс', 123), new \Exception('aaa bbb', 123), true),
		
			array(array(null, 'aaa bbb', null), new \Exception('aaa bbb'), false),
			array(array(null, 'aaa bbb', null), new \spectrum\core\verifications\Exception('aaa bbb'), false),
			array(array(null, 'aaa bbb', null), new \spectrum\core\verifications\Exception('aaa bbb'), false),
			array(array(null, 'aaa bbb', null), new \spectrum\core\verifications\Exception('AaA BBB'), false),
			array(array(null, 'AaA BBB', null), new \spectrum\core\verifications\Exception('aaa bbb'), false),
			array(array(null, 'bbb ccc', null), new \spectrum\core\verifications\Exception('aaa bbb ccc ddd'), false),
			array(array(null, 'bbb ccc', null), new \spectrum\core\verifications\Exception('bbb ccc ddd'), false),
			array(array(null, 'bbb ccc', null), new \spectrum\core\verifications\Exception('aaa bbb ccc'), false),
			array(array(null, 'aaa bbb', null), null, true),
			array(array(null, 'aaa bbb', null), new \Exception(), true),
			array(array(null, 'aaa bbb', null), new \spectrum\core\verifications\Exception(), true),
			array(array(null, 'aaa bbb', null), new \spectrum\core\verifications\Exception('ccc'), true),
			array(array(null, 'aaa bbb', null), new \Exception('ccc'), true),

			array(array(null, null, 123), new \Exception(null, 123), false),
			array(array(null, null, 123), new \Exception('aaa bbb', 123), false),
			array(array(null, null, 123), new \spectrum\core\verifications\Exception('aaa bbb', 123), false),
			array(array(null, null, 123), null, true),
			array(array(null, null, 123), new \Exception(), true),
			array(array(null, null, 123), new \Exception(null, 111), true),
			array(array(null, null, 123), new \Exception('aaa bbb', 111), true),
		);
	}
	
	/**
	 * @dataProvider dataProviderValue2IsArray
	 */
	public function testValue2IsArray($value2, $throwsException, $expectedResult)
	{
		$this->runInTestCallback($this->createSpecIt(), function($test, $it) use(&$verification, $value2, $throwsException){
			$verification = new Verification(function() use($value2, $throwsException)
			{
				if ($throwsException)
					throw $throwsException;
				
			}, '!throws', $value2);
		});

		$this->assertSame($expectedResult, $verification->getCallDetails()->getResult());
		
		if ($throwsException)
			$this->assertSame($throwsException, $verification->getCallDetails()->getValue1()); // Sets thrown exception to value1 instead of default value (function)
		else
			$this->assertTrue($verification->getCallDetails()->getValue1() instanceof \Closure); // Not changes value1
		
		$this->assertSame($value2, $verification->getCallDetails()->getValue2());
	}
}