<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\builders;

use spectrum\config;
use spectrum\core\Spec;

require_once __DIR__ . '/../init.php';

class GroupTest extends \spectrum\tests\Test
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
	public function testCallsAtBuildingState_ReturnsNewGroupSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		
		$groupSpec1 = call_user_func_array('\spectrum\builders\group', $arguments);
		$this->assertInstanceOf('\spectrum\core\Spec', $groupSpec1);
		$this->assertNotSame($parentSpec, $groupSpec1);
		
		$groupSpec2 = call_user_func_array('\spectrum\builders\group', $arguments);
		$this->assertInstanceOf('\spectrum\core\Spec', $groupSpec2);
		$this->assertNotSame($parentSpec, $groupSpec2);
		$this->assertNotSame($groupSpec1, $groupSpec2);
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinations
	 */
	public function testCallsAtBuildingState_RestoreBuildingSpecAfterCall($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		call_user_func_array('\spectrum\builders\group', $arguments);
		$this->assertSame($parentSpec, \spectrum\builders\internal\getBuildingSpec());
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinations
	 */
	public function testCallsAtBuildingState_BuildingSpecIsCustom_AddsGroupSpecToBuildingSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		$groupSpec = call_user_func_array('\spectrum\builders\group', $arguments);
		
		$this->assertSame(array($groupSpec), $parentSpec->getChildSpecs());
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinationsWithEmptyContextAndBodyFunctions
	 */
	public function testCallsAtBuildingState_BuildingSpecIsCustom_DoesNotAddGroupSpecToSiblingGroupSpecs($arguments)
	{
		\spectrum\builders\internal\setBuildingSpec(new Spec());
		$groupSpec1 = call_user_func_array('\spectrum\builders\group', $arguments);
		$groupSpec2 = call_user_func_array('\spectrum\builders\group', $arguments);
		$groupSpec3 = call_user_func_array('\spectrum\builders\group', $arguments);
		
		$this->assertSame(array(), $groupSpec1->getChildSpecs());
		$this->assertSame(array(), $groupSpec2->getChildSpecs());
		$this->assertSame(array(), $groupSpec3->getChildSpecs());
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinations
	 */
	public function testCallsAtBuildingState_BuildingSpecIsRoot_AddsGroupSpecToRootSpec($arguments)
	{
		$groupSpec = call_user_func_array('\spectrum\builders\group', $arguments);
		$this->assertSame(array($groupSpec), \spectrum\builders\getRootSpec()->getChildSpecs());
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinations
	 */
	public function testCallsAtBuildingState_BuildingSpecIsNotRoot_DoesNotAddGroupSpecToRootSpec($arguments)
	{
		\spectrum\builders\internal\setBuildingSpec(new Spec());
		call_user_func_array('\spectrum\builders\group', $arguments);
		
		$this->assertSame(array(), \spectrum\builders\getRootSpec()->getChildSpecs());
	}
	
	public function providerNameIsString()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders('aaa bbb');
	}

	/**
	 * @dataProvider providerNameIsString
	 */
	public function testCallsAtBuildingState_NameIsString_SetsNameToGroupSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		$groupSpec = call_user_func_array('\spectrum\builders\group', $arguments);
		
		$this->assertSame('aaa bbb', $groupSpec->getName());
		$this->assertSame(null, $parentSpec->getName());
	}
	
	public function providerContextsIsArray()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders(null, array(
			'aaa' => array(),
			'bbb' => array(),
			'ccc' => array(),
		), function(){
			\spectrum\tests\Test::$temp[] = \spectrum\builders\group(null, null, function(){}, null);
			\spectrum\tests\Test::$temp[] = \spectrum\builders\test(null, null, function(){}, null);
		});
	}

	/**
	 * @dataProvider providerContextsIsArray
	 */
	public function testCallsAtBuildingState_ContextsIsArray_AddsContextSpecsBetweenGroupSpecAndBodySpecs($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		
		\spectrum\tests\Test::$temp = array();
		$groupSpec = call_user_func_array('\spectrum\builders\group', $arguments);
		
		$this->assertSame(array($groupSpec), $parentSpec->getChildSpecs());
		
		$contextSpecs = $groupSpec->getChildSpecs();
		$this->assertSame(3, count($contextSpecs));
		$this->assertSame('aaa', $contextSpecs[0]->getName());
		$this->assertSame('bbb', $contextSpecs[1]->getName());
		$this->assertSame('ccc', $contextSpecs[2]->getName());
		
		$contextEndingSpecs = array_merge(
			$contextSpecs[0]->getChildSpecs(),
			$contextSpecs[1]->getChildSpecs(),
			$contextSpecs[2]->getChildSpecs()
		);
		
		$this->assertSame(3, count($contextEndingSpecs));
		
		$this->assertSame($contextEndingSpecs[0], $contextEndingSpecs[1]);
		$this->assertSame($contextEndingSpecs[1], $contextEndingSpecs[2]);
		$this->assertSame($contextEndingSpecs[2], $contextEndingSpecs[0]);
		
		$this->assertSame(\spectrum\tests\Test::$temp, $contextEndingSpecs[0]->getChildSpecs());
	}
	
	public function providerContextsIsFunction()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders(null, function(){
			\spectrum\builders\group('aaa', null, function(){}, null);
			\spectrum\builders\group('bbb', null, function(){}, null);
			\spectrum\builders\group('ccc', null, function(){}, null);
		}, function(){
			\spectrum\tests\Test::$temp[] = \spectrum\builders\group(null, null, function(){}, null);
			\spectrum\tests\Test::$temp[] = \spectrum\builders\test(null, null, function(){}, null);
		});
	}

	/**
	 * @dataProvider providerContextsIsFunction
	 */
	public function testCallsAtBuildingState_ContextsIsFunction_AddsContextSpecsBetweenGroupSpecAndBodySpecs($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		
		\spectrum\tests\Test::$temp = array();
		$groupSpec = call_user_func_array('\spectrum\builders\group', $arguments);
		
		$this->assertSame(array($groupSpec), $parentSpec->getChildSpecs());
		
		$contextSpecs = $groupSpec->getChildSpecs();
		$this->assertSame(3, count($contextSpecs));
		$this->assertSame('aaa', $contextSpecs[0]->getName());
		$this->assertSame('bbb', $contextSpecs[1]->getName());
		$this->assertSame('ccc', $contextSpecs[2]->getName());
		
		$contextEndingSpecs = array_merge(
			$contextSpecs[0]->getChildSpecs(),
			$contextSpecs[1]->getChildSpecs(),
			$contextSpecs[2]->getChildSpecs()
		);
		
		$this->assertSame(3, count($contextEndingSpecs));
		
		$this->assertSame($contextEndingSpecs[0], $contextEndingSpecs[1]);
		$this->assertSame($contextEndingSpecs[1], $contextEndingSpecs[2]);
		$this->assertSame($contextEndingSpecs[2], $contextEndingSpecs[0]);
		
		$this->assertSame(\spectrum\tests\Test::$temp, $contextEndingSpecs[0]->getChildSpecs());
	}
	
	public function providerContextsIsFunction2()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders(null, function(){
			\spectrum\builders\group('aaa', null, function(){
				\spectrum\builders\group('bbb', null, function(){}, null);
				\spectrum\builders\group('ccc', null, function(){}, null);
			}, null);
			\spectrum\builders\group('ddd', null, function(){}, null);
		}, function(){
			\spectrum\tests\Test::$temp[] = \spectrum\builders\group(null, null, function(){}, null);
			\spectrum\tests\Test::$temp[] = \spectrum\builders\test(null, null, function(){}, null);
		});
	}

	/**
	 * @dataProvider providerContextsIsFunction2
	 */
	public function testCallsAtBuildingState_ContextsIsFunction_AddsBodySpecsToEndingContextSpecsOnly($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		
		\spectrum\tests\Test::$temp = array();
		$groupSpec = call_user_func_array('\spectrum\builders\group', $arguments);
		
		$this->assertSame(array($groupSpec), $parentSpec->getChildSpecs());
		
		$contextSpecsLevel1 = $groupSpec->getChildSpecs();
		$this->assertSame(2, count($contextSpecsLevel1));
		$this->assertSame('aaa', $contextSpecsLevel1[0]->getName());
		$this->assertSame('ddd', $contextSpecsLevel1[1]->getName());
		
		$contextSpecsLevel2 = $contextSpecsLevel1[0]->getChildSpecs();
		$this->assertSame(2, count($contextSpecsLevel2));
		$this->assertSame('bbb', $contextSpecsLevel2[0]->getName());
		$this->assertSame('ccc', $contextSpecsLevel2[1]->getName());
		
		$contextEndingSpecs = array_merge(
			$contextSpecsLevel2[0]->getChildSpecs(),
			$contextSpecsLevel2[1]->getChildSpecs(),
			$contextSpecsLevel1[1]->getChildSpecs()
		);
		
		$this->assertSame(3, count($contextEndingSpecs));
		
		$this->assertSame($contextEndingSpecs[0], $contextEndingSpecs[1]);
		$this->assertSame($contextEndingSpecs[1], $contextEndingSpecs[2]);
		$this->assertSame($contextEndingSpecs[2], $contextEndingSpecs[0]);
		
		$this->assertSame(\spectrum\tests\Test::$temp, $contextEndingSpecs[0]->getChildSpecs());
	}
	
	public function providerContextsIsFunction3()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders(null, function(){
			\spectrum\builders\group('aaa', null, function(){}, null);
			\spectrum\builders\group('bbb', null, function(){}, null);
			\spectrum\builders\test('ccc', null, function(){}, null);
		}, function(){
			\spectrum\tests\Test::$temp[] = \spectrum\builders\group(null, null, function(){}, null);
			\spectrum\tests\Test::$temp[] = \spectrum\builders\test(null, null, function(){}, null);
		});
	}

	/**
	 * @dataProvider providerContextsIsFunction3
	 */
	public function testCallsAtBuildingState_ContextsIsFunction_DoesNotAddAnySpecsToTestContextSpecs($arguments)
	{
		\spectrum\builders\internal\setBuildingSpec(new Spec());
		
		\spectrum\tests\Test::$temp = array();
		$groupSpec = call_user_func_array('\spectrum\builders\group', $arguments);
		
		$contextSpecs = $groupSpec->getChildSpecs();
		$this->assertSame(3, count($contextSpecs));
		$this->assertSame('aaa', $contextSpecs[0]->getName());
		$this->assertSame('bbb', $contextSpecs[1]->getName());
		$this->assertSame('ccc', $contextSpecs[2]->getName());
		
		$this->assertSame(0, count($contextSpecs[2]->getChildSpecs()));
		
		$contextEndingSpecs = array_merge(
			$contextSpecs[0]->getChildSpecs(),
			$contextSpecs[1]->getChildSpecs()
		);
		
		$this->assertSame(2, count($contextEndingSpecs));
		$this->assertSame($contextEndingSpecs[0], $contextEndingSpecs[1]);
		$this->assertSame(\spectrum\tests\Test::$temp, $contextEndingSpecs[0]->getChildSpecs());
	}
		
	public function providerBodyIsFunction()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders(null, array(), function(){
			\spectrum\tests\Test::$temp[] = \spectrum\builders\group('aaa', null, function(){}, null);
			\spectrum\tests\Test::$temp[] = \spectrum\builders\group('bbb', null, function(){}, null);
			\spectrum\tests\Test::$temp[] = \spectrum\builders\test('ccc', null, function(){}, null);
			\spectrum\tests\Test::$temp[] = \spectrum\builders\test('ddd', null, function(){}, null);
			\spectrum\tests\Test::$temp[] = \spectrum\builders\group('eee', null, function(){}, null);
		});
	}

	/**
	 * @dataProvider providerBodyIsFunction
	 */
	public function testCallsAtBuildingState_BodyIsFunction_AddsBodySpecsToGroupSpec($arguments)
	{
		\spectrum\tests\Test::$temp = array();
		$groupSpec = call_user_func_array('\spectrum\builders\group', $arguments);

		$this->assertSame(\spectrum\tests\Test::$temp, $groupSpec->getChildSpecs());
		
		$specs = $groupSpec->getChildSpecs();
		$this->assertSame(5, count($specs));
		$this->assertSame('aaa', $specs[0]->getName());
		$this->assertSame('bbb', $specs[1]->getName());
		$this->assertSame('ccc', $specs[2]->getName());
		$this->assertSame('ddd', $specs[3]->getName());
		$this->assertSame('eee', $specs[4]->getName());
		
		$this->assertSame(array(), $specs[0]->getChildSpecs());
		$this->assertSame(array(), $specs[1]->getChildSpecs());
		$this->assertSame(array(), $specs[2]->getChildSpecs());
		$this->assertSame(array(), $specs[3]->getChildSpecs());
		$this->assertSame(array(), $specs[4]->getChildSpecs());
	}
	
	public function providerBodyIsFunction2()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders('aaa', array(), function(){
			\spectrum\builders\group('bbb', null, function(){
				\spectrum\builders\test('ccc', null, function(){}, null);
			}, null);
			
			\spectrum\builders\group('ddd', null, function(){
				\spectrum\builders\group('eee', null, function(){
					\spectrum\builders\test('fff', null, function(){}, null);
				}, null);
				
				\spectrum\builders\group('ggg', null, function(){
					\spectrum\builders\test('hhh', null, function(){}, null);
					\spectrum\builders\test('iii', null, function(){}, null);
				}, null);
			}, null);
		});
	}

	/**
	 * @dataProvider providerBodyIsFunction2
	 */
	public function testCallsAtBuildingState_BodyIsFunction_AddsDescendantSpecsOfBodySpecsToHisParents($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		$groupSpec = call_user_func_array('\spectrum\builders\group', $arguments);

		$this->assertSame(array($groupSpec), $parentSpec->getChildSpecs());
		$this->assertSame('aaa', $groupSpec->getName());
		
		$level1Specs = $groupSpec->getChildSpecs();
		$this->assertSame(2, count($level1Specs));
		$this->assertSame('bbb', $level1Specs[0]->getName());
		$this->assertSame('ddd', $level1Specs[1]->getName());
		
		$level2Specs = $level1Specs[0]->getChildSpecs();
		$this->assertSame(1, count($level2Specs));
		$this->assertSame('ccc', $level2Specs[0]->getName());
		$this->assertSame(0, count($level2Specs[0]->getChildSpecs()));
		
		$level2Specs = $level1Specs[1]->getChildSpecs();
		$this->assertSame(2, count($level2Specs));
		$this->assertSame('eee', $level2Specs[0]->getName());
		$this->assertSame('ggg', $level2Specs[1]->getName());
		
		$level3Specs = $level2Specs[0]->getChildSpecs();
		$this->assertSame(1, count($level3Specs));
		$this->assertSame('fff', $level3Specs[0]->getName());
		$this->assertSame(0, count($level3Specs[0]->getChildSpecs()));
		
		$level3Specs = $level2Specs[1]->getChildSpecs();
		$this->assertSame(2, count($level3Specs));
		$this->assertSame('hhh', $level3Specs[0]->getName());
		$this->assertSame('iii', $level3Specs[1]->getName());
		$this->assertSame(0, count($level3Specs[0]->getChildSpecs()));
		$this->assertSame(0, count($level3Specs[1]->getChildSpecs()));
	}
	
	public function providerSettingsIsString()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders(null, null, function(){
			\spectrum\tests\Test::$temp[] = \spectrum\builders\group('aaa', null, function(){}, null);
			\spectrum\tests\Test::$temp[] = \spectrum\builders\test('bbb', null, function(){}, null);
		}, 'koi8-r');
	}

	/**
	 * @dataProvider providerSettingsIsString
	 */
	public function testCallsAtBuildingState_SettingsIsString_SetsInputCharsetToGroupSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		
		\spectrum\tests\Test::$temp = array();
		$groupSpec = call_user_func_array('\spectrum\builders\group', $arguments);

		$this->assertSame('koi8-r', mb_strtolower($groupSpec->charset->getInputCharset()));
		$this->assertNotSame('koi8-r', mb_strtolower($parentSpec->charset->getInputCharset()));
		$this->assertNotSame('koi8-r', mb_strtolower(\spectrum\tests\Test::$temp[0]->charset->getInputCharset()));
		$this->assertNotSame('koi8-r', mb_strtolower(\spectrum\tests\Test::$temp[1]->charset->getInputCharset()));
	}
	
	public function providerSettingsIsInteger()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders(null, null, function(){
			\spectrum\tests\Test::$temp[] = \spectrum\builders\group('aaa', null, function(){}, null);
			\spectrum\tests\Test::$temp[] = \spectrum\builders\test('bbb', null, function(){}, null);
		}, 8);
	}

	/**
	 * @dataProvider providerSettingsIsInteger
	 */
	public function testCallsAtBuildingState_SettingsIsInteger_SetsErrorHandlingLevelToGroupSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		
		\spectrum\tests\Test::$temp = array();
		$groupSpec = call_user_func_array('\spectrum\builders\group', $arguments);

		$this->assertSame(8, $groupSpec->errorHandling->getCatchPhpErrors());
		$this->assertNotSame(8, $parentSpec->errorHandling->getCatchPhpErrors());
		$this->assertNotSame(8, \spectrum\tests\Test::$temp[0]->errorHandling->getCatchPhpErrors());
		$this->assertNotSame(8, \spectrum\tests\Test::$temp[1]->errorHandling->getCatchPhpErrors());
	}
	
	public function providerSettingsIsTrue()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders(null, null, function(){
			\spectrum\tests\Test::$temp[] = \spectrum\builders\group('aaa', null, function(){}, null);
			\spectrum\tests\Test::$temp[] = \spectrum\builders\test('bbb', null, function(){}, null);
		}, true);
	}

	/**
	 * @dataProvider providerSettingsIsTrue
	 */
	public function testCallsAtBuildingState_SettingsIsTrue_SetsErrorHandlingLevelToGroupSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		
		\spectrum\tests\Test::$temp = array();
		$groupSpec = call_user_func_array('\spectrum\builders\group', $arguments);

		$this->assertSame(-1, $groupSpec->errorHandling->getCatchPhpErrors());
		$this->assertNotSame(-1, $parentSpec->errorHandling->getCatchPhpErrors());
		$this->assertNotSame(-1, \spectrum\tests\Test::$temp[0]->errorHandling->getCatchPhpErrors());
		$this->assertNotSame(-1, \spectrum\tests\Test::$temp[1]->errorHandling->getCatchPhpErrors());
	}
	
	public function providerSettingsIsFalse()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders(null, null, function(){
			\spectrum\tests\Test::$temp[] = \spectrum\builders\group('aaa', null, function(){}, null);
			\spectrum\tests\Test::$temp[] = \spectrum\builders\test('bbb', null, function(){}, null);
		}, false);
	}

	/**
	 * @dataProvider providerSettingsIsFalse
	 */
	public function testCallsAtBuildingState_SettingsIsFalse_SetsErrorHandlingLevelToGroupSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		
		\spectrum\tests\Test::$temp = array();
		$groupSpec = call_user_func_array('\spectrum\builders\group', $arguments);

		$this->assertSame(0, $groupSpec->errorHandling->getCatchPhpErrors());
		$this->assertNotSame(0, $parentSpec->errorHandling->getCatchPhpErrors());
		$this->assertNotSame(0, \spectrum\tests\Test::$temp[0]->errorHandling->getCatchPhpErrors());
		$this->assertNotSame(0, \spectrum\tests\Test::$temp[1]->errorHandling->getCatchPhpErrors());
	}
	
	public function providerSettingsIsArray()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders(null, null, function(){
			\spectrum\tests\Test::$temp[] = \spectrum\builders\group('aaa', null, function(){}, null);
			\spectrum\tests\Test::$temp[] = \spectrum\builders\test('bbb', null, function(){}, null);
		}, array(
			'catchPhpErrors' => 8,
			'breakOnFirstPhpError' => true,
			'breakOnFirstMatcherFail' => true,
			'inputCharset' => 'koi8-r',
		));
	}

	/**
	 * @dataProvider providerSettingsIsArray
	 */
	public function testCallsAtBuildingState_SettingsIsArray_SetsSettingsToGroupSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		
		\spectrum\tests\Test::$temp = array();
		$groupSpec = call_user_func_array('\spectrum\builders\group', $arguments);

		$this->assertSame(8, $groupSpec->errorHandling->getCatchPhpErrors());
		$this->assertSame(true, $groupSpec->errorHandling->getBreakOnFirstPhpError());
		$this->assertSame(true, $groupSpec->errorHandling->getBreakOnFirstMatcherFail());
		$this->assertSame('koi8-r', $groupSpec->charset->getInputCharset());
		
		$this->assertNotSame(8, $parentSpec->errorHandling->getCatchPhpErrors());
		$this->assertNotSame(true, $parentSpec->errorHandling->getBreakOnFirstPhpError());
		$this->assertNotSame(true, $parentSpec->errorHandling->getBreakOnFirstMatcherFail());
		$this->assertNotSame('koi8-r', $parentSpec->charset->getInputCharset());
		
		$this->assertNotSame(8, \spectrum\tests\Test::$temp[0]->errorHandling->getCatchPhpErrors());
		$this->assertNotSame(true, \spectrum\tests\Test::$temp[0]->errorHandling->getBreakOnFirstPhpError());
		$this->assertNotSame(true, \spectrum\tests\Test::$temp[0]->errorHandling->getBreakOnFirstMatcherFail());
		$this->assertNotSame('koi8-r', \spectrum\tests\Test::$temp[0]->charset->getInputCharset());
		
		$this->assertNotSame(8, \spectrum\tests\Test::$temp[1]->errorHandling->getCatchPhpErrors());
		$this->assertNotSame(true, \spectrum\tests\Test::$temp[1]->errorHandling->getBreakOnFirstPhpError());
		$this->assertNotSame(true, \spectrum\tests\Test::$temp[1]->errorHandling->getBreakOnFirstMatcherFail());
		$this->assertNotSame('koi8-r', \spectrum\tests\Test::$temp[1]->charset->getInputCharset());
	}
	
/**/
	
	public function testCallsAtBuildingState_BadArgumentsPassed_ThrowsException()
	{
		$this->assertThrowsException('\spectrum\builders\Exception', 'Incorrect arguments in "group" builder', function(){
			\spectrum\builders\group(null, null, function(){}, null, null, null, null);
		});
	}
	
/**/
	
	public function testCallsAtRunningState_ThrowsException()
	{
		\spectrum\tests\Test::$temp["exception"] = null;
		
		$this->registerPluginWithCodeInEvent('
			try
			{
				\spectrum\builders\group();
			}
			catch (\Exception $e)
			{
				\spectrum\tests\Test::$temp["exception"] = $e;
			}
		', 'onEndingSpecExecute');
		
		\spectrum\builders\getRootSpec()->run();
		
		$this->assertInstanceOf('\spectrum\builders\Exception', \spectrum\tests\Test::$temp["exception"]);
		$this->assertSame('Builder "group" should be call only at building state', \spectrum\tests\Test::$temp["exception"]->getMessage());
	}
}