<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
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
	
	public function testCommandCall_CallsRegisteredCommandFunction()
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
	
	public function testCommandCall_PassesPassedArgumentsToCalledRegisteredCommandFunctionStartingFromSecondArgument()
	{
		$passedArguments = array();
		config::registerConstructionCommand('aaa', function() use(&$passedArguments){
			$passedArguments[] = array_slice(func_get_args(), 1);
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

	public function testCommandCall_ReturnsReturnValueOfCalledRegisteredCommandFunction()
	{
		config::registerConstructionCommand('aaa', function(){ return 152; });
		config::registerConstructionCommand('bbb', function(){ return 'some text'; });
		
		$this->assertSame(152, callBroker::aaa());
		$this->assertSame('some text', callBroker::bbb());
	}
	
	public function testCommandCall_LocksConfigOnRegisteredCommandFunctionCall()
	{
		config::registerConstructionCommand('aaa', function(){});
		
		$this->assertSame(false, config::isLocked());
		callBroker::aaa();
		$this->assertSame(true, config::isLocked());
	}
	
	public function testCommandCall_Storage_PassesCopyOfStorageToCalledRegisteredCommandFunctionAtFirstArguments()
	{
		$passedStorage1 = null;
		config::registerConstructionCommand('aaa', function($storage) use(&$passedStorage1){ $passedStorage1 = $storage; });
		
		$passedStorage2 = null;
		config::registerConstructionCommand('bbb', function($storage) use(&$passedStorage2){ $passedStorage2 = $storage; });
		
		callBroker::aaa();
		$this->assertSame(array(
			'_self_' => array(),
			'aaa' => array(),
		), $passedStorage1);
		
		callBroker::bbb();
		$this->assertSame(array(
			'_self_' => array(),
			'aaa' => array(),
			'bbb' => array(),
		), $passedStorage2);
		
		$this->assertNotSame($passedStorage1, $passedStorage2);
	}
	
	public function testCommandCall_Storage_SavesChangesInSelfElementToSectionWithConstructionCommandNameAsKey()
	{
		config::registerConstructionCommand('aaa', function($storage){
			$storage['_self_']['someKey1'] = 'someValue1';
			$storage['_self_']['someKey2'] = 'someValue2';
		});
		
		config::registerConstructionCommand('bbb', function($storage){
			$storage['_self_']['someKey1'] = 'someValue3';
		});
		
		$passedStorage = null;
		config::registerConstructionCommand('ccc', function($storage) use(&$passedStorage){
			$passedStorage = $storage;
		});
		
		callBroker::aaa();
		callBroker::bbb();
		callBroker::ccc();
		
		$this->assertSame(array(
			'_self_' => array(),
			'aaa' => array(
				'someKey1' => 'someValue1',
				'someKey2' => 'someValue2',
			),
			'bbb' => array(
				'someKey1' => 'someValue3',
			),
			'ccc' => array(),
		), $passedStorage);
	}
	
	public function testCommandCall_Storage_DoesNotSaveChangesInNamedElements()
	{
		config::registerConstructionCommand('aaa', function($storage){
			$storage['_self_']['someKey'] = 'someValue1';
		});
		
		config::registerConstructionCommand('bbb', function(&$storage){
			$storage['aaa']['someKey'] = 'someValue2';
		});
		
		$passedStorage = null;
		config::registerConstructionCommand('ccc', function($storage) use(&$passedStorage){
			$passedStorage = $storage;
		});
		
		callBroker::aaa();
		callBroker::bbb();
		callBroker::ccc();
		
		$this->assertSame(array(
			'_self_' => array(),
			'aaa' => array(
				'someKey' => 'someValue1',
			),
			'bbb' => array(),
			'ccc' => array(),
		), $passedStorage);
	}
	
	public function testCommandCall_Storage_CreatesEmptyArrayOnlyOnFirstConstructionCommandCall()
	{
		config::registerConstructionCommand('aaa', function($storage){
			$storage['_self_'][] = 'someValue';
		});
		
		$passedStorage = null;
		config::registerConstructionCommand('bbb', function($storage) use(&$passedStorage){
			$passedStorage = $storage;
		});
		
		callBroker::aaa();
		callBroker::bbb();
		
		$this->assertSame(array(
			'_self_' => array(),
			'aaa' => array('someValue'),
			'bbb' => array(),
		), $passedStorage);
		
		callBroker::aaa();
		callBroker::bbb();
		
		$this->assertSame(array(
			'_self_' => array(),
			'aaa' => array('someValue', 'someValue'),
			'bbb' => array(),
		), $passedStorage);
	}
	
	public function testCommandCall_ConstructionCommandIsNotRegistered_ThrowsException()
	{
		config::unregisterConstructionCommands('aaa');
		$this->assertThrowsException('\spectrum\constructionCommands\Exception', 'Construction command "aaa" is not registered', function(){
			callBroker::aaa();
		});
	}
	
	public function testCommandCall_ConstructionCommandFunctionIsNotCallable_ThrowsException()
	{
		config::registerConstructionCommand('aaa', 'notCallableFunctionName');
		$this->assertThrowsException('\spectrum\constructionCommands\Exception', 'Function of construction command "aaa" is not callable', function(){
			callBroker::aaa();
		});
	}
}