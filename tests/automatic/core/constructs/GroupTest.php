<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core\constructs;

use spectrum\core\models\Spec;

require_once __DIR__ . '/../../../init.php';

class GroupTest extends \spectrum\tests\automatic\Test {
	public function providerAllArgumentCombinations() {
		return $this->getProviderWithCorrectArgumentsForGroupAndTestConstructs();
	}
	
	public function providerAllArgumentCombinationsWithEmptyContextAndBodyFunctions() {
		return $this->getProviderWithCorrectArgumentsForGroupAndTestConstructs(null, function(){}, function(){});
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinations
	 */
	public function testCallsAtBuildingState_ReturnsNewGroupSpec($arguments) {
		$parentSpec = new Spec();
		\spectrum\core\_private\setCurrentBuildingSpec($parentSpec);
		
		$groupSpec1 = call_user_func_array('\spectrum\core\constructs\group', $arguments);
		$this->assertInstanceOf('\spectrum\core\models\Spec', $groupSpec1);
		$this->assertNotSame($parentSpec, $groupSpec1);
		
		$groupSpec2 = call_user_func_array('\spectrum\core\constructs\group', $arguments);
		$this->assertInstanceOf('\spectrum\core\models\Spec', $groupSpec2);
		$this->assertNotSame($parentSpec, $groupSpec2);
		$this->assertNotSame($groupSpec1, $groupSpec2);
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinations
	 */
	public function testCallsAtBuildingState_RestoreBuildingSpecAfterCall($arguments) {
		$parentSpec = new Spec();
		\spectrum\core\_private\setCurrentBuildingSpec($parentSpec);
		call_user_func_array('\spectrum\core\constructs\group', $arguments);
		$this->assertSame($parentSpec, \spectrum\core\_private\getCurrentBuildingSpec());
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinations
	 */
	public function testCallsAtBuildingState_BuildingSpecIsRoot_AddsGroupSpecToRootSpec($arguments) {
		$groupSpec = call_user_func_array('\spectrum\core\constructs\group', $arguments);
		$this->assertSame(array($groupSpec), \spectrum\core\_private\getRootSpec()->getChildSpecs());
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinations
	 */
	public function testCallsAtBuildingState_BuildingSpecIsNotRoot_AddsGroupSpecToSpecifySpec($arguments) {
		$parentSpec = new Spec();
		\spectrum\core\_private\setCurrentBuildingSpec($parentSpec);
		$groupSpec = call_user_func_array('\spectrum\core\constructs\group', $arguments);
		
		$this->assertSame(array($groupSpec), $parentSpec->getChildSpecs());
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinations
	 */
	public function testCallsAtBuildingState_BuildingSpecIsNotRoot_DoesNotAddGroupSpecToRootSpec($arguments) {
		\spectrum\core\_private\setCurrentBuildingSpec(new Spec());
		call_user_func_array('\spectrum\core\constructs\group', $arguments);
		
		$this->assertSame(array(), \spectrum\core\_private\getRootSpec()->getChildSpecs());
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinationsWithEmptyContextAndBodyFunctions
	 */
	public function testCallsAtBuildingState_BuildingSpecIsNotRoot_DoesNotAddGroupSpecToSiblingGroupSpecs($arguments) {
		\spectrum\core\_private\setCurrentBuildingSpec(new Spec());
		$groupSpec1 = call_user_func_array('\spectrum\core\constructs\group', $arguments);
		$groupSpec2 = call_user_func_array('\spectrum\core\constructs\group', $arguments);
		$groupSpec3 = call_user_func_array('\spectrum\core\constructs\group', $arguments);
		
		$this->assertSame(array(), $groupSpec1->getChildSpecs());
		$this->assertSame(array(), $groupSpec2->getChildSpecs());
		$this->assertSame(array(), $groupSpec3->getChildSpecs());
	}
	
	public function providerVariantsOfArguments_NameArgumentIsString() {
		return $this->getProviderWithCorrectArgumentsForGroupAndTestConstructs('aaa bbb');
	}

	/**
	 * @dataProvider providerVariantsOfArguments_NameArgumentIsString
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_NameArgumentIsString_SetsNameToGroupSpec($arguments) {
		$parentSpec = new Spec();
		\spectrum\core\_private\setCurrentBuildingSpec($parentSpec);
		$groupSpec = call_user_func_array('\spectrum\core\constructs\group', $arguments);

		$this->assertSame(null, $parentSpec->getName());
		$this->assertSame('aaa bbb', $groupSpec->getName());
	}
	
	public function providerVariantsOfArguments_ContextsArgumentIsArray() {
		return $this->getProviderWithCorrectArgumentsForGroupAndTestConstructs(
			null,
			array(
				'aaa' => array(),
				'bbb' => array(),
				'ccc' => array(),
			),
			function() {
				\spectrum\tests\_testware\tools::$temp[] = \spectrum\core\constructs\group(null, null, function(){}, null);
				\spectrum\tests\_testware\tools::$temp[] = \spectrum\core\constructs\test(null, null, function(){}, null);
			}
		);
	}

	/**
	 * @dataProvider providerVariantsOfArguments_ContextsArgumentIsArray
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_ContextsArgumentIsArray_AddsContextSpecsBetweenGroupSpecAndBodySpecs($arguments) {
		$parentSpec = new Spec();
		\spectrum\core\_private\setCurrentBuildingSpec($parentSpec);
		
		\spectrum\tests\_testware\tools::$temp = array();
		$groupSpec = call_user_func_array('\spectrum\core\constructs\group', $arguments);
		
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
		
		$this->assertSame(\spectrum\tests\_testware\tools::$temp, $contextEndingSpecs[0]->getChildSpecs());
	}
	
	public function providerVariantsOfArguments_ContextsArgumentIsFunction() {
		return $this->getProviderWithCorrectArgumentsForGroupAndTestConstructs(
			null,
			function() {
				\spectrum\core\constructs\group('aaa', null, function(){}, null);
				\spectrum\core\constructs\group('bbb', null, function(){}, null);
				\spectrum\core\constructs\group('ccc', null, function(){}, null);
			},
			function() {
				\spectrum\tests\_testware\tools::$temp[] = \spectrum\core\constructs\group(null, null, function(){}, null);
				\spectrum\tests\_testware\tools::$temp[] = \spectrum\core\constructs\test(null, null, function(){}, null);
			}
		);
	}

	/**
	 * @dataProvider providerVariantsOfArguments_ContextsArgumentIsFunction
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_ContextsArgumentIsFunction_AddsContextSpecsBetweenGroupSpecAndBodySpecs($arguments) {
		$parentSpec = new Spec();
		\spectrum\core\_private\setCurrentBuildingSpec($parentSpec);
		
		\spectrum\tests\_testware\tools::$temp = array();
		$groupSpec = call_user_func_array('\spectrum\core\constructs\group', $arguments);
		
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
		
		$this->assertSame(\spectrum\tests\_testware\tools::$temp, $contextEndingSpecs[0]->getChildSpecs());
	}
	
	public function providerVariantsOfArguments_ContextsArgumentIsFunction2() {
		return $this->getProviderWithCorrectArgumentsForGroupAndTestConstructs(
			null,
			function() {
				\spectrum\core\constructs\group(
					'aaa',
					null,
					function(){
						\spectrum\core\constructs\group('bbb', null, function(){}, null);
						\spectrum\core\constructs\group('ccc', null, function(){}, null);
					},
					null
				);
				\spectrum\core\constructs\group('ddd', null, function(){}, null);
			},
			function() {
				\spectrum\tests\_testware\tools::$temp[] = \spectrum\core\constructs\group(null, null, function(){}, null);
				\spectrum\tests\_testware\tools::$temp[] = \spectrum\core\constructs\test(null, null, function(){}, null);
			}
		);
	}

	/**
	 * @dataProvider providerVariantsOfArguments_ContextsArgumentIsFunction2
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_ContextsArgumentIsFunction_AddsBodySpecsToEndingContextSpecsOnly($arguments) {
		$parentSpec = new Spec();
		\spectrum\core\_private\setCurrentBuildingSpec($parentSpec);
		
		\spectrum\tests\_testware\tools::$temp = array();
		$groupSpec = call_user_func_array('\spectrum\core\constructs\group', $arguments);
		
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
		
		$this->assertSame(\spectrum\tests\_testware\tools::$temp, $contextEndingSpecs[0]->getChildSpecs());
	}
	
	public function providerVariantsOfArguments_ContextsArgumentIsFunction3() {
		return $this->getProviderWithCorrectArgumentsForGroupAndTestConstructs(
			null,
			function() {
				\spectrum\core\constructs\test('aaa', null, function(){}, null);
				\spectrum\core\constructs\group('bbb', null, function(){}, null);
				\spectrum\core\constructs\group('ccc', null, function(){}, null);
				\spectrum\core\constructs\test('ddd', null, function(){}, null);
			},
			function() {
				\spectrum\tests\_testware\tools::$temp[] = \spectrum\core\constructs\group(null, null, function(){}, null);
				\spectrum\tests\_testware\tools::$temp[] = \spectrum\core\constructs\test(null, null, function(){}, null);
			}
		);
	}

	/**
	 * @dataProvider providerVariantsOfArguments_ContextsArgumentIsFunction3
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_ContextsArgumentIsFunction_DoesNotAddSpecsToTestSpecOfContext($arguments) {
		\spectrum\core\_private\setCurrentBuildingSpec(new Spec());
		
		\spectrum\tests\_testware\tools::$temp = array();
		$groupSpec = call_user_func_array('\spectrum\core\constructs\group', $arguments);
		
		$contextSpecs = $groupSpec->getChildSpecs();
		$this->assertSame(4, count($contextSpecs));
		$this->assertSame('aaa', $contextSpecs[0]->getName());
		$this->assertSame('bbb', $contextSpecs[1]->getName());
		$this->assertSame('ccc', $contextSpecs[2]->getName());
		$this->assertSame('ddd', $contextSpecs[3]->getName());
		
		$this->assertSame(0, count($contextSpecs[0]->getChildSpecs()));
		$this->assertSame(0, count($contextSpecs[3]->getChildSpecs()));
		
		$contextEndingSpecs = array_merge(
			$contextSpecs[1]->getChildSpecs(),
			$contextSpecs[2]->getChildSpecs()
		);
		
		$this->assertSame(2, count($contextEndingSpecs));
		$this->assertSame($contextEndingSpecs[0], $contextEndingSpecs[1]);
		$this->assertSame(\spectrum\tests\_testware\tools::$temp, $contextEndingSpecs[0]->getChildSpecs());
	}
		
	public function providerVariantsOfArguments_BodyArgumentIsFunction() {
		return $this->getProviderWithCorrectArgumentsForGroupAndTestConstructs(null, array(), function(){
			\spectrum\tests\_testware\tools::$temp[] = \spectrum\core\constructs\group('aaa', null, function(){}, null);
			\spectrum\tests\_testware\tools::$temp[] = \spectrum\core\constructs\group('bbb', null, function(){}, null);
			\spectrum\tests\_testware\tools::$temp[] = \spectrum\core\constructs\test('ccc', null, function(){}, null);
			\spectrum\tests\_testware\tools::$temp[] = \spectrum\core\constructs\test('ddd', null, function(){}, null);
			\spectrum\tests\_testware\tools::$temp[] = \spectrum\core\constructs\group('eee', null, function(){}, null);
		});
	}

	/**
	 * @dataProvider providerVariantsOfArguments_BodyArgumentIsFunction
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_BodyArgumentIsFunction_AddsBodySpecsToGroupSpec($arguments) {
		\spectrum\tests\_testware\tools::$temp = array();
		$groupSpec = call_user_func_array('\spectrum\core\constructs\group', $arguments);

		$this->assertSame(\spectrum\tests\_testware\tools::$temp, $groupSpec->getChildSpecs());
		
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
	
	public function providerVariantsOfArguments_BodyArgumentIsFunction2() {
		return $this->getProviderWithCorrectArgumentsForGroupAndTestConstructs(
			'aaa',
			array(),
			function() {
				\spectrum\core\constructs\group(
					'bbb',
					null,
					function() {
						\spectrum\core\constructs\test('ccc', null, function(){}, null);
					},
					null
				);
				
				\spectrum\core\constructs\group(
					'ddd',
					null,
					function() {
						\spectrum\core\constructs\group(
							'eee',
							null,
							function() {
								\spectrum\core\constructs\test('fff', null, function(){}, null);
							},
							null
						);
						
						\spectrum\core\constructs\group(
							'ggg',
							null,
							function() {
								\spectrum\core\constructs\test('hhh', null, function(){}, null);
								\spectrum\core\constructs\test('iii', null, function(){}, null);
							},
							null
						);
					},
					null
				);
			}
		);
	}

	/**
	 * @dataProvider providerVariantsOfArguments_BodyArgumentIsFunction2
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_BodyArgumentIsFunction_AddsDescendantSpecsOfBodySpecsToHisParents($arguments) {
		$parentSpec = new Spec();
		\spectrum\core\_private\setCurrentBuildingSpec($parentSpec);
		$groupSpec = call_user_func_array('\spectrum\core\constructs\group', $arguments);

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
		
	public function providerVariantsOfArguments_SettingsArgumentIsInteger() {
		return $this->getProviderWithCorrectArgumentsForGroupAndTestConstructs(null, null, function(){
			\spectrum\tests\_testware\tools::$temp[] = \spectrum\core\constructs\group('aaa', null, function(){}, null);
			\spectrum\tests\_testware\tools::$temp[] = \spectrum\core\constructs\test('bbb', null, function(){}, null);
		}, 8);
	}

	/**
	 * @dataProvider providerVariantsOfArguments_SettingsArgumentIsInteger
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_SettingsArgumentIsInteger_SetsErrorHandlingLevelToGroupSpec($arguments) {
		$parentSpec = new Spec();
		\spectrum\core\_private\setCurrentBuildingSpec($parentSpec);
		
		\spectrum\tests\_testware\tools::$temp = array();
		$groupSpec = call_user_func_array('\spectrum\core\constructs\group', $arguments);

		$this->assertNotSame(8, $parentSpec->getErrorHandling()->getCatchPhpErrors());
		$this->assertSame(8, $groupSpec->getErrorHandling()->getCatchPhpErrors());
		$this->assertNotSame(8, \spectrum\tests\_testware\tools::$temp[0]->getErrorHandling()->getCatchPhpErrors());
		$this->assertNotSame(8, \spectrum\tests\_testware\tools::$temp[1]->getErrorHandling()->getCatchPhpErrors());
	}
	
	public function providerVariantsOfArguments_SettingsArgumentIsTrue() {
		return $this->getProviderWithCorrectArgumentsForGroupAndTestConstructs(null, null, function(){
			\spectrum\tests\_testware\tools::$temp[] = \spectrum\core\constructs\group('aaa', null, function(){}, null);
			\spectrum\tests\_testware\tools::$temp[] = \spectrum\core\constructs\test('bbb', null, function(){}, null);
		}, true);
	}

	/**
	 * @dataProvider providerVariantsOfArguments_SettingsArgumentIsTrue
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_SettingsArgumentIsTrue_SetsErrorHandlingLevelToGroupSpec($arguments) {
		$parentSpec = new Spec();
		\spectrum\core\_private\setCurrentBuildingSpec($parentSpec);
		
		\spectrum\tests\_testware\tools::$temp = array();
		$groupSpec = call_user_func_array('\spectrum\core\constructs\group', $arguments);

		$this->assertNotSame(-1, $parentSpec->getErrorHandling()->getCatchPhpErrors());
		$this->assertSame(-1, $groupSpec->getErrorHandling()->getCatchPhpErrors());
		$this->assertNotSame(-1, \spectrum\tests\_testware\tools::$temp[0]->getErrorHandling()->getCatchPhpErrors());
		$this->assertNotSame(-1, \spectrum\tests\_testware\tools::$temp[1]->getErrorHandling()->getCatchPhpErrors());
	}
	
	public function providerVariantsOfArguments_SettingsArgumentIsFalse() {
		return $this->getProviderWithCorrectArgumentsForGroupAndTestConstructs(null, null, function(){
			\spectrum\tests\_testware\tools::$temp[] = \spectrum\core\constructs\group('aaa', null, function(){}, null);
			\spectrum\tests\_testware\tools::$temp[] = \spectrum\core\constructs\test('bbb', null, function(){}, null);
		}, false);
	}

	/**
	 * @dataProvider providerVariantsOfArguments_SettingsArgumentIsFalse
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_SettingsArgumentIsFalse_SetsErrorHandlingLevelToGroupSpec($arguments) {
		$parentSpec = new Spec();
		\spectrum\core\_private\setCurrentBuildingSpec($parentSpec);
		
		\spectrum\tests\_testware\tools::$temp = array();
		$groupSpec = call_user_func_array('\spectrum\core\constructs\group', $arguments);

		$this->assertNotSame(0, $parentSpec->getErrorHandling()->getCatchPhpErrors());
		$this->assertSame(0, $groupSpec->getErrorHandling()->getCatchPhpErrors());
		$this->assertNotSame(0, \spectrum\tests\_testware\tools::$temp[0]->getErrorHandling()->getCatchPhpErrors());
		$this->assertNotSame(0, \spectrum\tests\_testware\tools::$temp[1]->getErrorHandling()->getCatchPhpErrors());
	}
	
	public function providerVariantsOfArguments_SettingsArgumentIsArray() {
		return $this->getProviderWithCorrectArgumentsForGroupAndTestConstructs(
			null,
			null,
			function() {
				\spectrum\tests\_testware\tools::$temp[] = \spectrum\core\constructs\group('aaa', null, function(){}, null);
				\spectrum\tests\_testware\tools::$temp[] = \spectrum\core\constructs\test('bbb', null, function(){}, null);
			},
			array(
				'catchPhpErrors' => 8,
				'breakOnFirstPhpError' => true,
				'breakOnFirstMatcherFail' => true,
			)
		);
	}

	/**
	 * @dataProvider providerVariantsOfArguments_SettingsArgumentIsArray
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_SettingsArgumentIsArray_SetsSettingsToGroupSpec($arguments) {
		$parentSpec = new Spec();
		\spectrum\core\_private\setCurrentBuildingSpec($parentSpec);
		
		\spectrum\tests\_testware\tools::$temp = array();
		$groupSpec = call_user_func_array('\spectrum\core\constructs\group', $arguments);

		$this->assertNotSame(8, $parentSpec->getErrorHandling()->getCatchPhpErrors());
		$this->assertNotSame(true, $parentSpec->getErrorHandling()->getBreakOnFirstPhpError());
		$this->assertNotSame(true, $parentSpec->getErrorHandling()->getBreakOnFirstMatcherFail());
		
		$this->assertSame(8, $groupSpec->getErrorHandling()->getCatchPhpErrors());
		$this->assertSame(true, $groupSpec->getErrorHandling()->getBreakOnFirstPhpError());
		$this->assertSame(true, $groupSpec->getErrorHandling()->getBreakOnFirstMatcherFail());
		
		$this->assertNotSame(8, \spectrum\tests\_testware\tools::$temp[0]->getErrorHandling()->getCatchPhpErrors());
		$this->assertNotSame(true, \spectrum\tests\_testware\tools::$temp[0]->getErrorHandling()->getBreakOnFirstPhpError());
		$this->assertNotSame(true, \spectrum\tests\_testware\tools::$temp[0]->getErrorHandling()->getBreakOnFirstMatcherFail());
		
		$this->assertNotSame(8, \spectrum\tests\_testware\tools::$temp[1]->getErrorHandling()->getCatchPhpErrors());
		$this->assertNotSame(true, \spectrum\tests\_testware\tools::$temp[1]->getErrorHandling()->getBreakOnFirstPhpError());
		$this->assertNotSame(true, \spectrum\tests\_testware\tools::$temp[1]->getErrorHandling()->getBreakOnFirstMatcherFail());
	}
	
/**/
	
	public function testCallsAtBuildingState_BadArgumentsPassed_ThrowsException() {
		$this->assertThrowsException('\spectrum\core\Exception', 'Incorrect arguments in "group" construct', function() {
			\spectrum\core\constructs\group(null, null, function(){}, null, null, null, null);
		});
	}
	
/**/
	
	public function testCallsAtRunningState_ThrowsException() {
		\spectrum\core\config::registerEventListener('onEndingSpecExecuteBefore', function() use(&$exception) {
			try {
				\spectrum\core\constructs\group();
			} catch (\Exception $e) {
				$exception = $e;
			}
		});
		
		\spectrum\core\_private\getRootSpec()->run();
		
		$this->assertInstanceOf('\spectrum\core\Exception', $exception);
		$this->assertSame('Function "group" should be call only at building state', $exception->getMessage());
	}
}