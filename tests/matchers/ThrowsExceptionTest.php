<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\matchers;

require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../../spectrum/matchers/throwsException.php';

class ThrowsExceptionTest extends \spectrum\tests\Test
{
	public function providerMatcherCall()
	{
		$level2Exception = $this->createClass('class ... extends \Exception {}');
		$level3Exception = $this->createClass('class ... extends ' . $level2Exception . ' {}');
		
		return array(
			array(true, array(function(){ throw new \Exception(); }, null, null, null)),
			array(true, array(function(){ throw new \Exception(); }, '\Exception', null, null)),
			array(true, array(function(){ throw new \Exception(); }, '\exception', null, null)),
			array(true, array(function(){ throw new \Exception(); }, 'Exception', null, null)),
			array(true, array(function(){ throw new \Exception(); }, 'exception', null, null)),
			array(false, array(function(){ throw new \Exception(); }, $level2Exception, null, null)),
			array(false, array(function(){ throw new \Exception(); }, $level3Exception, null, null)),
			array(true, array(function(){ throw new \Exception(); }, null, '')),
			array(false, array(function(){ throw new \Exception(); }, null, ' ')),
			array(false, array(function(){ throw new \Exception(); }, null, 'AAA BBB CCC', null)),
			array(true, array(function(){ throw new \Exception(); }, null, null, 0)),
			array(false, array(function(){ throw new \Exception(); }, null, null, 20)),
			array(true, array(function(){ throw new \Exception(); }, '\Exception', '', 0)),
			array(false, array(function(){ throw new \Exception(); }, '\Exception', '', 20)),
			array(false, array(function(){ throw new \Exception(); }, '\Exception', 'AAA BBB CCC', 0)),
			array(false, array(function(){ throw new \Exception(); }, '\Exception', 'AAA BBB CCC', 20)),

			array(true, array(function(){ throw new \Exception(''); }, '\Exception', '', 0)),
			array(false, array(function(){ throw new \Exception(''); }, '\Exception', '', 20)),
			array(false, array(function(){ throw new \Exception(''); }, '\Exception', 'AAA BBB CCC', 0)),
			array(false, array(function(){ throw new \Exception(''); }, '\Exception', 'AAA BBB CCC', 20)),

			array(true, array(function(){ throw new \Exception('', 0); }, '\Exception', '', 0)),
			array(false, array(function(){ throw new \Exception('', 0); }, '\Exception', '', 20)),
			array(false, array(function(){ throw new \Exception('', 0); }, '\Exception', 'AAA BBB CCC', 0)),
			array(false, array(function(){ throw new \Exception('', 0); }, '\Exception', 'AAA BBB CCC', 20)),
			
			array(true, array(function(){ throw new \Exception('', 20); }, null, null, null)),
			array(true, array(function(){ throw new \Exception('', 20); }, '\Exception', null, null)),
			array(true, array(function(){ throw new \Exception('', 20); }, '\exception', null, null)),
			array(true, array(function(){ throw new \Exception('', 20); }, 'Exception', null, null)),
			array(true, array(function(){ throw new \Exception('', 20); }, 'exception', null, null)),
			array(false, array(function(){ throw new \Exception('', 20); }, $level2Exception, null, null)),
			array(false, array(function(){ throw new \Exception('', 20); }, $level3Exception, null, null)),
			array(true, array(function(){ throw new \Exception('', 20); }, null, '')),
			array(false, array(function(){ throw new \Exception('', 20); }, null, ' ')),
			array(false, array(function(){ throw new \Exception('', 20); }, null, 'AAA BBB CCC', null)),
			array(false, array(function(){ throw new \Exception('', 20); }, null, null, 0)),
			array(true, array(function(){ throw new \Exception('', 20); }, null, null, 20)),
			array(false, array(function(){ throw new \Exception('', 20); }, '\Exception', '', 0)),
			array(true, array(function(){ throw new \Exception('', 20); }, '\Exception', '', 20)),
			array(false, array(function(){ throw new \Exception('', 20); }, '\Exception', 'AAA BBB CCC', 0)),
			array(false, array(function(){ throw new \Exception('', 20); }, '\Exception', 'AAA BBB CCC', 20)),

			array(true, array(function(){ throw new \Exception(' '); }, null, null, null)),
			array(true, array(function(){ throw new \Exception(' '); }, '\Exception', null, null)),
			array(true, array(function(){ throw new \Exception(' '); }, '\exception', null, null)),
			array(true, array(function(){ throw new \Exception(' '); }, 'Exception', null, null)),
			array(true, array(function(){ throw new \Exception(' '); }, 'exception', null, null)),
			array(false, array(function(){ throw new \Exception(' '); }, $level2Exception, null, null)),
			array(false, array(function(){ throw new \Exception(' '); }, $level3Exception, null, null)),
			array(false, array(function(){ throw new \Exception(' '); }, null, '')),
			array(true, array(function(){ throw new \Exception(' '); }, null, ' ')),
			array(false, array(function(){ throw new \Exception(' '); }, null, 'AAA BBB CCC', null)),
			array(true, array(function(){ throw new \Exception(' '); }, null, null, 0)),
			array(false, array(function(){ throw new \Exception(' '); }, null, null, 20)),
			array(false, array(function(){ throw new \Exception(' '); }, '\Exception', '', 0)),
			array(false, array(function(){ throw new \Exception(' '); }, '\Exception', '', 20)),
			array(false, array(function(){ throw new \Exception(' '); }, '\Exception', 'AAA BBB CCC', 0)),
			array(false, array(function(){ throw new \Exception(' '); }, '\Exception', 'AAA BBB CCC', 20)),

			array(true, array(function(){ throw new \Exception(20); }, null, null, null)),
			array(true, array(function(){ throw new \Exception(20); }, '\Exception', null, null)),
			array(true, array(function(){ throw new \Exception(20); }, '\exception', null, null)),
			array(true, array(function(){ throw new \Exception(20); }, 'Exception', null, null)),
			array(true, array(function(){ throw new \Exception(20); }, 'exception', null, null)),
			array(false, array(function(){ throw new \Exception(20); }, $level2Exception, null, null)),
			array(false, array(function(){ throw new \Exception(20); }, $level3Exception, null, null)),
			array(false, array(function(){ throw new \Exception(20); }, null, '')),
			array(false, array(function(){ throw new \Exception(20); }, null, ' ')),
			array(false, array(function(){ throw new \Exception(20); }, null, 'AAA BBB CCC', null)),
			array(true, array(function(){ throw new \Exception(20); }, null, null, 0)),
			array(false, array(function(){ throw new \Exception(20); }, null, null, 20)),
			array(false, array(function(){ throw new \Exception(20); }, '\Exception', '', 0)),
			array(false, array(function(){ throw new \Exception(20); }, '\Exception', '', 20)),
			array(false, array(function(){ throw new \Exception(20); }, '\Exception', 'AAA BBB CCC', 0)),
			array(false, array(function(){ throw new \Exception(20); }, '\Exception', 'AAA BBB CCC', 20)),
			
			array(true, array(function(){ throw new \Exception('AAA BBB CCC'); }, null, null, null)),
			array(true, array(function(){ throw new \Exception('AAA BBB CCC'); }, '\Exception', null, null)),
			array(true, array(function(){ throw new \Exception('AAA BBB CCC'); }, '\exception', null, null)),
			array(true, array(function(){ throw new \Exception('AAA BBB CCC'); }, 'Exception', null, null)),
			array(true, array(function(){ throw new \Exception('AAA BBB CCC'); }, 'exception', null, null)),
			array(false, array(function(){ throw new \Exception('AAA BBB CCC'); }, $level2Exception, null, null)),
			array(false, array(function(){ throw new \Exception('AAA BBB CCC'); }, $level3Exception, null, null)),
			array(false, array(function(){ throw new \Exception('AAA BBB CCC'); }, null, '')),
			array(true, array(function(){ throw new \Exception('AAA BBB CCC'); }, null, ' ')),
			array(true, array(function(){ throw new \Exception('AAA BBB CCC'); }, null, 'AAA BBB CCC', null)),
			array(false, array(function(){ throw new \Exception('AAA BBB CCC'); }, null, 'AAA bbb CCC', null)),
			array(true, array(function(){ throw new \Exception('AAA BBB CCC'); }, null, 'AAA', null)),
			array(true, array(function(){ throw new \Exception('AAA BBB CCC'); }, null, 'BBB', null)),
			array(true, array(function(){ throw new \Exception('AAA BBB CCC'); }, null, 'CCC', null)),
			array(false, array(function(){ throw new \Exception('AAA BBB CCC'); }, null, 'aaa', null)),
			array(false, array(function(){ throw new \Exception('AAA BBB CCC'); }, null, 'bbb', null)),
			array(false, array(function(){ throw new \Exception('AAA BBB CCC'); }, null, 'ccc', null)),
			array(false, array(function(){ throw new \Exception('AAA BBB CCC'); }, null, 'AAA ZZZ CCC', null)),
			array(false, array(function(){ throw new \Exception('AAA BBB CCC'); }, null, 'ZZZ AAA BBB CCC ZZZ', null)),
			array(false, array(function(){ throw new \Exception('AAA BBB CCC'); }, null, 'zzz', null)),
			array(true, array(function(){ throw new \Exception('AAA BBB CCC'); }, null, null, 0)),
			array(false, array(function(){ throw new \Exception('AAA BBB CCC'); }, null, null, 20)),
			array(false, array(function(){ throw new \Exception('AAA BBB CCC'); }, '\Exception', '', 0)),
			array(false, array(function(){ throw new \Exception('AAA BBB CCC'); }, '\Exception', '', 20)),
			array(true, array(function(){ throw new \Exception('AAA BBB CCC'); }, '\Exception', 'AAA BBB CCC', 0)),
			array(false, array(function(){ throw new \Exception('AAA BBB CCC'); }, '\Exception', 'AAA BBB CCC', 20)),

			array(false, array(function(){ throw new \Exception('AAA BBB CCC', 0); }, '\Exception', '', 0)),
			array(false, array(function(){ throw new \Exception('AAA BBB CCC', 0); }, '\Exception', '', 20)),
			array(true, array(function(){ throw new \Exception('AAA BBB CCC', 0); }, '\Exception', 'AAA BBB CCC', 0)),
			array(false, array(function(){ throw new \Exception('AAA BBB CCC', 0); }, '\Exception', 'AAA BBB CCC', 20)),
			
			array(true, array(function(){ throw new \Exception('AAA BBB CCC', 20); }, null, null, null)),
			array(true, array(function(){ throw new \Exception('AAA BBB CCC', 20); }, '\Exception', null, null)),
			array(true, array(function(){ throw new \Exception('AAA BBB CCC', 20); }, '\exception', null, null)),
			array(true, array(function(){ throw new \Exception('AAA BBB CCC', 20); }, 'Exception', null, null)),
			array(true, array(function(){ throw new \Exception('AAA BBB CCC', 20); }, 'exception', null, null)),
			array(false, array(function(){ throw new \Exception('AAA BBB CCC', 20); }, $level2Exception, null, null)),
			array(false, array(function(){ throw new \Exception('AAA BBB CCC', 20); }, $level3Exception, null, null)),
			array(false, array(function(){ throw new \Exception('AAA BBB CCC', 20); }, null, '')),
			array(true, array(function(){ throw new \Exception('AAA BBB CCC', 20); }, null, ' ')),
			array(true, array(function(){ throw new \Exception('AAA BBB CCC', 20); }, null, 'AAA BBB CCC', null)),
			array(false, array(function(){ throw new \Exception('AAA BBB CCC', 20); }, null, 'AAA bbb CCC', null)),
			array(true, array(function(){ throw new \Exception('AAA BBB CCC', 20); }, null, 'AAA', null)),
			array(true, array(function(){ throw new \Exception('AAA BBB CCC', 20); }, null, 'BBB', null)),
			array(true, array(function(){ throw new \Exception('AAA BBB CCC', 20); }, null, 'CCC', null)),
			array(false, array(function(){ throw new \Exception('AAA BBB CCC', 20); }, null, 'aaa', null)),
			array(false, array(function(){ throw new \Exception('AAA BBB CCC', 20); }, null, 'bbb', null)),
			array(false, array(function(){ throw new \Exception('AAA BBB CCC', 20); }, null, 'ccc', null)),
			array(false, array(function(){ throw new \Exception('AAA BBB CCC', 20); }, null, 'AAA ZZZ CCC', null)),
			array(false, array(function(){ throw new \Exception('AAA BBB CCC', 20); }, null, 'ZZZ AAA BBB CCC ZZZ', null)),
			array(false, array(function(){ throw new \Exception('AAA BBB CCC', 20); }, null, 'zzz', null)),
			array(false, array(function(){ throw new \Exception('AAA BBB CCC', 20); }, null, null, 0)),
			array(true, array(function(){ throw new \Exception('AAA BBB CCC', 20); }, null, null, 20)),
			array(false, array(function(){ throw new \Exception('AAA BBB CCC', 20); }, '\Exception', '', 0)),
			array(false, array(function(){ throw new \Exception('AAA BBB CCC', 20); }, '\Exception', '', 20)),
			array(false, array(function(){ throw new \Exception('AAA BBB CCC', 20); }, '\Exception', 'AAA BBB CCC', 0)),
			array(true, array(function(){ throw new \Exception('AAA BBB CCC', 20); }, '\Exception', 'AAA BBB CCC', 20)),
			
			array(true, array(function(){ throw new \Exception('AAA bbb CCC'); }, null, null, null)),
			array(true, array(function(){ throw new \Exception('AAA bbb CCC'); }, '\Exception', null, null)),
			array(true, array(function(){ throw new \Exception('AAA bbb CCC'); }, '\exception', null, null)),
			array(true, array(function(){ throw new \Exception('AAA bbb CCC'); }, 'Exception', null, null)),
			array(true, array(function(){ throw new \Exception('AAA bbb CCC'); }, 'exception', null, null)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC'); }, $level2Exception, null, null)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC'); }, $level3Exception, null, null)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC'); }, null, '')),
			array(true, array(function(){ throw new \Exception('AAA bbb CCC'); }, null, ' ')),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC'); }, null, 'AAA BBB CCC', null)),
			array(true, array(function(){ throw new \Exception('AAA bbb CCC'); }, null, 'AAA bbb CCC', null)),
			array(true, array(function(){ throw new \Exception('AAA bbb CCC'); }, null, 'AAA', null)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC'); }, null, 'BBB', null)),
			array(true, array(function(){ throw new \Exception('AAA bbb CCC'); }, null, 'CCC', null)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC'); }, null, 'aaa', null)),
			array(true, array(function(){ throw new \Exception('AAA bbb CCC'); }, null, 'bbb', null)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC'); }, null, 'ccc', null)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC'); }, null, 'AAA ZZZ CCC', null)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC'); }, null, 'ZZZ AAA BBB CCC ZZZ', null)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC'); }, null, 'zzz', null)),
			array(true, array(function(){ throw new \Exception('AAA bbb CCC'); }, null, null, 0)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC'); }, null, null, 20)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC'); }, '\Exception', '', 0)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC'); }, '\Exception', '', 20)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC'); }, '\Exception', 'AAA BBB CCC', 0)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC'); }, '\Exception', 'AAA BBB CCC', 20)),

			array(false, array(function(){ throw new \Exception('AAA bbb CCC', 0); }, '\Exception', '', 0)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC', 0); }, '\Exception', '', 20)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC', 0); }, '\Exception', 'AAA BBB CCC', 0)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC', 0); }, '\Exception', 'AAA BBB CCC', 20)),
			
			array(true, array(function(){ throw new \Exception('AAA bbb CCC', 20); }, null, null, null)),
			array(true, array(function(){ throw new \Exception('AAA bbb CCC', 20); }, '\Exception', null, null)),
			array(true, array(function(){ throw new \Exception('AAA bbb CCC', 20); }, '\exception', null, null)),
			array(true, array(function(){ throw new \Exception('AAA bbb CCC', 20); }, 'Exception', null, null)),
			array(true, array(function(){ throw new \Exception('AAA bbb CCC', 20); }, 'exception', null, null)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC', 20); }, $level2Exception, null, null)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC', 20); }, $level3Exception, null, null)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC', 20); }, null, '')),
			array(true, array(function(){ throw new \Exception('AAA bbb CCC', 20); }, null, ' ')),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC', 20); }, null, 'AAA BBB CCC', null)),
			array(true, array(function(){ throw new \Exception('AAA bbb CCC', 20); }, null, 'AAA bbb CCC', null)),
			array(true, array(function(){ throw new \Exception('AAA bbb CCC', 20); }, null, 'AAA', null)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC', 20); }, null, 'BBB', null)),
			array(true, array(function(){ throw new \Exception('AAA bbb CCC', 20); }, null, 'CCC', null)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC', 20); }, null, 'aaa', null)),
			array(true, array(function(){ throw new \Exception('AAA bbb CCC', 20); }, null, 'bbb', null)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC', 20); }, null, 'ccc', null)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC', 20); }, null, 'AAA ZZZ CCC', null)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC', 20); }, null, 'ZZZ AAA BBB CCC ZZZ', null)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC', 20); }, null, 'zzz', null)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC', 20); }, null, null, 0)),
			array(true, array(function(){ throw new \Exception('AAA bbb CCC', 20); }, null, null, 20)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC', 20); }, '\Exception', '', 0)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC', 20); }, '\Exception', '', 20)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC', 20); }, '\Exception', 'AAA BBB CCC', 0)),
			array(false, array(function(){ throw new \Exception('AAA bbb CCC', 20); }, '\Exception', 'AAA BBB CCC', 20)),
			
			//
			
			array(true, array(function(){ throw new \exception(); }, null, null, null)),
			array(true, array(function(){ throw new \exception(); }, '\Exception', null, null)),
			array(true, array(function(){ throw new \exception(); }, '\exception', null, null)),
			array(true, array(function(){ throw new \exception(); }, 'Exception', null, null)),
			array(true, array(function(){ throw new \exception(); }, 'exception', null, null)),
			array(false, array(function(){ throw new \exception(); }, $level2Exception, null, null)),
			array(false, array(function(){ throw new \exception(); }, $level3Exception, null, null)),
			
			array(true, array(function() use($level2Exception){ throw new $level2Exception(); }, null, null, null)),
			array(true, array(function() use($level2Exception){ throw new $level2Exception(); }, '\Exception', null, null)),
			array(true, array(function() use($level2Exception){ throw new $level2Exception(); }, 'exception', null, null)),
			array(true, array(function() use($level2Exception){ throw new $level2Exception(); }, $level2Exception, null, null)),
			array(false, array(function() use($level2Exception){ throw new $level2Exception(); }, $level3Exception, null, null)),
			
			array(true, array(function() use($level3Exception){ throw new $level3Exception(); }, null, null, null)),
			array(true, array(function() use($level3Exception){ throw new $level3Exception(); }, '\Exception', null, null)),
			array(true, array(function() use($level3Exception){ throw new $level3Exception(); }, 'exception', null, null)),
			array(true, array(function() use($level3Exception){ throw new $level3Exception(); }, $level2Exception, null, null)),
			array(true, array(function() use($level3Exception){ throw new $level3Exception(); }, $level3Exception, null, null)),
			
			//
		
			array(false, array(function(){}, null, null, null)),
		);
	}
	
	/**
	 * @dataProvider providerMatcherCall
	 */
	public function testMatcherCall($expectedResult, $arguments)
	{
		$this->assertSame($expectedResult, call_user_func_array('\spectrum\matchers\throwsException', $arguments));
	}
	
	public function testFunctionWithTestCodeIsNotCallable_ThrowsException()
	{
		$this->assertThrowsException('\spectrum\Exception', 'Function with test code is not callable', function(){
			\spectrum\matchers\throwsException('');
		});
	}
	
	public function testExpectedClassIsNotSubclassOfExceptionClass_ThrowsExceptionAndDoesNotCallFunctionWithTestCode()
	{
		$isCalled = false;
		$this->assertThrowsException('\spectrum\Exception', 'Expected class should be subclass of "\Exception" class (now "\stdClass" is not subclass of "\Exception" class)', function() use(&$isCalled){
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
	public function testExpectedClassIsNotString_ThrowsExceptionAndDoesNotCallFunctionWithTestCode($value)
	{
		$isCalled = false;
		$this->assertThrowsException('\spectrum\Exception', 'Expected class should be not empty string', function() use(&$isCalled, $value){
			\spectrum\matchers\throwsException(function() use(&$isCalled){ $isCalled = true; }, $value);
		});
		
		$this->assertSame(false, $isCalled);
	}
	
	public function testExpectedClassIsEmptyString_ThrowsExceptionAndDoesNotCallFunctionWithTestCode()
	{
		$isCalled = false;
		$this->assertThrowsException('\spectrum\Exception', 'Expected class should be not empty string', function() use(&$isCalled){
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
		$this->assertThrowsException('\spectrum\Exception', 'Expected string in message should be a string', function() use(&$isCalled, $value){
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
	public function testExpectedCodeIsNotInteger_ThrowsExceptionAndDoesNotCallFunctionWithTestCode($value)
	{
		$isCalled = false;
		$this->assertThrowsException('\spectrum\Exception', 'Expected code should be a integer', function() use(&$isCalled, $value){
			\spectrum\matchers\throwsException(function() use(&$isCalled){ $isCalled = true; }, null, null, $value);
		});
		
		$this->assertSame(false, $isCalled);
	}
}