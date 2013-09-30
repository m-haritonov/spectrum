<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\constructionCommands;

use spectrum\config;
use spectrum\constructionCommands\callBroker;

require_once __DIR__ . '/../init.php';

class CallBrokerTest extends \spectrum\tests\Test
{
	public function setUp()
	{
		parent::setUp();
		config::unregisterConstructionCommands();
	}
	
	public function testCallsRegisteredCommandFunction()
	{
		$calls = array();
		config::registerConstructionCommand('aaa', function() use(&$calls){ $calls[] = 'aaa'; });
		config::registerConstructionCommand('bbb', function() use(&$calls){ $calls[] = 'bbb'; });
		config::registerConstructionCommand('ccc', function() use(&$calls){ $calls[] = 'ccc'; });
		
		callBroker::aaa();
		$this->assertSame(array('aaa'), $calls);
		
		callBroker::ccc();
		$this->assertSame(array('aaa', 'ccc'), $calls);
		
		callBroker::bbb();
		$this->assertSame(array('aaa', 'ccc', 'bbb'), $calls);
		
		callBroker::aaa();
		$this->assertSame(array('aaa', 'ccc', 'bbb', 'aaa'), $calls);
	}
	
	public function testPassesArgumentsToCalledRegisteredCommandFunction()
	{
		$passedArguments = array();
		config::registerConstructionCommand('aaa', function() use(&$passedArguments){
			$passedArguments[] = func_get_args();
		});
		
		callBroker::aaa('aaa', 'bbb', 'ccc');
		$this->assertSame(array(
			array('aaa', 'bbb', 'ccc'),
		), $passedArguments);
		
		callBroker::aaa(111, true, 888);
		$this->assertSame(array(
			array('aaa', 'bbb', 'ccc'),
			array(111, true, 888),
		), $passedArguments);
	}

	public function testReturnsReturnValueOfCalledRegisteredCommandFunction()
	{
		config::registerConstructionCommand('aaa', function(){ return 152; });
		config::registerConstructionCommand('bbb', function(){ return 'some text'; });
		
		$this->assertSame(152, callBroker::aaa());
		$this->assertSame('some text', callBroker::bbb());
	}
	
	public function testLocksConfigOnRegisteredCommandFunctionCall()
	{
		config::registerConstructionCommand('aaa', function(){});
		
		$this->assertSame(false, config::isLocked());
		callBroker::aaa();
		$this->assertSame(true, config::isLocked());
	}
}