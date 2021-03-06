<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core\_private;

require_once __DIR__ . '/../../../init.php';

class ConvertArrayWithContextsToSpecsTest extends \spectrum\tests\automatic\Test {
	public function testCallsAtBuildingState_ElementIsArray_ReturnsSpecsWithBeforeContextFunctionsThatSetsValuesToData() {
		$specs = \spectrum\core\_private\convertArrayWithContextsToSpecs(array(
			array(),
			array('aaa'),
			array('bbb', 'ccc'),
			array('aaa1', 'aaa2' => 'aaa3', 'aaa4' => 'aaa5'),
			array('bbb1', 'bbb2' => 'bbb3', 'bbb4' => 'bbb5'),
			array('ccc1', 'ccc2' => 'ccc3', 'ccc4' => 'ccc5'),
		));
		
		$this->assertSame(6, count($specs));
		
		$contexts = $specs[0]->getContextModifiers()->getAll();
		$this->assertSame(1, count($contexts));
		$this->assertSame('before', $contexts[0]['type']);
		
		$contexts = $specs[1]->getContextModifiers()->getAll();
		$this->assertSame(1, count($contexts));
		$this->assertSame('before', $contexts[0]['type']);
		
		$contexts = $specs[2]->getContextModifiers()->getAll();
		$this->assertSame(1, count($contexts));
		$this->assertSame('before', $contexts[0]['type']);
		
		$contexts = $specs[3]->getContextModifiers()->getAll();
		$this->assertSame(1, count($contexts));
		$this->assertSame('before', $contexts[0]['type']);
		
		$contexts = $specs[4]->getContextModifiers()->getAll();
		$this->assertSame(1, count($contexts));
		$this->assertSame('before', $contexts[0]['type']);
		
		$contexts = $specs[5]->getContextModifiers()->getAll();
		$this->assertSame(1, count($contexts));
		$this->assertSame('before', $contexts[0]['type']);
		
		
		$dataObjects = array();
		foreach ($specs as $spec) {
			\spectrum\core\_private\getRootSpec()->bindChildSpec($spec);
			$spec->getExecutor()->setFunction(function() use(&$dataObjects, $spec) {
				$dataObjects[] = $spec->getData();
			});
		}
		
		\spectrum\core\_private\getRootSpec()->run();

		$this->assertSame(array(), get_object_vars($dataObjects[0]));
		$this->assertSame(array(0 => 'aaa'), get_object_vars($dataObjects[1]));
		$this->assertSame(array(0 => 'bbb', 1 => 'ccc'), get_object_vars($dataObjects[2]));
		$this->assertSame(array(0 => 'aaa1', 'aaa2' => 'aaa3', 'aaa4' => 'aaa5'), get_object_vars($dataObjects[3]));
		$this->assertSame(array(0 => 'bbb1', 'bbb2' => 'bbb3', 'bbb4' => 'bbb5'), get_object_vars($dataObjects[4]));
		$this->assertSame(array(0 => 'ccc1', 'ccc2' => 'ccc3', 'ccc4' => 'ccc5'), get_object_vars($dataObjects[5]));
	}
	
	public function providerElementIsArray_KeysAsNames() {
		return array(
			array(array('bbb'), array('bbb' => array('aaa'))),
			array(array('AA bb CC'), array('AA bb CC' => array('aaa', 'bbb'))),
			array(
				array('AA bb CC', 'BB bb', 'CC cc'),
				array(
					'AA bb CC' => array('aaa', 'bbb'),
					'BB bb' => array('aaa', 'bbb'),
					'CC cc' => array('aaa', 'bbb'),
				)
			),
		);
	}
	
	/**
	 * @dataProvider providerElementIsArray_KeysAsNames
	 */
	public function testCallsAtBuildingState_ElementIsArray_ElementKeyIsNotEmptyString_SetsElementKeyAsSpecName($expectedNames, $contexts) {
		$names = array();
		foreach (\spectrum\core\_private\convertArrayWithContextsToSpecs($contexts) as $spec) {
			$names[] = $spec->getName();
		}
		
		$this->assertSame($expectedNames, $names);
	}
	
	public function providerElementIsArray_FirstValueAsNames() {
		return array(
			array(array('/"aaa", 123, 14.3, true, false, null, array, object, Resource id \#\d+, function/s'), array('' => array('aaa', 123, 14.3, true, false, null, array(), new \stdClass(), fopen('php://temp', 'r'), function(){})), true),
			
			array(array('"aaa"'), array('' => array('aaa'))),
			array(array('"aaa"'), array(0 => array('aaa'))),
			array(array('"aaa"'), array(0 => array('a' => 'aaa'))),
			array(array('"aaa"'), array(1 => array('aaa'))),
			array(array('"aaa", "bbb"'), array(1 => array('aaa', 'bbb'))),
			array(
				array('"aaa", "bbb"', '"ccc", "ddd"', '"eee"'),
				array(
					0 => array('a' => 'aaa', 'b' => 'bbb'),
					1 => array('c' => 'ccc', 'd' => 'ddd'),
					2 => array('e' => 'eee'),
				)
			),
			
			array(array('"AA bb CC", "bbb"'), array(0 => array('a' => 'AA bb CC', 'b' => 'bbb'))),
			
			array(array('123'), array('' => array(123))),
			array(array('123'), array(0 => array(123))),
			array(array('123'), array(1 => array(123))),
			array(array('123, "aaa"'), array(1 => array(123, 'aaa'))),
			
			array(array('"' . str_repeat('a', 98) . '"'), array(null => array(str_repeat('a', 98)))),
			array(array('"' . str_repeat('a', 99) . '...'), array(null => array(str_repeat('a', 99)))),
			array(array('"' . str_repeat('a', 99) . '...'), array(null => array(str_repeat('a', 200)))),
		);
	}
	
	/**
	 * @dataProvider providerElementIsArray_FirstValueAsNames
	 */
	public function testCallsAtBuildingState_ElementIsArray_ElementKeyIsIntegerOrEmptyString_SetsConcatenationOfValuesAsSpecName($expectedNames, $contexts, $useRegexp = false) {
		$names = array();
		foreach (\spectrum\core\_private\convertArrayWithContextsToSpecs($contexts) as $spec) {
			$names[] = $spec->getName();
		}
		
		if ($useRegexp) {
			foreach ($expectedNames as $key => $expectedName) {
				$this->assertSame(1, preg_match($expectedName, $names[$key]));
			}
		} else {
			$this->assertSame($expectedNames, $names);
		}
	}
	
/**/
	
	public function testCallsAtBuildingState_ElementIsFunction_ReturnsSpecsWithBeforeContextFunctions() {
		$specs = \spectrum\core\_private\convertArrayWithContextsToSpecs(array(
			function(){},
			function() use(&$isCalled) { $isCalled = true; },
			function() { \spectrum\core\constructs\data()->aaa = 'bbb'; },
		));
		
		$this->assertSame(3, count($specs));
		
		$contexts = $specs[0]->getContextModifiers()->getAll();
		$this->assertSame(1, count($contexts));
		$this->assertSame('before', $contexts[0]['type']);
		
		$contexts = $specs[1]->getContextModifiers()->getAll();
		$this->assertSame(1, count($contexts));
		$this->assertSame('before', $contexts[0]['type']);
		
		$contexts = $specs[2]->getContextModifiers()->getAll();
		$this->assertSame(1, count($contexts));
		$this->assertSame('before', $contexts[0]['type']);
		
		$this->assertSame(null, $isCalled);
		
		$dataObjects = array();
		foreach ($specs as $spec) {
			\spectrum\core\_private\getRootSpec()->bindChildSpec($spec);
			$spec->getExecutor()->setFunction(function() use(&$dataObjects, $spec) {
				$dataObjects[] = $spec->getData();
			});
		}
		
		\spectrum\core\_private\getRootSpec()->run();
		
		$this->assertSame(true, $isCalled);
		$this->assertSame(array(), get_object_vars($dataObjects[0]));
		$this->assertSame(array(), get_object_vars($dataObjects[1]));
		$this->assertSame(array('aaa' => 'bbb'), get_object_vars($dataObjects[2]));
	}
	
	public function providerElementIsFunction_KeysAsNames() {
		return array(
			array(array('aaa'), array('aaa' => function(){})),
			array(
				array('aaa', 'bbb', 'ccc'),
				array(
					'aaa' => function(){},
					'bbb' => function(){},
					'ccc' => function(){},
				)
			),
			
			array(array('AA bb CC'), array('AA bb CC' => function(){})),
			
			array(array(''), array('' => function(){})),
			array(array(0), array(0 => function(){})),
			array(array(1), array(1 => function(){})),
		);
	}
	
	/**
	 * @dataProvider providerElementIsFunction_KeysAsNames
	 */
	public function testCallsAtBuildingState_ElementIsFunction_SetsElementKeyAsSpecName($expectedNames, $contexts) {
		$names = array();
		foreach (\spectrum\core\_private\convertArrayWithContextsToSpecs($contexts) as $spec) {
			$names[] = $spec->getName();
		}
		
		$this->assertSame($expectedNames, $names);
	}
	
/**/
	
	public function testCallsAtBuildingState_ElementsAreArraysAndFunctions_ReturnsSpecsWithBeforeContextFunctions() {
		$specs = \spectrum\core\_private\convertArrayWithContextsToSpecs(array(
			array(),
			function() use(&$isCalled) { $isCalled = true; },
			array('bbb', 'ccc'),
			function(){},
			function(){ \spectrum\core\constructs\data()->aaa = 'bbb'; },
			array('aaa1', 'aaa2' => 'aaa3', 'aaa4' => 'aaa5'),
		));
		
		$this->assertSame(6, count($specs));
		
		$contexts = $specs[0]->getContextModifiers()->getAll();
		$this->assertSame(1, count($contexts));
		$this->assertSame('before', $contexts[0]['type']);
		
		$contexts = $specs[1]->getContextModifiers()->getAll();
		$this->assertSame(1, count($contexts));
		$this->assertSame('before', $contexts[0]['type']);
		
		$contexts = $specs[2]->getContextModifiers()->getAll();
		$this->assertSame(1, count($contexts));
		$this->assertSame('before', $contexts[0]['type']);
		
		$contexts = $specs[3]->getContextModifiers()->getAll();
		$this->assertSame(1, count($contexts));
		$this->assertSame('before', $contexts[0]['type']);
		
		$contexts = $specs[4]->getContextModifiers()->getAll();
		$this->assertSame(1, count($contexts));
		$this->assertSame('before', $contexts[0]['type']);
		
		$contexts = $specs[5]->getContextModifiers()->getAll();
		$this->assertSame(1, count($contexts));
		$this->assertSame('before', $contexts[0]['type']);
		
		$this->assertSame(null, $isCalled);
		
		$dataObjects = array();
		foreach ($specs as $spec) {
			\spectrum\core\_private\getRootSpec()->bindChildSpec($spec);
			$spec->getExecutor()->setFunction(function() use(&$dataObjects, $spec) {
				$dataObjects[] = $spec->getData();
			});
		}
		
		\spectrum\core\_private\getRootSpec()->run();
		
		$this->assertSame(true, $isCalled);
		$this->assertSame(array(), get_object_vars($dataObjects[0]));
		$this->assertSame(array(), get_object_vars($dataObjects[1]));
		$this->assertSame(array(0 => 'bbb', 1 => 'ccc'), get_object_vars($dataObjects[2]));
		$this->assertSame(array(), get_object_vars($dataObjects[3]));
		$this->assertSame(array('aaa' => 'bbb'), get_object_vars($dataObjects[4]));
		$this->assertSame(array(0 => 'aaa1', 'aaa2' => 'aaa3', 'aaa4' => 'aaa5'), get_object_vars($dataObjects[5]));
	}
	
/**/
	
	public function testCallsAtBuildingState_ElementIsNotArrayAndNotFunction_ThrowsException() {
		$this->assertThrowsException('\spectrum\core\Exception', 'The context row #2 should be an array', function() {
			\spectrum\core\_private\convertArrayWithContextsToSpecs(array(
				'some name 1' => array('a' => 'aaa'),
				'some name 2' => 'aaa',
			));
		});
	}
}