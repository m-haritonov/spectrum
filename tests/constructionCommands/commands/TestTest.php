<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\constructionCommands\commands;

use spectrum\config;
use spectrum\constructionCommands\callBroker;
use spectrum\core\Spec;

require_once __DIR__ . '/../../init.php';

class TestTest extends \spectrum\tests\Test
{
	public function providerAllArgumentCombinations()
	{
		return $this->getProviderWithCorrectArgumentCombinationsForSpecDeclaringConstructionCommand();
	}

	public function providerAllArgumentCombinationsWithEmptyContextAndBodyFunctions()
	{
		return $this->getProviderWithCorrectArgumentCombinationsForSpecDeclaringConstructionCommand(null, function(){}, function(){});
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinations
	 */
	public function testCallsAtDeclaringState_ReturnsNewTestSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\constructionCommands\callBroker::internal_setDeclaringSpec($parentSpec);
		
		$testSpec1 = call_user_func_array('\spectrum\constructionCommands\callBroker::test', $arguments);
		$this->assertInstanceOf('\spectrum\core\Spec', $testSpec1);
		$this->assertNotSame($parentSpec, $testSpec1);
		
		$testSpec2 = call_user_func_array('\spectrum\constructionCommands\callBroker::test', $arguments);
		$this->assertInstanceOf('\spectrum\core\Spec', $testSpec2);
		$this->assertNotSame($parentSpec, $testSpec2);
		$this->assertNotSame($testSpec1, $testSpec2);
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinations
	 */
	public function testCallsAtDeclaringState_RestoreDeclaringSpecAfterCall($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\constructionCommands\callBroker::internal_setDeclaringSpec($parentSpec);
		call_user_func_array('\spectrum\constructionCommands\callBroker::test', $arguments);
		$this->assertSame($parentSpec, \spectrum\constructionCommands\callBroker::internal_getDeclaringSpec());
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinations
	 */
	public function testCallsAtDeclaringState_DeclaringSpecIsCustom_AddsTestSpecToDeclaringSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\constructionCommands\callBroker::internal_setDeclaringSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\constructionCommands\callBroker::test', $arguments);
		
		$this->assertSame(array($testSpec), $parentSpec->getChildSpecs());
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinationsWithEmptyContextAndBodyFunctions
	 */
	public function testCallsAtDeclaringState_DeclaringSpecIsCustom_DoesNotAddTestSpecToSiblingTestSpecs($arguments)
	{
		\spectrum\constructionCommands\callBroker::internal_setDeclaringSpec(new Spec());
		$testSpec1 = call_user_func_array('\spectrum\constructionCommands\callBroker::test', $arguments);
		$testSpec2 = call_user_func_array('\spectrum\constructionCommands\callBroker::test', $arguments);
		$testSpec3 = call_user_func_array('\spectrum\constructionCommands\callBroker::test', $arguments);
		
		$this->assertSame(array(), $testSpec1->getChildSpecs());
		$this->assertSame(array(), $testSpec2->getChildSpecs());
		$this->assertSame(array(), $testSpec3->getChildSpecs());
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinations
	 */
	public function testCallsAtDeclaringState_DeclaringSpecIsRoot_AddsTestSpecToRootSpec($arguments)
	{
		$testSpec = call_user_func_array('\spectrum\constructionCommands\callBroker::test', $arguments);
		$this->assertSame(array($testSpec), \spectrum\constructionCommands\callBroker::internal_getRootSpec()->getChildSpecs());
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinations
	 */
	public function testCallsAtDeclaringState_DeclaringSpecIsNotRoot_DoesNotAddTestSpecToRootSpec($arguments)
	{
		\spectrum\constructionCommands\callBroker::internal_setDeclaringSpec(new Spec());
		call_user_func_array('\spectrum\constructionCommands\callBroker::test', $arguments);
		
		$this->assertSame(array(), \spectrum\constructionCommands\callBroker::internal_getRootSpec()->getChildSpecs());
	}
	
	public function providerNameIsString()
	{
		return $this->getProviderWithCorrectArgumentCombinationsForSpecDeclaringConstructionCommand('aaa bbb');
	}

	/**
	 * @dataProvider providerNameIsString
	 */
	public function testCallsAtDeclaringState_NameIsString_SetsNameToTestSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\constructionCommands\callBroker::internal_setDeclaringSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\constructionCommands\callBroker::test', $arguments);
		
		$this->assertSame('aaa bbb', $testSpec->getName());
		$this->assertSame(null, $parentSpec->getName());
	}
	
	public function providerContextsIsArray()
	{
		return $this->getProviderWithCorrectArgumentCombinationsForSpecDeclaringConstructionCommand(null, array(
			'aaa' => array(),
			'bbb' => array(),
			'ccc' => array(),
		));
	}

	/**
	 * @dataProvider providerContextsIsArray
	 */
	public function testCallsAtDeclaringState_ContextsIsArray_AddsContextSpecsToTestSpecAsChildSpecs($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\constructionCommands\callBroker::internal_setDeclaringSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\constructionCommands\callBroker::test', $arguments);
		
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
		return $this->getProviderWithCorrectArgumentCombinationsForSpecDeclaringConstructionCommand(null, function(){
			\spectrum\constructionCommands\callBroker::group('aaa', null, function(){
				\spectrum\constructionCommands\callBroker::group('bbb', null, function(){}, null);
				\spectrum\constructionCommands\callBroker::group('ccc', null, function(){}, null);
			}, null);
			\spectrum\constructionCommands\callBroker::group('ddd', null, function(){}, null);
			\spectrum\constructionCommands\callBroker::group('eee', null, function(){}, null);
		});
	}

	/**
	 * @dataProvider providerContextsIsFunction
	 */
	public function testCallsAtDeclaringState_ContextsIsFunction_AddsContextSpecsToTestSpecAsChildSpecs($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\constructionCommands\callBroker::internal_setDeclaringSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\constructionCommands\callBroker::test', $arguments);
		
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
		return $this->getProviderWithCorrectArgumentCombinationsForSpecDeclaringConstructionCommand(null, function(){
			\spectrum\constructionCommands\callBroker::test('aaa', null, function(){ return 'zzz'; }, null);
		});
	}

	/**
	 * @dataProvider providerContextsIsFunction2
	 */
	public function testCallsAtDeclaringState_ContextsIsFunction_AddsTestContextSpecToTestSpecWithOwnBodyFunction($arguments)
	{
		\spectrum\constructionCommands\callBroker::internal_setDeclaringSpec(new Spec());
		$testSpec = call_user_func_array('\spectrum\constructionCommands\callBroker::test', $arguments);
		
		$contextSpecs = $testSpec->getChildSpecs();
		$this->assertSame(1, count($contextSpecs));
		$this->assertSame('aaa', $contextSpecs[0]->getName());
		$this->assertSame(array(), $contextSpecs[0]->getChildSpecs());
		
		$bodyFunction = $contextSpecs[0]->testFunction->getFunction();
		$this->assertNotSame($testSpec->testFunction->getFunction(), $bodyFunction);
		$this->assertSame('zzz', $bodyFunction());
	}
	
	public function providerBodyIsFunction()
	{
		$function = function() use(&$function){ return $function; };
		return $this->getProviderWithCorrectArgumentCombinationsForSpecDeclaringConstructionCommand(null, null, $function);
	}

	/**
	 * @dataProvider providerBodyIsFunction
	 */
	public function testCallsAtDeclaringState_BodyIsFunction_AddsBodyFunctionToTestFunctionPlugin($arguments)
	{
		$testSpec = call_user_func_array('\spectrum\constructionCommands\callBroker::test', $arguments);
		$this->assertInstanceOf('\Closure', $testSpec->testFunction->getFunction());
		
		$function = $testSpec->testFunction->getFunction();
		$this->assertSame($function(), $function);
	}
	
	public function providerBodyIsFunction2()
	{
		return $this->getProviderWithCorrectArgumentCombinationsForSpecDeclaringConstructionCommand(null, null, function(){
			\spectrum\tests\Test::$temp['isCalled'] = true;
		});
	}

	/**
	 * @dataProvider providerBodyIsFunction2
	 */
	public function testCallsAtDeclaringState_BodyIsFunction_DoesNotCallBodyFunction($arguments)
	{
		\spectrum\tests\Test::$temp['isCalled'] = false;
		call_user_func_array('\spectrum\constructionCommands\callBroker::test', $arguments);
		$this->assertSame(false, \spectrum\tests\Test::$temp['isCalled']);
	}
	
	public function providerSettingsIsString()
	{
		return $this->getProviderWithCorrectArgumentCombinationsForSpecDeclaringConstructionCommand(null, null, null, 'koi8-r');
	}

	/**
	 * @dataProvider providerSettingsIsString
	 */
	public function testCallsAtDeclaringState_SettingsIsString_SetsInputCharsetToTestSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\constructionCommands\callBroker::internal_setDeclaringSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\constructionCommands\callBroker::test', $arguments);

		$this->assertSame('koi8-r', mb_strtolower($testSpec->charset->getInputCharset()));
		$this->assertNotSame('koi8-r', mb_strtolower($parentSpec->charset->getInputCharset()));
	}
	
	public function providerSettingsIsInteger()
	{
		return $this->getProviderWithCorrectArgumentCombinationsForSpecDeclaringConstructionCommand(null, null, null, 8);
	}

	/**
	 * @dataProvider providerSettingsIsInteger
	 */
	public function testCallsAtDeclaringState_SettingsIsInteger_SetsErrorHandlingLevelToTestSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\constructionCommands\callBroker::internal_setDeclaringSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\constructionCommands\callBroker::test', $arguments);

		$this->assertSame(8, $testSpec->errorHandling->getCatchPhpErrors());
		$this->assertNotSame(8, $parentSpec->errorHandling->getCatchPhpErrors());
	}
	
	public function providerSettingsIsTrue()
	{
		return $this->getProviderWithCorrectArgumentCombinationsForSpecDeclaringConstructionCommand(null, null, null, true);
	}

	/**
	 * @dataProvider providerSettingsIsTrue
	 */
	public function testCallsAtDeclaringState_SettingsIsTrue_SetsErrorHandlingLevelToTestSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\constructionCommands\callBroker::internal_setDeclaringSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\constructionCommands\callBroker::test', $arguments);

		$this->assertSame(-1, $testSpec->errorHandling->getCatchPhpErrors());
		$this->assertNotSame(-1, $parentSpec->errorHandling->getCatchPhpErrors());
	}
	
	public function providerSettingsIsFalse()
	{
		return $this->getProviderWithCorrectArgumentCombinationsForSpecDeclaringConstructionCommand(null, null, null, false);
	}

	/**
	 * @dataProvider providerSettingsIsFalse
	 */
	public function testCallsAtDeclaringState_SettingsIsFalse_SetsErrorHandlingLevelToTestSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\constructionCommands\callBroker::internal_setDeclaringSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\constructionCommands\callBroker::test', $arguments);

		$this->assertSame(0, $testSpec->errorHandling->getCatchPhpErrors());
		$this->assertNotSame(0, $parentSpec->errorHandling->getCatchPhpErrors());
	}
	
	public function providerSettingsIsArray()
	{
		return $this->getProviderWithCorrectArgumentCombinationsForSpecDeclaringConstructionCommand(null, null, null, array(
			'catchPhpErrors' => 8,
			'breakOnFirstPhpError' => true,
			'breakOnFirstMatcherFail' => true,
			'inputCharset' => 'koi8-r',
		));
	}

	/**
	 * @dataProvider providerSettingsIsArray
	 */
	public function testCallsAtDeclaringState_SettingsIsArray_SetsSettingsToTestSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\constructionCommands\callBroker::internal_setDeclaringSpec($parentSpec);
		$testSpec = call_user_func_array('\spectrum\constructionCommands\callBroker::test', $arguments);

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
	
	public function testCallsAtDeclaringState_UsingOfGetArgumentsForSpecDeclaringCommandConstructionCommand_PassesToCommandProperArguments()
	{
		config::unregisterConstructionCommands('internal_getArgumentsForSpecDeclaringCommand');
		
		$passedArguments = array();
		config::registerConstructionCommand('internal_getArgumentsForSpecDeclaringCommand', function($storage, $arguments) use(&$passedArguments){
			$passedArguments[] = $arguments;
			return array(@$arguments[0], @$arguments[1], @$arguments[2], @$arguments[3]);
		});
		
		$function1 = function(){};
		$function2 = function(){};
		\spectrum\constructionCommands\callBroker::test('aaa', $function1, $function2, array('inputCharset' => 'koi8-r'));
		
		$this->assertSame(array(array('aaa', $function1, $function2, array('inputCharset' => 'koi8-r'))), $passedArguments);
	}
	
	public function testCallsAtDeclaringState_UsingOfGetArgumentsForSpecDeclaringCommandConstructionCommand_UsesReturnOfCommandValues()
	{
		config::unregisterConstructionCommands('internal_getArgumentsForSpecDeclaringCommand');
		
		$calledFunctions = array();
		$bodyFunction = function() use(&$calledFunctions){ $calledFunctions[] = 'body'; };
		config::registerConstructionCommand('internal_getArgumentsForSpecDeclaringCommand', function() use(&$calledFunctions, $bodyFunction){
			return array(
				'bbb',
				function() use(&$calledFunctions){ $calledFunctions[] = 'contexts'; },
				$bodyFunction,
				array('inputCharset' => 'koi8-r'),
			);
		});
		
		$isCalled = false;
		$testSpec = \spectrum\constructionCommands\callBroker::test(
			'aaa', 
			function() use(&$isCalled){ $isCalled = true; }, 
			function() use(&$isCalled){ $isCalled = true; }, 
			array()
		);
		
		$this->assertSame('bbb', $testSpec->getName());
		$this->assertSame(false, $isCalled);
		$this->assertSame(array('contexts'), $calledFunctions);
		$this->assertSame($bodyFunction, $testSpec->testFunction->getFunction());
		$this->assertSame('koi8-r', $testSpec->charset->getInputCharset());
	}
	
	public function testCallsAtDeclaringState_UsingOfGetArgumentsForSpecDeclaringCommandConstructionCommand_CommandReturnsNull_ThrowsException()
	{
		config::unregisterConstructionCommands('internal_getArgumentsForSpecDeclaringCommand');
		config::registerConstructionCommand('internal_getArgumentsForSpecDeclaringCommand', function(){
			return null;
		});
		
		$this->assertThrowsException('\spectrum\constructionCommands\Exception', 'Incorrect arguments in "test" command', function(){
			\spectrum\constructionCommands\callBroker::test(null, null, function(){}, null);
		});
	}
	
/**/
	
	public function testCallsAtRunningState_ThrowsException()
	{
		\spectrum\tests\Test::$temp["exception"] = null;
		
		$this->registerPluginWithCodeInEvent('
			try
			{
				\spectrum\constructionCommands\callBroker::test();
			}
			catch (\Exception $e)
			{
				\spectrum\tests\Test::$temp["exception"] = $e;
			}
		', 'onEndingSpecExecute');
		
		callBroker::internal_getRootSpec()->run();
		
		$this->assertInstanceOf('\spectrum\constructionCommands\Exception', \spectrum\tests\Test::$temp["exception"]);
		$this->assertSame('Construction command "test" should be call only at declaring state', \spectrum\tests\Test::$temp["exception"]->getMessage());
	}
}