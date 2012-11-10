<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\verifications\verification\operators;
use spectrum\core\verifications\Verification;
use spectrum\core\verifications\CallDetails;

require_once __DIR__ . '/../../../../init.php';

class InstanceofTest extends \spectrum\core\verifications\verification\Test
{
	public function testShouldBeThrowExceptionIfValue1IsString()
	{
		$test = $this;
		$this->assertThrowException('\spectrum\core\verifications\Exception', '"instanceof" operator in verification can accept only object as first operand (now string passed)', function() use($test){
			$it = $test->createSpecIt();
			$it->errorHandling->setCatchExceptions(false);
			$test->runInTestCallback($it, function($test, $it){
				new Verification('\stdClass', 'instanceof', '\stdClass');
			});
		});
	}

	public function testShouldBeReturnTrueIfValue1ObjectInstanceOfValue2Object()
	{
		$this->runInTestCallback($this->createSpecIt(), function($test, $it) use(&$verify)
		{
			$object1 = new \stdClass();
			$object2 = new \stdClass();
			$verify = new Verification($object1, 'instanceof', $object2);
		});
		
		$this->assertTrue($verify->getCallDetails()->getResult());
	}
	
	public function testShouldBeReturnFalseIfValue1ObjectNotInstanceOfValue2Object()
	{
		$this->runInTestCallback($this->createSpecIt(), function($test, $it) use(&$verify)
		{
			$object1 = new \stdClass();
			$object2 = new \Exception();
			$verify = new Verification($object1, 'instanceof', $object2);
		});
		
		$this->assertFalse($verify->getCallDetails()->getResult());
	}
}