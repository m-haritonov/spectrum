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
	public function testCallsAtBuildingState_BuildingSpecIsCustom_AddsTestSpecToBuildingSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\builders\test', $arguments);
		
		$this->assertSame(array($testSpec), $parentSpec->getChildSpecs());
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinationsWithEmptyContextAndBodyFunctions
	 */
	public function testCallsAtBuildingState_BuildingSpecIsCustom_DoesNotAddTestSpecToSiblingTestSpecs($arguments)
	{
		\spectrum\builders\internal\setBuildingSpec(new Spec());
		$testSpec1 = call_user_func_array('\spectrum\builders\test', $arguments);
		$testSpec2 = call_user_func_array('\spectrum\builders\test', $arguments);
		$testSpec3 = call_user_func_array('\spectrum\builders\test', $arguments);
		
		$this->assertSame(array(), $testSpec1->getChildSpecs());
		$this->assertSame(array(), $testSpec2->getChildSpecs());
		$this->assertSame(array(), $testSpec3->getChildSpecs());
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
	public function testCallsAtBuildingState_BuildingSpecIsNotRoot_DoesNotAddTestSpecToRootSpec($arguments)
	{
		\spectrum\builders\internal\setBuildingSpec(new Spec());
		call_user_func_array('\spectrum\builders\test', $arguments);
		
		$this->assertSame(array(), \spectrum\builders\getRootSpec()->getChildSpecs());
	}
	
	public function providerNameIsString()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders('aaa bbb');
	}

	/**
	 * @dataProvider providerNameIsString
	 */
	public function testCallsAtBuildingState_NameIsString_SetsNameToTestSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\builders\test', $arguments);
		
		$this->assertSame('aaa bbb', $testSpec->getName());
		$this->assertSame(null, $parentSpec->getName());
	}
	
	public function providerContextsIsArray()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders(null, array(
			'aaa' => array(),
			'bbb' => array(),
			'ccc' => array(),
		));
	}

	/**
	 * @dataProvider providerContextsIsArray
	 */
	public function testCallsAtBuildingState_ContextsIsArray_AddsContextSpecsToTestSpecAsChildSpecs($arguments)
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
	
	public function providerContextsIsFunction()
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
	 * @dataProvider providerContextsIsFunction
	 */
	public function testCallsAtBuildingState_ContextsIsFunction_AddsContextSpecsToTestSpecAsChildSpecs($arguments)
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
	
	public function providerContextsIsFunction2()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders(null, function(){
			\spectrum\builders\test('aaa', null, function(){ return 'zzz'; }, null);
		});
	}

	/**
	 * @dataProvider providerContextsIsFunction2
	 */
	public function testCallsAtBuildingState_ContextsIsFunction_AddsTestContextSpecToTestSpecWithOwnBodyFunction($arguments)
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
	
	public function providerBodyIsFunction()
	{
		$function = function() use(&$function){ return $function; };
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders(null, null, $function);
	}

	/**
	 * @dataProvider providerBodyIsFunction
	 */
	public function testCallsAtBuildingState_BodyIsFunction_AddsBodyFunctionToTestPlugin($arguments)
	{
		$testSpec = call_user_func_array('\spectrum\builders\test', $arguments);
		$this->assertInstanceOf('\Closure', $testSpec->test->getFunction());
		
		$function = $testSpec->test->getFunction();
		$this->assertSame($function(), $function);
	}
	
	public function providerBodyIsFunction2()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders(null, null, function(){
			\spectrum\tests\Test::$temp['isCalled'] = true;
		});
	}

	/**
	 * @dataProvider providerBodyIsFunction2
	 */
	public function testCallsAtBuildingState_BodyIsFunction_DoesNotCallBodyFunction($arguments)
	{
		\spectrum\tests\Test::$temp['isCalled'] = false;
		call_user_func_array('\spectrum\builders\test', $arguments);
		$this->assertSame(false, \spectrum\tests\Test::$temp['isCalled']);
	}
	
	public function providerSettingsIsString()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders(null, null, null, 'koi8-r');
	}

	/**
	 * @dataProvider providerSettingsIsString
	 */
	public function testCallsAtBuildingState_SettingsIsString_SetsInputCharsetToTestSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\builders\test', $arguments);

		$this->assertSame('koi8-r', mb_strtolower($testSpec->charset->getInputCharset()));
		$this->assertNotSame('koi8-r', mb_strtolower($parentSpec->charset->getInputCharset()));
	}
	
	public function providerSettingsIsInteger()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders(null, null, null, 8);
	}

	/**
	 * @dataProvider providerSettingsIsInteger
	 */
	public function testCallsAtBuildingState_SettingsIsInteger_SetsErrorHandlingLevelToTestSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\builders\test', $arguments);

		$this->assertSame(8, $testSpec->errorHandling->getCatchPhpErrors());
		$this->assertNotSame(8, $parentSpec->errorHandling->getCatchPhpErrors());
	}
	
	public function providerSettingsIsTrue()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders(null, null, null, true);
	}

	/**
	 * @dataProvider providerSettingsIsTrue
	 */
	public function testCallsAtBuildingState_SettingsIsTrue_SetsErrorHandlingLevelToTestSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\builders\test', $arguments);

		$this->assertSame(-1, $testSpec->errorHandling->getCatchPhpErrors());
		$this->assertNotSame(-1, $parentSpec->errorHandling->getCatchPhpErrors());
	}
	
	public function providerSettingsIsFalse()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders(null, null, null, false);
	}

	/**
	 * @dataProvider providerSettingsIsFalse
	 */
	public function testCallsAtBuildingState_SettingsIsFalse_SetsErrorHandlingLevelToTestSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\builders\test', $arguments);

		$this->assertSame(0, $testSpec->errorHandling->getCatchPhpErrors());
		$this->assertNotSame(0, $parentSpec->errorHandling->getCatchPhpErrors());
	}
	
	public function providerSettingsIsArray()
	{
		return $this->getProviderWithCorrectArgumentsForGroupAndTestBuilders(null, null, null, array(
			'catchPhpErrors' => 8,
			'breakOnFirstPhpError' => true,
			'breakOnFirstMatcherFail' => true,
			'inputCharset' => 'koi8-r',
		));
	}

	/**
	 * @dataProvider providerSettingsIsArray
	 */
	public function testCallsAtBuildingState_SettingsIsArray_SetsSettingsToTestSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\builders\test', $arguments);

		$this->assertSame(8, $testSpec->errorHandling->getCatchPhpErrors());
		$this->assertSame(true, $testSpec->errorHandling->getBreakOnFirstPhpError());
		$this->assertSame(true, $testSpec->errorHandling->getBreakOnFirstMatcherFail());
		$this->assertSame('koi8-r', $testSpec->charset->getInputCharset());
		
		$this->assertNotSame(8, $parentSpec->errorHandling->getCatchPhpErrors());
		$this->assertNotSame(true, $parentSpec->errorHandling->getBreakOnFirstPhpError());
		$this->assertNotSame(true, $parentSpec->errorHandling->getBreakOnFirstMatcherFail());
		$this->assertNotSame('koi8-r', $parentSpec->charset->getInputCharset());
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