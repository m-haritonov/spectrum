<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core\constructs;

use spectrum\core\models\Spec;

require_once __DIR__ . '/../../../init.php';

class TestTest extends \spectrum\tests\automatic\Test {
	public function providerAllArgumentCombinations() {
		return $this->getProviderWithCorrectArgumentsForGroupAndTestConstructs();
	}

	public function providerAllArgumentCombinationsWithEmptyContextAndBodyFunctions() {
		return $this->getProviderWithCorrectArgumentsForGroupAndTestConstructs(null, function(){}, function(){});
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinations
	 */
	public function testCallsAtBuildingState_ReturnsNewTestSpec($arguments) {
		$parentSpec = new Spec();
		\spectrum\core\_private\setCurrentBuildingSpec($parentSpec);
		
		$testSpec1 = call_user_func_array('\spectrum\core\constructs\test', $arguments);
		$this->assertInstanceOf('\spectrum\core\models\Spec', $testSpec1);
		$this->assertNotSame($parentSpec, $testSpec1);
		
		$testSpec2 = call_user_func_array('\spectrum\core\constructs\test', $arguments);
		$this->assertInstanceOf('\spectrum\core\models\Spec', $testSpec2);
		$this->assertNotSame($parentSpec, $testSpec2);
		$this->assertNotSame($testSpec1, $testSpec2);
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinations
	 */
	public function testCallsAtBuildingState_RestoreBuildingSpecAfterCall($arguments) {
		$parentSpec = new Spec();
		\spectrum\core\_private\setCurrentBuildingSpec($parentSpec);
		call_user_func_array('\spectrum\core\constructs\test', $arguments);
		$this->assertSame($parentSpec, \spectrum\core\_private\getCurrentBuildingSpec());
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinations
	 */
	public function testCallsAtBuildingState_BuildingSpecIsRoot_AddsTestSpecToRootSpec($arguments) {
		$testSpec = call_user_func_array('\spectrum\core\constructs\test', $arguments);
		$this->assertSame(array($testSpec), \spectrum\core\_private\getRootSpec()->getChildSpecs());
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinations
	 */
	public function testCallsAtBuildingState_BuildingSpecIsNotRoot_AddsTestSpecToSpecifySpec($arguments) {
		$parentSpec = new Spec();
		\spectrum\core\_private\setCurrentBuildingSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\core\constructs\test', $arguments);
		
		$this->assertSame(array($testSpec), $parentSpec->getChildSpecs());
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinations
	 */
	public function testCallsAtBuildingState_BuildingSpecIsNotRoot_DoesNotAddTestSpecToRootSpec($arguments) {
		\spectrum\core\_private\setCurrentBuildingSpec(new Spec());
		call_user_func_array('\spectrum\core\constructs\test', $arguments);
		
		$this->assertSame(array(), \spectrum\core\_private\getRootSpec()->getChildSpecs());
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinationsWithEmptyContextAndBodyFunctions
	 */
	public function testCallsAtBuildingState_BuildingSpecIsNotRoot_DoesNotAddTestSpecToSiblingTestSpecs($arguments) {
		\spectrum\core\_private\setCurrentBuildingSpec(new Spec());
		$testSpec1 = call_user_func_array('\spectrum\core\constructs\test', $arguments);
		$testSpec2 = call_user_func_array('\spectrum\core\constructs\test', $arguments);
		$testSpec3 = call_user_func_array('\spectrum\core\constructs\test', $arguments);
		
		$this->assertSame(array(), $testSpec1->getChildSpecs());
		$this->assertSame(array(), $testSpec2->getChildSpecs());
		$this->assertSame(array(), $testSpec3->getChildSpecs());
	}
	
	public function providerVariantsOfArguments_NameArgumentIsString() {
		return $this->getProviderWithCorrectArgumentsForGroupAndTestConstructs('aaa bbb');
	}

	/**
	 * @dataProvider providerVariantsOfArguments_NameArgumentIsString
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_NameArgumentIsString_SetsNameToTestSpec($arguments) {
		$parentSpec = new Spec();
		\spectrum\core\_private\setCurrentBuildingSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\core\constructs\test', $arguments);

		$this->assertSame(null, $parentSpec->getName());
		$this->assertSame('aaa bbb', $testSpec->getName());
	}
	
	public function providerVariantsOfArguments_ContextsArgumentIsArray() {
		return $this->getProviderWithCorrectArgumentsForGroupAndTestConstructs(null, array(
			'aaa' => array(),
			'bbb' => array(),
			'ccc' => array(),
		));
	}

	/**
	 * @dataProvider providerVariantsOfArguments_ContextsArgumentIsArray
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_ContextsArgumentIsArray_AddsContextSpecsToTestSpecAsChildSpecs($arguments) {
		$parentSpec = new Spec();
		\spectrum\core\_private\setCurrentBuildingSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\core\constructs\test', $arguments);
		
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
	
	public function providerVariantsOfArguments_ContextsArgumentIsFunction() {
		return $this->getProviderWithCorrectArgumentsForGroupAndTestConstructs(
			null,
			function() {
				\spectrum\core\constructs\group(
					'aaa',
					null,
					function() {
						\spectrum\core\constructs\group('bbb', null, function(){}, null);
						\spectrum\core\constructs\group('ccc', null, function(){}, null);
					},
					null
				);
				\spectrum\core\constructs\group('ddd', null, function(){}, null);
				\spectrum\core\constructs\group('eee', null, function(){}, null);
			}
		);
	}

	/**
	 * @dataProvider providerVariantsOfArguments_ContextsArgumentIsFunction
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_ContextsArgumentIsFunction_AddsContextSpecsToTestSpecAsChildSpecs($arguments) {
		$parentSpec = new Spec();
		\spectrum\core\_private\setCurrentBuildingSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\core\constructs\test', $arguments);
		
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
	
	public function providerVariantsOfArguments_ContextsArgumentIsFunction2() {
		return $this->getProviderWithCorrectArgumentsForGroupAndTestConstructs(null, function(){
			\spectrum\core\constructs\test('aaa', null, function(){ return 'zzz'; }, null);
		});
	}

	/**
	 * @dataProvider providerVariantsOfArguments_ContextsArgumentIsFunction2
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_ContextsArgumentIsFunction_AddsTestContextSpecToTestSpecWithOwnBodyFunction($arguments) {
		\spectrum\core\_private\setCurrentBuildingSpec(new Spec());
		$testSpec = call_user_func_array('\spectrum\core\constructs\test', $arguments);
		
		$contextSpecs = $testSpec->getChildSpecs();
		$this->assertSame(1, count($contextSpecs));
		$this->assertSame('aaa', $contextSpecs[0]->getName());
		$this->assertSame(array(), $contextSpecs[0]->getChildSpecs());
		
		$bodyFunction = $contextSpecs[0]->getExecutor()->getFunction();
		$this->assertNotSame($testSpec->getExecutor()->getFunction(), $bodyFunction);
		$this->assertSame('zzz', $bodyFunction());
	}
	
	public function providerVariantsOfArguments_BodyArgumentIsFunction() {
		$function = function() use(&$function) { return $function; };
		return $this->getProviderWithCorrectArgumentsForGroupAndTestConstructs(null, null, $function);
	}

	/**
	 * @dataProvider providerVariantsOfArguments_BodyArgumentIsFunction
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_BodyArgumentIsFunction_AddsBodyFunctionToTest($arguments) {
		$testSpec = call_user_func_array('\spectrum\core\constructs\test', $arguments);
		$this->assertInstanceOf('\Closure', $testSpec->getExecutor()->getFunction());
		
		$function = $testSpec->getExecutor()->getFunction();
		$this->assertSame($function(), $function);
	}
	
	public function providerVariantsOfArguments_BodyArgumentIsFunction2() {
		return $this->getProviderWithCorrectArgumentsForGroupAndTestConstructs(null, null, function() {
			\spectrum\tests\_testware\tools::$temp['isCalled'] = true;
		});
	}

	/**
	 * @dataProvider providerVariantsOfArguments_BodyArgumentIsFunction2
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_BodyArgumentIsFunction_DoesNotCallBodyFunction($arguments) {
		\spectrum\tests\_testware\tools::$temp['isCalled'] = false;
		call_user_func_array('\spectrum\core\constructs\test', $arguments);
		$this->assertSame(false, \spectrum\tests\_testware\tools::$temp['isCalled']);
	}
	
	public function providerVariantsOfArguments_SettingsArgumentIsInteger() {
		return $this->getProviderWithCorrectArgumentsForGroupAndTestConstructs(null, null, null, 8);
	}

	/**
	 * @dataProvider providerVariantsOfArguments_SettingsArgumentIsInteger
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_SettingsArgumentIsInteger_SetsErrorHandlingLevelToTestSpec($arguments) {
		$parentSpec = new Spec();
		\spectrum\core\_private\setCurrentBuildingSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\core\constructs\test', $arguments);

		$this->assertNotSame(8, $parentSpec->getErrorHandling()->getCatchPhpErrors());
		$this->assertSame(8, $testSpec->getErrorHandling()->getCatchPhpErrors());
	}
	
	public function providerVariantsOfArguments_SettingsArgumentIsTrue() {
		return $this->getProviderWithCorrectArgumentsForGroupAndTestConstructs(null, null, null, true);
	}

	/**
	 * @dataProvider providerVariantsOfArguments_SettingsArgumentIsTrue
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_SettingsArgumentIsTrue_SetsErrorHandlingLevelToTestSpec($arguments) {
		$parentSpec = new Spec();
		\spectrum\core\_private\setCurrentBuildingSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\core\constructs\test', $arguments);

		$this->assertNotSame(-1, $parentSpec->getErrorHandling()->getCatchPhpErrors());
		$this->assertSame(-1, $testSpec->getErrorHandling()->getCatchPhpErrors());
	}
	
	public function providerVariantsOfArguments_SettingsArgumentIsFalse() {
		return $this->getProviderWithCorrectArgumentsForGroupAndTestConstructs(null, null, null, false);
	}

	/**
	 * @dataProvider providerVariantsOfArguments_SettingsArgumentIsFalse
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_SettingsArgumentIsFalse_SetsErrorHandlingLevelToTestSpec($arguments) {
		$parentSpec = new Spec();
		\spectrum\core\_private\setCurrentBuildingSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\core\constructs\test', $arguments);

		$this->assertNotSame(0, $parentSpec->getErrorHandling()->getCatchPhpErrors());
		$this->assertSame(0, $testSpec->getErrorHandling()->getCatchPhpErrors());
	}
	
	public function providerVariantsOfArguments_SettingsArgumentIsArray() {
		return $this->getProviderWithCorrectArgumentsForGroupAndTestConstructs(null, null, null, array(
			'catchPhpErrors' => 8,
			'breakOnFirstPhpError' => true,
			'breakOnFirstMatcherFail' => true,
		));
	}

	/**
	 * @dataProvider providerVariantsOfArguments_SettingsArgumentIsArray
	 */
	public function testCallsAtBuildingState_VariantsOfArguments_SettingsArgumentIsArray_SetsSettingsToTestSpec($arguments) {
		$parentSpec = new Spec();
		\spectrum\core\_private\setCurrentBuildingSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\core\constructs\test', $arguments);

		$this->assertNotSame(8, $parentSpec->getErrorHandling()->getCatchPhpErrors());
		$this->assertNotSame(true, $parentSpec->getErrorHandling()->getBreakOnFirstPhpError());
		$this->assertNotSame(true, $parentSpec->getErrorHandling()->getBreakOnFirstMatcherFail());
		
		$this->assertSame(8, $testSpec->getErrorHandling()->getCatchPhpErrors());
		$this->assertSame(true, $testSpec->getErrorHandling()->getBreakOnFirstPhpError());
		$this->assertSame(true, $testSpec->getErrorHandling()->getBreakOnFirstMatcherFail());
	}
	
/**/
	
	public function testCallsAtBuildingState_BadArgumentsPassed_ThrowsException() {
		$this->assertThrowsException('\spectrum\core\Exception', 'Incorrect arguments in "test" construct', function(){
			\spectrum\core\constructs\test(null, null, function(){}, null, null, null, null);
		});
	}
	
/**/
	
	public function testCallsAtRunningState_ThrowsException() {
		\spectrum\core\config::registerEventListener('onEndingSpecExecuteBefore', function() use(&$exception) {
			try {
				\spectrum\core\constructs\test();
			} catch (\Exception $e) {
				$exception = $e;
			}
		});
		
		\spectrum\core\_private\getRootSpec()->run();
		
		$this->assertInstanceOf('\spectrum\core\Exception', $exception);
		$this->assertSame('Function "test" should be call only at building state', $exception->getMessage());
	}
}