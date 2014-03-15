<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\_internal;

require_once __DIR__ . '/../init.php';

class ConvertArrayWithContextsToSpecsTest extends \spectrum\tests\Test
{
	public function testCallsAtBuildingState_ReturnsSpecTreeWithBeforeContextFunctions()
	{
		$specs = \spectrum\_internal\convertArrayWithContextsToSpecs(array(
			array(),
			array('aaa'),
			array('bbb', 'ccc'),
		), null);
		
		$this->assertSame(3, count($specs));
		
		$contexts = $specs[0]->contextModifiers->getAll();
		$this->assertSame(1, count($contexts));
		$this->assertSame('before', $contexts[0]['type']);
		
		$contexts = $specs[1]->contextModifiers->getAll();
		$this->assertSame(1, count($contexts));
		$this->assertSame('before', $contexts[0]['type']);
		
		$contexts = $specs[2]->contextModifiers->getAll();
		$this->assertSame(1, count($contexts));
		$this->assertSame('before', $contexts[0]['type']);
	}
	
	public function testCallsAtBuildingState_CreatesContextFunctionsWithElementKeyAsContextDataPropertyName()
	{
		$specs = \spectrum\_internal\convertArrayWithContextsToSpecs(array(
			array('aaa1', 'aaa2' => 'aaa3', 'aaa4' => 'aaa5'),
			array('bbb1', 'bbb2' => 'bbb3', 'bbb4' => 'bbb5'),
			array('ccc1', 'ccc2' => 'ccc3', 'ccc4' => 'ccc5'),
		), null);
		
		\spectrum\_internal\getRootSpec()->bindChildSpec($specs[0]);
		\spectrum\_internal\getRootSpec()->bindChildSpec($specs[1]);
		\spectrum\_internal\getRootSpec()->bindChildSpec($specs[2]);
		
		$contextDataObjects = array();
		foreach ($specs as $spec)
		{
			$spec->test->setFunction(function() use(&$contextDataObjects, $spec){
				$contextDataObjects[] = $spec->test->getContextData();
			});
		}
		
		\spectrum\_internal\getRootSpec()->run();

		$this->assertSame(array('aaa1', 'aaa2' => 'aaa3', 'aaa4' => 'aaa5'), get_object_vars($contextDataObjects[0]));
		$this->assertSame(array('bbb1', 'bbb2' => 'bbb3', 'bbb4' => 'bbb5'), get_object_vars($contextDataObjects[1]));
		$this->assertSame(array('ccc1', 'ccc2' => 'ccc3', 'ccc4' => 'ccc5'), get_object_vars($contextDataObjects[2]));
	}
	
	public function testCallsAtBuildingState_ElementIsNotArray_UsesElementAsArraysWithOneElement()
	{
		$specs = \spectrum\_internal\convertArrayWithContextsToSpecs(array(
			'some name 1' => 'aaa',
			'some name 2' => 'bbb',
			null,
		), null);
		
		$this->assertSame('some name 1', $specs[0]->getName());
		$this->assertSame('some name 2', $specs[1]->getName());
		$this->assertSame(0, $specs[2]->getName());
		
		\spectrum\_internal\getRootSpec()->bindChildSpec($specs[0]);
		\spectrum\_internal\getRootSpec()->bindChildSpec($specs[1]);
		\spectrum\_internal\getRootSpec()->bindChildSpec($specs[2]);
		
		$contextDataObjects = array();
		foreach ($specs as $spec)
		{
			$spec->test->setFunction(function() use(&$contextDataObjects, $spec){
				$contextDataObjects[] = $spec->test->getContextData();
			});
		}
		
		\spectrum\_internal\getRootSpec()->run();

		$this->assertSame(array(0 => 'aaa'), get_object_vars($contextDataObjects[0]));
		$this->assertSame(array(0 => 'bbb'), get_object_vars($contextDataObjects[1]));
		$this->assertSame(array(0 => null), get_object_vars($contextDataObjects[2]));
	}
	
	public function provider()
	{
		return array(
			array('aaa', array('' => array('aaa'))),
			array('aaa', array(0 => array('aaa'))),
			array('aaa', array(1 => array('aaa'))),
			array('aaa', array(1 => array('aaa', 'bbb'))),
			
			array(123, array('' => array(123))),
			array(123, array(0 => array(123))),
			array(123, array(1 => array(123))),
			array(123, array(1 => array(123, 'aaa'))),
			
			array('bbb', array('bbb' => array('aaa'))),
			
			array(str_repeat('a', 100), array(null => array(str_repeat('a', 100)))),
			array(str_repeat('a', 100) . '...', array(null => array(str_repeat('a', 101)))),
			array(str_repeat('a', 100) . '...', array(null => array(str_repeat('a', 200)))),
		);
	}
	
	/**
	 * @dataProvider provider
	 */
	public function testCallsAtBuildingState_SetsToSpecProperName($expectedName, $contexts)
	{
		$specs = \spectrum\_internal\convertArrayWithContextsToSpecs($contexts, null);
		$this->assertSame($expectedName, $specs[0]->getName());
	}
}