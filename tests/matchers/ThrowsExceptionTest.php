<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\matchers;

require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../../spectrum/matchers/throwsException.php';

class ThrowsExceptionTest extends \spectrum\tests\Test
{
	public function providerReturnsTrue()
	{
		$childException = $this->createClass('class ... extends \Exception {}');
		$descendantException = $this->createClass('class ... extends ' . $childException . ' {}');
		
		return array(
			array(array(function(){ throw new \Exception(); })),
			array(array(function(){ throw new \Exception(); }, null)),
			array(array(function(){ throw new \Exception(); }, null, null)),
			array(array(function(){ throw new \Exception(); }, null, null, null)),
			
			array(array(function(){ throw new \Exception(); }, '\Exception', null, null)),
			array(array(function(){ throw new \Exception(); }, '\exception', null, null)),
			array(array(function(){ throw new \Exception(); }, 'Exception', null, null)),
			array(array(function(){ throw new \Exception(); }, 'exception', null, null)),
			
			array(array(function(){ throw new \exception(); }, '\Exception', null, null)),
			array(array(function(){ throw new \exception(); }, '\exception', null, null)),
			array(array(function(){ throw new \exception(); }, 'Exception', null, null)),
			array(array(function(){ throw new \exception(); }, 'exception', null, null)),
			
			array(array(function() use($childException){ throw new $childException(); }, '\Exception', null, null)),
			array(array(function() use($childException){ throw new $childException(); }, $childException, null, null)),
			array(array(function() use($descendantException){ throw new $descendantException(); }, '\Exception', null, null)),
			array(array(function() use($descendantException){ throw new $descendantException(); }, $childException, null, null)),
			
			array(array(function(){ throw new \Exception('AAA BBB CCC'); }, null, 'AAA BBB CCC', null)),
			array(array(function(){ throw new \Exception('AAA BBB CCC'); }, null, 'AAA bbb CCC', null)),
			array(array(function(){ throw new \Exception('AAA bbb CCC'); }, null, 'AAA BBB CCC', null)),
			array(array(function(){ throw new \Exception('AAA BBB CCC'); }, null, 'AAA', null)),
			array(array(function(){ throw new \Exception('AAA BBB CCC'); }, null, 'BBB', null)),
			array(array(function(){ throw new \Exception('AAA BBB CCC'); }, null, 'CCC', null)),
			array(array(function(){ throw new \Exception(); }, null, '')),
			array(array(function(){ throw new \Exception(''); }, null, '')),
			
			array(array(function(){ throw new \Exception(); }, null, null, 0)),
			array(array(function(){ throw new \Exception('', 10); }, null, null, 10)),
			array(array(function(){ throw new \Exception('', 20); }, null, null, 20)),
			
			array(array(function(){ throw new \Exception('aaa bbb', 20); }, '\Exception', 'bbb', 20)),
			array(array(function(){ throw new \Exception('aaa bbb', 20); }, '\Exception', 'bbb')),
			array(array(function(){ throw new \Exception('aaa bbb', 20); }, '\Exception')),
			array(array(function(){ throw new \Exception('aaa bbb', 20); })),
		);
	}
	
	/**
	 * @dataProvider providerReturnsTrue
	 */
	public function testExceptionValuesIsCorrespondToArguments_ReturnsTrue($arguments)
	{
		$this->assertSame(true, call_user_func_array('\spectrum\matchers\throwsException', $arguments));
	}
	
	public function providerReturnsFalse()
	{
		$childException = $this->createClass('class ... extends \Exception {}');
		$descendantException = $this->createClass('class ... extends ' . $childException . ' {}');
		
		return array(
			array(array(function(){})),
			array(array(function(){}, null)),
			array(array(function(){}, null, null)),
			array(array(function(){}, null, null, null)),
			
			array(array(function(){ throw new \Exception(); }, $childException, null, null)),
			array(array(function(){ throw new \Exception(); }, $descendantException, null, null)),
			array(array(function(){ throw new \Exception('aaa'); }, null, 'bbb', null)),
			array(array(function(){ throw new \Exception(10); }, null, null, 10)),
			array(array(function(){ throw new \Exception('', 10); }, null, null, 0)),
			
			array(array(function(){ throw new \Exception('aaa bbb', 20); }, $childException, 'bbb', 20)),
			array(array(function(){ throw new \Exception('aaa bbb', 20); }, '\Exception', 'ccc', 20)),
			array(array(function(){ throw new \Exception('aaa bbb', 20); }, '\Exception', 'bbb', 30)),
			
			array(array(function(){ throw new \Exception('aaa bbb'); }, null, null, 20)),
			array(array(function(){ throw new \Exception(); }, '\Exception', 'ccc')),

			array(array(function(){ throw new \Exception(' '); }, null, '')),
			array(array(function(){ throw new \Exception(''); }, null, ' ')),
			array(array(function(){ throw new \Exception('aaa'); }, null, '')),
		);
	}
	
	/**
	 * @dataProvider providerReturnsFalse
	 */
	public function testExceptionValuesIsNotCorrespondToArguments_ReturnsFalse($arguments)
	{
		$this->assertSame(false, call_user_func_array('\spectrum\matchers\throwsException', $arguments));
	}
	
	public function testExceptionIsNotThrown_ReturnsFalse()
	{
		$this->assertSame(false, \spectrum\matchers\throwsException(function(){}));
	}
	
	public function testFunctionWithTestCodeIsNotCallable_ThrowsException()
	{
		$this->assertThrowsException('\spectrum\core\Exception', 'Function with test code is not callable', function(){
			\spectrum\matchers\throwsException('');
		});
	}
	
	public function testExceptedClassIsNotSubclassOfExceptionClass_ThrowsExceptionAndDoesNotCallFunctionWithTestCode()
	{
		$isCalled = false;
		$this->assertThrowsException('\spectrum\core\Exception', 'Excepted class should be subclass of "\Exception" class (now "\stdClass" is not subclass of "\Exception" class)', function() use(&$isCalled){
			\spectrum\matchers\throwsException(function() use(&$isCalled){ $isCalled = true; }, '\stdClass');
		});
		
		$this->assertSame(false, $isCalled);
	}
	
	public function providerNotStrings()
	{
		return array(
			array(123),
			array(123.4),
			array(true),
			array(false),
			array(array()),
			array(new \stdClass()),
		);
	}
	
	/**
	 * @dataProvider providerNotStrings
	 */
	public function testExceptedClassIsNotString_ThrowsExceptionAndDoesNotCallFunctionWithTestCode($value)
	{
		$isCalled = false;
		$this->assertThrowsException('\spectrum\core\Exception', 'Excepted class should be not empty string', function() use(&$isCalled, $value){
			\spectrum\matchers\throwsException(function() use(&$isCalled){ $isCalled = true; }, $value);
		});
		
		$this->assertSame(false, $isCalled);
	}
	
	public function testExceptedClassIsEmptyString_ThrowsExceptionAndDoesNotCallFunctionWithTestCode()
	{
		$isCalled = false;
		$this->assertThrowsException('\spectrum\core\Exception', 'Excepted class should be not empty string', function() use(&$isCalled){
			\spectrum\matchers\throwsException(function() use(&$isCalled){ $isCalled = true; }, '');
		});
		
		$this->assertSame(false, $isCalled);
	}
	
	/**
	 * @dataProvider providerNotStrings
	 */
	public function testExpectedStringInMessageIsNotString_ThrowsExceptionAndDoesNotCallFunctionWithTestCode($value)
	{
		$isCalled = false;
		$this->assertThrowsException('\spectrum\core\Exception', 'Excepted string in message should be a string', function() use(&$isCalled, $value){
			\spectrum\matchers\throwsException(function() use(&$isCalled){ $isCalled = true; }, null, $value);
		});
		
		$this->assertSame(false, $isCalled);
	}
	
	public function providerNotInteger()
	{
		return array(
			array(''),
			array('aaa'),
			array(true),
			array(false),
			array(array()),
			array(new \stdClass()),
		);
	}
	
	/**
	 * @dataProvider providerNotInteger
	 */
	public function testExceptedCodeIsNotInteger_ThrowsExceptionAndDoesNotCallFunctionWithTestCode($value)
	{
		$isCalled = false;
		$this->assertThrowsException('\spectrum\core\Exception', 'Excepted code should be a integer', function() use(&$isCalled, $value){
			\spectrum\matchers\throwsException(function() use(&$isCalled){ $isCalled = true; }, null, null, $value);
		});
		
		$this->assertSame(false, $isCalled);
	}
}