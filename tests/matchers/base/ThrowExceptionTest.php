<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\matchers\base;
require_once dirname(__FILE__) . '/../../init.php';
require_once 'base/throwException.php';

class ThrowExceptionTest extends \spectrum\matchers\Test
{
	public function testShouldBeReturnTrueIfCallbackThrownExceptionOfSameClass()
	{
		$this->assertTrue(throwException(function(){ throw new \Exception(); }, '\Exception'));
	}

	public function testShouldBeReturnTrueIfCallbackThrownExceptionOfSubclassOfSameClass()
	{
		$this->assertTrue(throwException(function(){ throw new \spectrum\core\Exception(); }, '\Exception'));
	}

	public function testShouldBeReturnTrueIfExceptionMessageContainsString()
	{
		$this->assertTrue(throwException(function(){ throw new \Exception('Foo not bar'); }, null, 'bar'));
	}

	public function testShouldBeReturnTrueIfExceptionMessageContainsStringAtBegin()
	{
		$this->assertTrue(throwException(function(){ throw new \Exception('bar not foo'); }, null, 'bar'));
	}
	
	public function testShouldBeReturnTrueIfExceptionCodeEqualsExpectedCode()
	{
		$this->assertTrue(throwException(function(){ throw new \Exception('', 123); }, null, null, 123));
	}

	public function testShouldBeUseRootExceptionIfExpectedClassIsNull()
	{
		$this->assertTrue(throwException(function(){ throw new \Exception(); }, null));
	}

	public function testShouldBeIgnoreCaseInExpectedStringInMessage()
	{
		$this->assertTrue(throwException(function(){ throw new \Exception('Foo not BAR'); }, null, 'bar'));
	}

	public function testShouldNotBeCheckExpectedStringInMessageIfItIsNull()
	{
		$this->assertTrue(throwException(function(){ throw new \Exception('Foo not BAR'); }, null, null));
	}

	public function testShouldNotBeCheckExpectedCodeIfItIsNull()
	{
		$this->assertTrue(throwException(function(){ throw new \Exception('', 123); }, null, null, null));
	}
	
/**/
	
	public function testShouldBeThrowExceptionIfClassIsNotSubclassOfException()
	{
		$this->assertThrowException('\spectrum\matchers\Exception', 'Excepted class "\spectrum\core\asserts\Assert" should be subclass of "\Exception"', function(){
			throwException(function(){}, '\spectrum\core\asserts\Assert');
		});
	}

	public function testShouldBeThrowExceptionIfCallbackNotThrownException()
	{
		$this->assertThrowException('\spectrum\matchers\Exception', 'Excepted exception "\Exception" not thrown', function(){
			throwException(function(){}, '\Exception');
		});
	}

	public function testShouldBeThrowExceptionIfCallbackThrownExceptionOfSuperClassInsteadOfExpectedClass()
	{
		$this->assertThrowException('\spectrum\matchers\Exception', 'Excepted exception "\spectrum\matchers\testEnv\ExceptionFoo" not thrown', function(){
			throwException(function(){ throw new \Exception(); }, '\spectrum\matchers\testEnv\ExceptionFoo');
		});
	}

	public function testShouldBeThrowExceptionIfCallbackThrownExceptionOfAncestorSuperClassInsteadOfExpectedClass()
	{
		$this->assertThrowException('\spectrum\matchers\Exception', 'Excepted exception "\spectrum\matchers\testEnv\ExceptionFooFoo" not thrown', function(){
			throwException(function(){ throw new \Exception(); }, '\spectrum\matchers\testEnv\ExceptionFooFoo');
		});
	}

	public function testShouldBeThrowExceptionIfCallbackThrownExceptionOfSiblingClass()
	{
		$this->assertThrowException('\spectrum\matchers\Exception', 'Excepted exception "\spectrum\matchers\testEnv\ExceptionFoo" not thrown', function(){
			throwException(function(){ throw new \spectrum\matchers\testEnv\ExceptionBar(); }, '\spectrum\matchers\testEnv\ExceptionFoo');
		});
	}

	public function testShouldBeThrowExceptionIfExceptionMessageNotContainsExpectedString()
	{
		$this->assertThrowException('\spectrum\matchers\Exception', 'Actual message "Foo not foo" not contains expected string "bar"', function(){
			throwException(function(){ throw new \Exception('Foo not foo'); }, null, 'bar');
		});
	}

	public function testShouldBeThrowExceptionIfExceptionCodeNotEqualToExpectedCode()
	{
		$this->assertThrowException('\spectrum\matchers\Exception', 'Actual code "12345" not equal to expected code "123"', function(){
			throwException(function(){ throw new \Exception('', 12345); }, null, null, 123);
		});
	}
}