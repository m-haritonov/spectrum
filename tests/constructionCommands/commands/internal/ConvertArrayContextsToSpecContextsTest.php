<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\constructionCommands\commands\internal;

use spectrum\config;
use spectrum\constructionCommands\callBroker;

require_once __DIR__ . '/../../../init.php';

class ConvertArrayContextsToSpecContextsTest extends \spectrum\tests\Test
{
	public function testCallsAtDeclaringState_ReturnsSpecTreeWithBeforeContextFunctions()
	{
		$specs = callBroker::internal_convertArrayContextsToSpecContexts(array(
			array(),
			array('aaa'),
			array('bbb', 'ccc'),
		));
		
		$this->assertSame(3, count($specs));
		
		$contexts = $specs[0]->contexts->getAll();
		$this->assertSame(1, count($contexts));
		$this->assertSame('before', $contexts[0]['type']);
		
		$contexts = $specs[1]->contexts->getAll();
		$this->assertSame(1, count($contexts));
		$this->assertSame('before', $contexts[0]['type']);
		
		$contexts = $specs[2]->contexts->getAll();
		$this->assertSame(1, count($contexts));
		$this->assertSame('before', $contexts[0]['type']);
	}
	
	public function testCallsAtDeclaringState_UsesSetNameForArgumentsFunctionForSpecNameGetting()
	{
		config::unregisterConstructionCommands('internal_getNameForArguments');
		config::registerConstructionCommand('internal_getNameForArguments', function($storage, array $arguments, $defaultName){
			if (!$arguments)
				return $defaultName;
			else
				return $defaultName . '_' . implode('_', $arguments);
		});
		
		$specs = callBroker::internal_convertArrayContextsToSpecContexts(array(
			'some name 1' => array('aaa', 'bbb'),
			'some name 2' => array('ccc'),
			array('ddd', 'eee'),
			array(),
		));
		
		$this->assertSame('some name 1_aaa_bbb', $specs[0]->getName());
		$this->assertSame('some name 2_ccc', $specs[1]->getName());
		$this->assertSame('0_ddd_eee', $specs[2]->getName());
		$this->assertSame(1, $specs[3]->getName());
	}
	
	public function testCallsAtDeclaringState_CreatesContextFunctionsWithElementKeyAsContextDataPropertyName()
	{
		\spectrum\tests\Test::$temp["contextDataObjects"] = array();
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["contextDataObjects"][] = $this->getOwnerSpec()->contexts->getContextData();
		', 'onEndingSpecExecute');
		
		$specs = callBroker::internal_convertArrayContextsToSpecContexts(array(
			array('aaa1', 'aaa2' => 'aaa3', 'aaa4' => 'aaa5'),
			array('bbb1', 'bbb2' => 'bbb3', 'bbb4' => 'bbb5'),
			array('ccc1', 'ccc2' => 'ccc3', 'ccc4' => 'ccc5'),
		));
		
		callBroker::internal_getRootSpec()->bindChildSpec($specs[0]);
		callBroker::internal_getRootSpec()->bindChildSpec($specs[1]);
		callBroker::internal_getRootSpec()->bindChildSpec($specs[2]);
		callBroker::internal_getRootSpec()->run();

		$this->assertSame(array('aaa1', 'aaa2' => 'aaa3', 'aaa4' => 'aaa5'), get_object_vars(\spectrum\tests\Test::$temp["contextDataObjects"][0]));
		$this->assertSame(array('bbb1', 'bbb2' => 'bbb3', 'bbb4' => 'bbb5'), get_object_vars(\spectrum\tests\Test::$temp["contextDataObjects"][1]));
		$this->assertSame(array('ccc1', 'ccc2' => 'ccc3', 'ccc4' => 'ccc5'), get_object_vars(\spectrum\tests\Test::$temp["contextDataObjects"][2]));
	}
	
	public function testCallsAtDeclaringState_ElementIsNotArray_UsesElementAsArraysWithOneElement()
	{
		config::unregisterConstructionCommands('internal_getNameForArguments');
		config::registerConstructionCommand('internal_getNameForArguments', function($storage, array $arguments, $defaultName){
			return $defaultName;
		});
		
		\spectrum\tests\Test::$temp["contextDataObjects"] = array();
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["contextDataObjects"][] = $this->getOwnerSpec()->contexts->getContextData();
		', 'onEndingSpecExecute');
		
		$specs = callBroker::internal_convertArrayContextsToSpecContexts(array(
			'some name 1' => 'aaa',
			'some name 2' => 'bbb',
			'ccc',
		));
		
		$this->assertSame('some name 1', $specs[0]->getName());
		$this->assertSame('some name 2', $specs[1]->getName());
		$this->assertSame(0, $specs[2]->getName());
		
		callBroker::internal_getRootSpec()->bindChildSpec($specs[0]);
		callBroker::internal_getRootSpec()->bindChildSpec($specs[1]);
		callBroker::internal_getRootSpec()->bindChildSpec($specs[2]);
		callBroker::internal_getRootSpec()->run();

		$this->assertSame(array(0 => 'aaa'), get_object_vars(\spectrum\tests\Test::$temp["contextDataObjects"][0]));
		$this->assertSame(array(0 => 'bbb'), get_object_vars(\spectrum\tests\Test::$temp["contextDataObjects"][1]));
		$this->assertSame(array(0 => 'ccc'), get_object_vars(\spectrum\tests\Test::$temp["contextDataObjects"][2]));
	}
}