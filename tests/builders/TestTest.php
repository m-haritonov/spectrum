<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\builders;

use spectrum\core\Spec;

require_once __DIR__ . '/../init.php';

class TestTest extends \spectrum\tests\Test
{
	public function providerAllArgumentCombinations()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders();
	}

	public function providerAllArgumentCombinationsWithEmptyContextAndBodyFunctions()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders(null, function(){}, function(){});
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinations
	 */
	public function testCallsAtBuildingState_ReturnsNewTestSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		
		$testSpec1 = call_user_func_array('\spectrum\builders\test', $arguments);
		$this->assertInstanceOf('\spectrum\core\Spec', $testSpec1);
		$this->assertNotSame($parentSpec, $testSpec1);
		
		$testSpec2 = call_user_func_array('\spectrum\builders\test', $arguments);
		$this->assertInstanceOf('\spectrum\core\Spec', $testSpec2);
		$this->assertNotSame($parentSpec, $testSpec2);
		$this->assertNotSame($testSpec1, $testSpec2);
	}
	
	public function providerInputCharset()
	{
		return array(
			array(
				array('utf-8', 'utf-8', 'utf-8', 'utf-8', 'utf-8', 'utf-8', 'utf-8', 'utf-8', 'utf-8'),
				function(){
					\spectrum\tests\Test::$temp['specs'][0] = \spectrum\builders\test(null, null, function(){}, null);
					
					\spectrum\tests\Test::$temp['specs'][1] = \spectrum\builders\group(null, null, function(){
						\spectrum\tests\Test::$temp['specs'][2] = \spectrum\builders\test(null, null, function(){}, null);
						\spectrum\tests\Test::$temp['specs'][3] = \spectrum\builders\test(null, null, function(){}, null);
						\spectrum\tests\Test::$temp['specs'][4] = \spectrum\builders\test(null, null, function(){}, null);
						
						\spectrum\tests\Test::$temp['specs'][5] = \spectrum\builders\group(null, null, function(){
							\spectrum\tests\Test::$temp['specs'][6] = \spectrum\builders\test(null, null, function(){}, null);
							\spectrum\tests\Test::$temp['specs'][7] = \spectrum\builders\test(null, null, function(){}, null);
							\spectrum\tests\Test::$temp['specs'][8] = \spectrum\builders\test(null, null, function(){}, null);
						}, null);
					}, null);
				}
			),
			
			array(
				array('aaa0', 'aaa1', 'aaa2', 'aaa3', 'aaa4', 'aaa5', 'aaa6', 'aaa7', 'aaa8'),
				function(){
					\spectrum\tests\Test::$temp['specs'][0] = \spectrum\builders\test(null, null, function(){}, array('inputCharset' => 'aaa0'));
					
					\spectrum\tests\Test::$temp['specs'][1] = \spectrum\builders\group(null, null, function(){
						\spectrum\tests\Test::$temp['specs'][2] = \spectrum\builders\test(null, null, function(){}, array('inputCharset' => 'aaa2'));
						\spectrum\tests\Test::$temp['specs'][3] = \spectrum\builders\test(null, null, function(){}, array('inputCharset' => 'aaa3'));
						\spectrum\tests\Test::$temp['specs'][4] = \spectrum\builders\test(null, null, function(){}, array('inputCharset' => 'aaa4'));
						
						\spectrum\tests\Test::$temp['specs'][5] = \spectrum\builders\group(null, null, function(){
							\spectrum\tests\Test::$temp['specs'][6] = \spectrum\builders\test(null, null, function(){}, array('inputCharset' => 'aaa6'));
							\spectrum\tests\Test::$temp['specs'][7] = \spectrum\builders\test(null, null, function(){}, array('inputCharset' => 'aaa7'));
							\spectrum\tests\Test::$temp['specs'][8] = \spectrum\builders\test(null, null, function(){}, array('inputCharset' => 'aaa8'));
						}, array('inputCharset' => 'aaa5'));
					}, array('inputCharset' => 'aaa1'));
				}
			),
			
			array(
				array('aaa0', 'aaa0', 'aaa2', 'aaa2', 'aaa2', 'aaa2', 'aaa6', 'aaa6', 'aaa6', 'aaa6', 'aaa2', 'aaa2', 'aaa2', 'aaa2'),
				function(){
					\spectrum\tests\Test::$temp['specs'][0] = \spectrum\builders\group(null, null, function(){
						\spectrum\tests\Test::$temp['specs'][1] = \spectrum\builders\test(null, null, function(){}, null);
						
						\spectrum\tests\Test::$temp['specs'][2] = \spectrum\builders\group(null, null, function(){
							\spectrum\tests\Test::$temp['specs'][3] = \spectrum\builders\test(null, null, function(){}, null);
							\spectrum\tests\Test::$temp['specs'][4] = \spectrum\builders\test(null, null, function(){}, null);
							\spectrum\tests\Test::$temp['specs'][5] = \spectrum\builders\test(null, null, function(){}, null);
							
							\spectrum\tests\Test::$temp['specs'][6] = \spectrum\builders\group(null, null, function(){
								\spectrum\tests\Test::$temp['specs'][7] = \spectrum\builders\test(null, null, function(){}, null);
								\spectrum\tests\Test::$temp['specs'][8] = \spectrum\builders\test(null, null, function(){}, null);
								\spectrum\tests\Test::$temp['specs'][9] = \spectrum\builders\test(null, null, function(){}, null);
							}, array('inputCharset' => 'aaa6'));
							
							\spectrum\tests\Test::$temp['specs'][10] = \spectrum\builders\group(null, null, function(){
								\spectrum\tests\Test::$temp['specs'][11] = \spectrum\builders\test(null, null, function(){}, null);
								\spectrum\tests\Test::$temp['specs'][12] = \spectrum\builders\test(null, null, function(){}, null);
								\spectrum\tests\Test::$temp['specs'][13] = \spectrum\builders\test(null, null, function(){}, null);
							}, null);
						}, array('inputCharset' => 'aaa2'));
					}, array('inputCharset' => 'aaa0'));
				}
			),
			
			//
			
			array(
				array('utf-8', 'utf-8', 'utf-8'),
				function(){
					\spectrum\tests\Test::$temp['specs'][0] = \spectrum\builders\group(null, function(){
						\spectrum\tests\Test::$temp['specs'][1] = \spectrum\builders\group(null, null, function(){}, null);
					}, function(){
						\spectrum\tests\Test::$temp['specs'][2] = \spectrum\builders\test(null, null, function(){}, null);
					}, null);
				}
			),
			
			array(
				array('utf-8', 'utf-8', 'aaa2'),
				function(){
					\spectrum\tests\Test::$temp['specs'][0] = \spectrum\builders\group(null, function(){
						\spectrum\tests\Test::$temp['specs'][1] = \spectrum\builders\group(null, null, function(){}, null);
					}, function(){
						\spectrum\tests\Test::$temp['specs'][2] = \spectrum\builders\test(null, null, function(){}, array('inputCharset' => 'aaa2'));
					}, null);
				}
			),
			
			array(
				array('utf-8', 'aaa1', 'utf-8'),
				function(){
					\spectrum\tests\Test::$temp['specs'][0] = \spectrum\builders\group(null, function(){
						\spectrum\tests\Test::$temp['specs'][1] = \spectrum\builders\group(null, null, function(){}, array('inputCharset' => 'aaa1'));
					}, function(){
						\spectrum\tests\Test::$temp['specs'][2] = \spectrum\builders\test(null, null, function(){}, null);
					}, null);
				}
			),
			
			array(
				array('utf-8', 'aaa1', 'aaa2'),
				function(){
					\spectrum\tests\Test::$temp['specs'][0] = \spectrum\builders\group(null, function(){
						\spectrum\tests\Test::$temp['specs'][1] = \spectrum\builders\group(null, null, function(){}, array('inputCharset' => 'aaa1'));
					}, function(){
						\spectrum\tests\Test::$temp['specs'][2] = \spectrum\builders\test(null, null, function(){}, array('inputCharset' => 'aaa2'));
					}, null);
				}
			),
			
			array(
				array('aaa0', 'aaa0', 'aaa0'),
				function(){
					\spectrum\tests\Test::$temp['specs'][0] = \spectrum\builders\group(null, function(){
						\spectrum\tests\Test::$temp['specs'][1] = \spectrum\builders\group(null, null, function(){}, null);
					}, function(){
						\spectrum\tests\Test::$temp['specs'][2] = \spectrum\builders\test(null, null, function(){}, null);
					}, array('inputCharset' => 'aaa0'));
				}
			),
			
			array(
				array('aaa0', 'aaa0', 'aaa2'),
				function(){
					\spectrum\tests\Test::$temp['specs'][0] = \spectrum\builders\group(null, function(){
						\spectrum\tests\Test::$temp['specs'][1] = \spectrum\builders\group(null, null, function(){}, null);
					}, function(){
						\spectrum\tests\Test::$temp['specs'][2] = \spectrum\builders\test(null, null, function(){}, array('inputCharset' => 'aaa2'));
					}, array('inputCharset' => 'aaa0'));
				}
			),
			
			array(
				array('aaa0', 'aaa1', 'aaa0'),
				function(){
					\spectrum\tests\Test::$temp['specs'][0] = \spectrum\builders\group(null, function(){
						\spectrum\tests\Test::$temp['specs'][1] = \spectrum\builders\group(null, null, function(){}, array('inputCharset' => 'aaa1'));
					}, function(){
						\spectrum\tests\Test::$temp['specs'][2] = \spectrum\builders\test(null, null, function(){}, null);
					}, array('inputCharset' => 'aaa0'));
				}
			),
			
			array(
				array('aaa0', 'aaa1', 'aaa2'),
				function(){
					\spectrum\tests\Test::$temp['specs'][0] = \spectrum\builders\group(null, function(){
						\spectrum\tests\Test::$temp['specs'][1] = \spectrum\builders\group(null, null, function(){}, array('inputCharset' => 'aaa1'));
					}, function(){
						\spectrum\tests\Test::$temp['specs'][2] = \spectrum\builders\test(null, null, function(){}, array('inputCharset' => 'aaa2'));
					}, array('inputCharset' => 'aaa0'));
				}
			),
			
			array(
				array('aaa0', 'aaa0', 'aaa0', 'aaa0', 'aaa0', 'aaa0', 'aaa0', 'aaa0', 'bbb8', 'bbb9', 'bbb10'),
				function(){
					\spectrum\tests\Test::$temp['specs'][0] = \spectrum\builders\group(null, function(){
						\spectrum\tests\Test::$temp['specs'][1] = \spectrum\builders\group(null, null, function(){}, null);
						\spectrum\tests\Test::$temp['specs'][2] = \spectrum\builders\group(null, null, function(){
							\spectrum\tests\Test::$temp['specs'][3] = \spectrum\builders\group(null, null, function(){}, null);
							\spectrum\tests\Test::$temp['specs'][4] = \spectrum\builders\group(null, null, function(){}, null);
						}, null);
					}, function(){
						\spectrum\tests\Test::$temp['specs'][5] = \spectrum\builders\test(null, null, function(){}, null);
						
						\spectrum\tests\Test::$temp['specs'][6] = \spectrum\builders\group(null, null, function(){
							\spectrum\tests\Test::$temp['specs'][7] = \spectrum\builders\test(null, null, function(){}, null);
						}, null);
						
						\spectrum\tests\Test::$temp['specs'][8] = \spectrum\builders\test(null, null, function(){}, array('inputCharset' => 'bbb8'));
						
						\spectrum\tests\Test::$temp['specs'][9] = \spectrum\builders\group(null, null, function(){
							\spectrum\tests\Test::$temp['specs'][10] = \spectrum\builders\test(null, null, function(){}, array('inputCharset' => 'bbb10'));
						}, array('inputCharset' => 'bbb9'));
					}, array('inputCharset' => 'aaa0'));
				}
			),
			
			array(
				array('aaa0', 'aaa1', 'aaa2', 'aaa3', 'aaa4', 'aaa0', 'aaa0', 'aaa0', 'bbb8', 'bbb9', 'bbb10'),
				function(){
					\spectrum\tests\Test::$temp['specs'][0] = \spectrum\builders\group(null, function(){
						\spectrum\tests\Test::$temp['specs'][1] = \spectrum\builders\group(null, null, function(){}, array('inputCharset' => 'aaa1'));
						\spectrum\tests\Test::$temp['specs'][2] = \spectrum\builders\group(null, null, function(){
							\spectrum\tests\Test::$temp['specs'][3] = \spectrum\builders\group(null, null, function(){}, array('inputCharset' => 'aaa3'));
							\spectrum\tests\Test::$temp['specs'][4] = \spectrum\builders\group(null, null, function(){}, array('inputCharset' => 'aaa4'));
						}, array('inputCharset' => 'aaa2'));
					}, function(){
						\spectrum\tests\Test::$temp['specs'][5] = \spectrum\builders\test(null, null, function(){}, null);
						
						\spectrum\tests\Test::$temp['specs'][6] = \spectrum\builders\group(null, null, function(){
							\spectrum\tests\Test::$temp['specs'][7] = \spectrum\builders\test(null, null, function(){}, null);
						}, null);
						
						\spectrum\tests\Test::$temp['specs'][8] = \spectrum\builders\test(null, null, function(){}, array('inputCharset' => 'bbb8'));
						
						\spectrum\tests\Test::$temp['specs'][9] = \spectrum\builders\group(null, null, function(){
							\spectrum\tests\Test::$temp['specs'][10] = \spectrum\builders\test(null, null, function(){}, array('inputCharset' => 'bbb10'));
						}, array('inputCharset' => 'bbb9'));
					}, array('inputCharset' => 'aaa0'));
				}
			),
			
			//
			
			array(
				array('utf-8', 'utf-8', 'utf-8', 'utf-8'),
				function(){
					\spectrum\tests\Test::$temp['specs'][0] = \spectrum\builders\test(null, array(
						array('a' => 'a'),
						array('a' => 'b'),
						array('a' => 'c'),
					), function(){}, null);
					
					$specs = \spectrum\tests\Test::$temp['specs'][0]->getChildSpecs();
					\spectrum\tests\Test::$temp['specs'][1] = $specs[0];
					\spectrum\tests\Test::$temp['specs'][2] = $specs[1];
					\spectrum\tests\Test::$temp['specs'][3] = $specs[2];
				}
			),
			
			array(
				array('aaa1', 'aaa1', 'aaa1', 'aaa1'),
				function(){
					\spectrum\tests\Test::$temp['specs'][0] = \spectrum\builders\test(null, array(
						array('a' => 'a'),
						array('a' => 'b'),
						array('a' => 'c'),
					), function(){}, 'aaa1');
					
					$specs = \spectrum\tests\Test::$temp['specs'][0]->getChildSpecs();
					\spectrum\tests\Test::$temp['specs'][1] = $specs[0];
					\spectrum\tests\Test::$temp['specs'][2] = $specs[1];
					\spectrum\tests\Test::$temp['specs'][3] = $specs[2];
				}
			),
		);
	}
	
	/**
	 * @dataProvider providerInputCharset
	 */
	public function testCallsAtBuildingState_SetsProperInputCharsetToEachSpec($expectedCharsets, $function)
	{
		$function();
		
		$charsets = array();
		foreach (\spectrum\tests\Test::$temp['specs'] as $key => $spec)
			$charsets[$key] = $spec->getInputCharset();
		
		ksort($charsets);
		$this->assertSame($expectedCharsets, $charsets);
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinations
	 */
	public function testCallsAtBuildingState_RestoreBuildingSpecAfterCall($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		call_user_func_array('\spectrum\builders\test', $arguments);
		$this->assertSame($parentSpec, \spectrum\builders\internal\getBuildingSpec());
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinations
	 */
	public function testCallsAtBuildingState_BuildingSpecIsRoot_AddsTestSpecToRootSpec($arguments)
	{
		$testSpec = call_user_func_array('\spectrum\builders\test', $arguments);
		$this->assertSame(array($testSpec), \spectrum\builders\getRootSpec()->getChildSpecs());
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinations
	 */
	public function testCallsAtBuildingState_BuildingSpecIsNotRoot_AddsTestSpecToSpecifySpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\builders\test', $arguments);
		
		$this->assertSame(array($testSpec), $parentSpec->getChildSpecs());
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinations
	 */
	public function testCallsAtBuildingState_BuildingSpecIsNotRoot_DoesNotAddTestSpecToRootSpec($arguments)
	{
		\spectrum\builders\internal\setBuildingSpec(new Spec());
		call_user_func_array('\spectrum\builders\test', $arguments);
		
		$this->assertSame(array(), \spectrum\builders\getRootSpec()->getChildSpecs());
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinationsWithEmptyContextAndBodyFunctions
	 */
	public function testCallsAtBuildingState_BuildingSpecIsNotRoot_DoesNotAddTestSpecToSiblingTestSpecs($arguments)
	{
		\spectrum\builders\internal\setBuildingSpec(new Spec());
		$testSpec1 = call_user_func_array('\spectrum\builders\test', $arguments);
		$testSpec2 = call_user_func_array('\spectrum\builders\test', $arguments);
		$testSpec3 = call_user_func_array('\spectrum\builders\test', $arguments);
		
		$this->assertSame(array(), $testSpec1->getChildSpecs());
		$this->assertSame(array(), $testSpec2->getChildSpecs());
		$this->assertSame(array(), $testSpec3->getChildSpecs());
	}
	
	public function providerVariantsOfArguments_NameArgumentIsString()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders('aaa bbb');
	}

	/**
	 * @dataProvider providerVariantsOfArguments_NameArgumentIsString
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_NameArgumentIsString_SetsNameToTestSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\builders\test', $arguments);

		$this->assertSame(null, $parentSpec->getName());
		$this->assertSame('aaa bbb', $testSpec->getName());
	}
	
	public function providerVariantsOfArguments_ContextsArgumentIsArray()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders(null, array(
			'aaa' => array(),
			'bbb' => array(),
			'ccc' => array(),
		));
	}

	/**
	 * @dataProvider providerVariantsOfArguments_ContextsArgumentIsArray
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_ContextsArgumentIsArray_AddsContextSpecsToTestSpecAsChildSpecs($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\builders\test', $arguments);
		
		$this->assertSame(array($testSpec), $parentSpec->getChildSpecs());
		
		$contextSpecs = $testSpec->getChildSpecs();
		$this->assertSame(3, count($contextSpecs));
		$this->assertSame('aaa', $contextSpecs[0]->getName());
		$this->assertSame('bbb', $contextSpecs[1]->getName());
		$this->assertSame('ccc', $contextSpecs[2]->getName());
		
		$this->assertSame(array(), $contextSpecs[0]->getChildSpecs());
		$this->assertSame(array(), $contextSpecs[1]->getChildSpecs());
		$this->assertSame(array(), $contextSpecs[2]->getChildSpecs());
	}
	
	public function providerVariantsOfArguments_ContextsArgumentIsFunction()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders(null, function(){
			\spectrum\builders\group('aaa', null, function(){
				\spectrum\builders\group('bbb', null, function(){}, null);
				\spectrum\builders\group('ccc', null, function(){}, null);
			}, null);
			\spectrum\builders\group('ddd', null, function(){}, null);
			\spectrum\builders\group('eee', null, function(){}, null);
		});
	}

	/**
	 * @dataProvider providerVariantsOfArguments_ContextsArgumentIsFunction
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_ContextsArgumentIsFunction_AddsContextSpecsToTestSpecAsChildSpecs($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\builders\test', $arguments);
		
		$this->assertSame(array($testSpec), $parentSpec->getChildSpecs());
		
		$contextSpecs = $testSpec->getChildSpecs();
		$this->assertSame(3, count($contextSpecs));
		$this->assertSame('aaa', $contextSpecs[0]->getName());
		$this->assertSame('ddd', $contextSpecs[1]->getName());
		$this->assertSame('eee', $contextSpecs[2]->getName());
		
		$specs = $contextSpecs[0]->getChildSpecs();
		$this->assertSame(2, count($specs));
		$this->assertSame('bbb', $specs[0]->getName());
		$this->assertSame('ccc', $specs[1]->getName());
		$this->assertSame(array(), $specs[0]->getChildSpecs());
		$this->assertSame(array(), $specs[1]->getChildSpecs());
		
		$this->assertSame(array(), $contextSpecs[1]->getChildSpecs());
		$this->assertSame(array(), $contextSpecs[2]->getChildSpecs());
	}
	
	public function providerVariantsOfArguments_ContextsArgumentIsFunction2()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders(null, function(){
			\spectrum\builders\test('aaa', null, function(){ return 'zzz'; }, null);
		});
	}

	/**
	 * @dataProvider providerVariantsOfArguments_ContextsArgumentIsFunction2
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_ContextsArgumentIsFunction_AddsTestContextSpecToTestSpecWithOwnBodyFunction($arguments)
	{
		\spectrum\builders\internal\setBuildingSpec(new Spec());
		$testSpec = call_user_func_array('\spectrum\builders\test', $arguments);
		
		$contextSpecs = $testSpec->getChildSpecs();
		$this->assertSame(1, count($contextSpecs));
		$this->assertSame('aaa', $contextSpecs[0]->getName());
		$this->assertSame(array(), $contextSpecs[0]->getChildSpecs());
		
		$bodyFunction = $contextSpecs[0]->test->getFunction();
		$this->assertNotSame($testSpec->test->getFunction(), $bodyFunction);
		$this->assertSame('zzz', $bodyFunction());
	}
	
	public function providerVariantsOfArguments_BodyArgumentIsFunction()
	{
		$function = function() use(&$function){ return $function; };
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders(null, null, $function);
	}

	/**
	 * @dataProvider providerVariantsOfArguments_BodyArgumentIsFunction
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_BodyArgumentIsFunction_AddsBodyFunctionToTestPlugin($arguments)
	{
		$testSpec = call_user_func_array('\spectrum\builders\test', $arguments);
		$this->assertInstanceOf('\Closure', $testSpec->test->getFunction());
		
		$function = $testSpec->test->getFunction();
		$this->assertSame($function(), $function);
	}
	
	public function providerVariantsOfArguments_BodyArgumentIsFunction2()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders(null, null, function(){
			\spectrum\tests\Test::$temp['isCalled'] = true;
		});
	}

	/**
	 * @dataProvider providerVariantsOfArguments_BodyArgumentIsFunction2
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_BodyArgumentIsFunction_DoesNotCallBodyFunction($arguments)
	{
		\spectrum\tests\Test::$temp['isCalled'] = false;
		call_user_func_array('\spectrum\builders\test', $arguments);
		$this->assertSame(false, \spectrum\tests\Test::$temp['isCalled']);
	}
	
	public function providerVariantsOfArguments_SettingsArgumentIsString()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders(null, null, null, 'koi8-r');
	}

	/**
	 * @dataProvider providerVariantsOfArguments_SettingsArgumentIsString
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_SettingsArgumentIsString_SetsInputCharsetToTestSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\builders\test', $arguments);

		$this->assertNotSame('koi8-r', mb_strtolower($parentSpec->getInputCharset()));
		$this->assertSame('koi8-r', mb_strtolower($testSpec->getInputCharset()));
	}
	
	public function providerVariantsOfArguments_SettingsArgumentIsInteger()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders(null, null, null, 8);
	}

	/**
	 * @dataProvider providerVariantsOfArguments_SettingsArgumentIsInteger
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_SettingsArgumentIsInteger_SetsErrorHandlingLevelToTestSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\builders\test', $arguments);

		$this->assertNotSame(8, $parentSpec->errorHandling->getCatchPhpErrors());
		$this->assertSame(8, $testSpec->errorHandling->getCatchPhpErrors());
	}
	
	public function providerVariantsOfArguments_SettingsArgumentIsTrue()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders(null, null, null, true);
	}

	/**
	 * @dataProvider providerVariantsOfArguments_SettingsArgumentIsTrue
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_SettingsArgumentIsTrue_SetsErrorHandlingLevelToTestSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\builders\test', $arguments);

		$this->assertNotSame(-1, $parentSpec->errorHandling->getCatchPhpErrors());
		$this->assertSame(-1, $testSpec->errorHandling->getCatchPhpErrors());
	}
	
	public function providerVariantsOfArguments_SettingsArgumentIsFalse()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders(null, null, null, false);
	}

	/**
	 * @dataProvider providerVariantsOfArguments_SettingsArgumentIsFalse
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_SettingsArgumentIsFalse_SetsErrorHandlingLevelToTestSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\builders\test', $arguments);

		$this->assertNotSame(0, $parentSpec->errorHandling->getCatchPhpErrors());
		$this->assertSame(0, $testSpec->errorHandling->getCatchPhpErrors());
	}
	
	public function providerVariantsOfArguments_SettingsArgumentIsArray()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders(null, null, null, array(
			'catchPhpErrors' => 8,
			'breakOnFirstPhpError' => true,
			'breakOnFirstMatcherFail' => true,
			'inputCharset' => 'koi8-r',
		));
	}

	/**
	 * @dataProvider providerVariantsOfArguments_SettingsArgumentIsArray
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_SettingsArgumentIsArray_SetsSettingsToTestSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\builders\test', $arguments);

		$this->assertNotSame(8, $parentSpec->errorHandling->getCatchPhpErrors());
		$this->assertNotSame(true, $parentSpec->errorHandling->getBreakOnFirstPhpError());
		$this->assertNotSame(true, $parentSpec->errorHandling->getBreakOnFirstMatcherFail());
		$this->assertNotSame('koi8-r', $parentSpec->getInputCharset());
		
		$this->assertSame(8, $testSpec->errorHandling->getCatchPhpErrors());
		$this->assertSame(true, $testSpec->errorHandling->getBreakOnFirstPhpError());
		$this->assertSame(true, $testSpec->errorHandling->getBreakOnFirstMatcherFail());
		$this->assertSame('koi8-r', $testSpec->getInputCharset());
	}
	
/**/
	
	public function testCallsAtBuildingState_BadArgumentsPassed_ThrowsException()
	{
		$this->assertThrowsException('\spectrum\builders\Exception', 'Incorrect arguments in "test" builder', function(){
			\spectrum\builders\test(null, null, function(){}, null, null, null, null);
		});
	}
	
/**/
	
	public function testCallsAtRunningState_ThrowsException()
	{
		\spectrum\tests\Test::$temp["exception"] = null;
		
		$this->registerPluginWithCodeInEvent('
			try
			{
				\spectrum\builders\test();
			}
			catch (\Exception $e)
			{
				\spectrum\tests\Test::$temp["exception"] = $e;
			}
		', 'onEndingSpecExecute');
		
		\spectrum\builders\getRootSpec()->run();
		
		$this->assertInstanceOf('\spectrum\builders\Exception', \spectrum\tests\Test::$temp["exception"]);
		$this->assertSame('Builder "test" should be call only at building state', \spectrum\tests\Test::$temp["exception"]->getMessage());
	}
}