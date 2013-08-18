<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core\asserts\baseMatchers;
require_once __DIR__ . '/../../../init.php';
require_once __DIR__ . '/../../../../spectrum/core/asserts/baseMatchers/throwsException.php';

class ThrowsExceptionTest extends \spectrum\tests\core\Test
{
	public function dataProvider()
	{
		return array(
			в цикле составить из комбинации состояний аргументов (8 шт.) и состояний exception (с messages, code и разными классами)
			array(true, function(){ throw new \Exception(); }, null, null, null),
			array(true, function(){ throw new \Exception(); }, '\Exception', null, null),
			
			array(array('\Exception'), new \Exception(), true),
			array(array('\spectrum\core\Exception'), new \spectrum\core\verifications\Exception(), true),
			array(array('\spectrum\core\verifications\Exception'), new \spectrum\core\verifications\Exception(), true),
			array(array('\Exception'), null, false),
			array(array('\spectrum\core\verifications\Exception'), new \Exception(), false),
			array(array('\spectrum\core\verifications\Exception'), new \spectrum\core\Exception(), false),

			array(array('\Exception', 'aaa bbb'), new \Exception('aaa bbb'), true),
			array(array('\spectrum\core\Exception', 'aaa bbb'), new \spectrum\core\verifications\Exception('aaa bbb'), true),
			array(array('\spectrum\core\verifications\Exception', 'aaa bbb'), new \spectrum\core\verifications\Exception('aaa bbb'), true),
			array(array('\spectrum\core\verifications\Exception', 'aaa bbb'), new \spectrum\core\verifications\Exception('AaA BBB'), true), // Should be ignore case in exception message
			array(array('\spectrum\core\verifications\Exception', 'AaA BBB'), new \spectrum\core\verifications\Exception('aaa bbb'), true), // Should be ignore case in value2 string
			array(array('\spectrum\core\verifications\Exception', 'bbb ccc'), new \spectrum\core\verifications\Exception('aaa bbb ccc ddd'), true), // Should be use incomplete entry search (entry in middle of string)
			array(array('\spectrum\core\verifications\Exception', 'bbb ccc'), new \spectrum\core\verifications\Exception('bbb ccc ddd'), true), // Should be use incomplete entry search (entry in begin of string)
			array(array('\spectrum\core\verifications\Exception', 'bbb ccc'), new \spectrum\core\verifications\Exception('aaa bbb ccc'), true), // Should be use incomplete entry search (entry in end of string)
			array(array('\Exception', 'aaa bbb'), null, false),
			array(array('\spectrum\core\verifications\Exception', 'aaa bbb'), new \Exception(), false),
			array(array('\spectrum\core\verifications\Exception', 'aaa bbb'), new \spectrum\core\verifications\Exception(), false), // Should be return false if thrown exception contains empty message
			array(array('\spectrum\core\verifications\Exception', 'aaa bbb'), new \spectrum\core\verifications\Exception('ccc'), false), // Should be return false if thrown exception not contains expected string in message
			array(array('\spectrum\core\verifications\Exception', 'aaa bbb'), new \Exception('ccc'), false),
		
			array(array('\Exception', null), new \Exception(), true),
			array(array('\spectrum\core\Exception', null), new \spectrum\core\verifications\Exception(), true),
			array(array('\spectrum\core\verifications\Exception', null), new \spectrum\core\verifications\Exception(), true),
			array(array('\Exception', null), null, false),
			array(array('\spectrum\core\verifications\Exception', null), new \Exception(), false),
			array(array('\spectrum\core\verifications\Exception', null), new \spectrum\core\Exception(), false),
			
			array(array(null, 'aaa bbb'), new \Exception('aaa bbb'), true), // Should be catch all exceptions with expected message
			array(array(null, 'aaa bbb'), new \spectrum\core\verifications\Exception('aaa bbb'), true),
			array(array(null, 'aaa bbb'), new \spectrum\core\verifications\Exception('aaa bbb'), true),
			array(array(null, 'aaa bbb'), new \spectrum\core\verifications\Exception('AaA BBB'), true), // Should be ignore case in exception message
			array(array(null, 'AaA BBB'), new \spectrum\core\verifications\Exception('aaa bbb'), true), // Should be ignore case in value2 string
			array(array(null, 'bbb ccc'), new \spectrum\core\verifications\Exception('aaa bbb ccc ddd'), true), // Should be use incomplete entry search (entry in middle of string)
			array(array(null, 'bbb ccc'), new \spectrum\core\verifications\Exception('bbb ccc ddd'), true), // Should be use incomplete entry search (entry in begin of string)
			array(array(null, 'bbb ccc'), new \spectrum\core\verifications\Exception('aaa bbb ccc'), true), // Should be use incomplete entry search (entry in end of string)
			array(array(null, 'aaa bbb'), null, false),
			array(array(null, 'aaa bbb'), new \Exception(), false),
			array(array(null, 'aaa bbb'), new \spectrum\core\verifications\Exception(), false), // Should be return false if thrown exception contains empty message
			array(array(null, 'aaa bbb'), new \spectrum\core\verifications\Exception('ccc'), false), // Should be return false if thrown exception not contains expected string in message
			array(array(null, 'aaa bbb'), new \Exception('ccc'), false),
			
			array(array('\Exception', 'aaa bbb', 123), new \Exception('aaa bbb', 123), true),
			array(array('\spectrum\core\Exception', 'aaa bbb', 123), new \spectrum\core\verifications\Exception('aaa bbb', 123), true),
			array(array('\spectrum\core\verifications\Exception', 'aaa bbb', 123), new \spectrum\core\verifications\Exception('aaa bbb', 123), true),
			array(array('\Exception', 'aaa bbb', 123), null, false),
			array(array('\Exception', 'aaa bbb', 123), new \Exception(), false),
			array(array('\Exception', 'aaa bbb', 123), new \Exception(null, 111), false),
			array(array('\Exception', 'aaa bbb', 123), new \Exception('aaa bbb', 111), false),
		
			array(array('\Exception', 'aaa bbb', null), new \Exception('aaa bbb'), true),
			array(array('\spectrum\core\Exception', 'aaa bbb', null), new \spectrum\core\verifications\Exception('aaa bbb'), true),
			array(array('\spectrum\core\verifications\Exception', 'aaa bbb', null), new \spectrum\core\verifications\Exception('aaa bbb'), true),
			array(array('\spectrum\core\verifications\Exception', 'aaa bbb', null), new \spectrum\core\verifications\Exception('AaA BBB'), true), // Should be ignore case in exception message
			array(array('\spectrum\core\verifications\Exception', 'AaA BBB', null), new \spectrum\core\verifications\Exception('aaa bbb'), true), // Should be ignore case in value2 string
			array(array('\spectrum\core\verifications\Exception', 'bbb ccc', null), new \spectrum\core\verifications\Exception('aaa bbb ccc ddd'), true), // Should be use incomplete entry search (entry in middle of string)
			array(array('\spectrum\core\verifications\Exception', 'bbb ccc', null), new \spectrum\core\verifications\Exception('bbb ccc ddd'), true), // Should be use incomplete entry search (entry in begin of string)
			array(array('\spectrum\core\verifications\Exception', 'bbb ccc', null), new \spectrum\core\verifications\Exception('aaa bbb ccc'), true), // Should be use incomplete entry search (entry in end of string)
			array(array('\Exception', 'aaa bbb', null), null, false),
			array(array('\spectrum\core\verifications\Exception', 'aaa bbb', null), new \Exception(), false),
			array(array('\spectrum\core\verifications\Exception', 'aaa bbb', null), new \spectrum\core\verifications\Exception(), false), // Should be return false if thrown exception contains empty message
			array(array('\spectrum\core\verifications\Exception', 'aaa bbb', null), new \spectrum\core\verifications\Exception('ccc'), false), // Should be return false if thrown exception not contains expected string in message
			array(array('\spectrum\core\verifications\Exception', 'aaa bbb', null), new \Exception('ccc'), false),
		
			array(array('\Exception', null, 123), new \Exception(null, 123), true),
			array(array('\Exception', null, 123), new \Exception('aaa bbb', 123), true),
			array(array('\spectrum\core\Exception', null, 123), new \spectrum\core\verifications\Exception('aaa bbb', 123), true),
			array(array('\spectrum\core\verifications\Exception', null, 123), new \spectrum\core\verifications\Exception('aaa bbb', 123), true),
			array(array('\Exception', null, 123), null, false),
			array(array('\Exception', null, 123), new \Exception(), false),
			array(array('\Exception', null, 123), new \Exception(null, 111), false),
			array(array('\Exception', null, 123), new \Exception('aaa bbb', 111), false),
		
			array(array('\Exception', null, null), new \Exception(), true),
			array(array('\spectrum\core\Exception', null, null), new \spectrum\core\verifications\Exception(), true),
			array(array('\spectrum\core\verifications\Exception', null, null), new \spectrum\core\verifications\Exception(), true),
			array(array('\Exception', null, null), null, false),
			array(array('\spectrum\core\verifications\Exception', null, null), new \Exception(), false),
			array(array('\spectrum\core\verifications\Exception', null, null), new \spectrum\core\Exception(), false),
		
			array(array(null, 'aaa bbb', 123), new \Exception('aaa bbb', 123), true),
			array(array(null, 'aaa bbb', 123), new \spectrum\core\verifications\Exception('aaa bbb', 123), true),
			array(array(null, 'aaa bbb', 123), null, false),
			array(array(null, 'aaa bbb', 123), new \Exception(), false),
			array(array(null, 'aaa bbb', 123), new \Exception(null, 111), false),
			array(array(null, 'aaa bbb', 123), new \Exception('aaa bbb', 111), false),
			array(array(null, 'ссс', 123), new \Exception('aaa bbb', 111), false),
			array(array(null, 'ссс', 123), new \Exception('aaa bbb', 123), false),
		
			array(array(null, 'aaa bbb', null), new \Exception('aaa bbb'), true), // Should be catch all exceptions with expected message
			array(array(null, 'aaa bbb', null), new \spectrum\core\verifications\Exception('aaa bbb'), true),
			array(array(null, 'aaa bbb', null), new \spectrum\core\verifications\Exception('aaa bbb'), true),
			array(array(null, 'aaa bbb', null), new \spectrum\core\verifications\Exception('AaA BBB'), true), // Should be ignore case in exception message
			array(array(null, 'AaA BBB', null), new \spectrum\core\verifications\Exception('aaa bbb'), true), // Should be ignore case in value2 string
			array(array(null, 'bbb ccc', null), new \spectrum\core\verifications\Exception('aaa bbb ccc ddd'), true), // Should be use incomplete entry search (entry in middle of string)
			array(array(null, 'bbb ccc', null), new \spectrum\core\verifications\Exception('bbb ccc ddd'), true), // Should be use incomplete entry search (entry in begin of string)
			array(array(null, 'bbb ccc', null), new \spectrum\core\verifications\Exception('aaa bbb ccc'), true), // Should be use incomplete entry search (entry in end of string)
			array(array(null, 'aaa bbb', null), null, false),
			array(array(null, 'aaa bbb', null), new \Exception(), false),
			array(array(null, 'aaa bbb', null), new \spectrum\core\verifications\Exception(), false), // Should be return false if thrown exception contains empty message
			array(array(null, 'aaa bbb', null), new \spectrum\core\verifications\Exception('ccc'), false), // Should be return false if thrown exception not contains expected string in message
			array(array(null, 'aaa bbb', null), new \Exception('ccc'), false),

			array(array(null, null, 123), new \Exception(null, 123), true),
			array(array(null, null, 123), new \Exception('aaa bbb', 123), true),
			array(array(null, null, 123), new \spectrum\core\verifications\Exception('aaa bbb', 123), true),
			array(array(null, null, 123), null, false),
			array(array(null, null, 123), new \Exception(), false),
			array(array(null, null, 123), new \Exception(null, 111), false),
			array(array(null, null, 123), new \Exception('aaa bbb', 111), false),
		);
	}
	
	public function testExceptedArgumentsIsCorrect_ShouldBeReturnTrueOrFalse($returnValue)
	{
		$this->assertSame($returnValue, call_user_func_array('\spectrum\core\baseMatchers\throwsException', array_slice(func_get_args(), 1)));
	}
	
	public function testExceptedClassIsNotInstanceOfException_ShouldBeThrowException()
	{
		$this->assertThrowsException(
			'\spectrum\core\Exception', 
			'Matcher "\spectrum\core\baseMatchers\throwsException" can accept only callable function as first operand (now passed callback not callable)', 
			function(){
				\spectrum\core\baseMatchers\throwsException(function(){ throw new \Exception(); }, '\spectrum\tests\testHelpers\NotException');
			}
		);
	}
}