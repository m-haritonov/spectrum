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

class GroupTest extends \spectrum\tests\Test
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
	public function testCallsAtDeclaringState_ReturnsNewGroupSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\constructionCommands\callBroker::internal_setDeclaringSpec($parentSpec);
		
		$groupSpec1 = call_user_func_array('\spectrum\constructionCommands\callBroker::group', $arguments);
		$this->assertInstanceOf('\spectrum\core\Spec', $groupSpec1);
		$this->assertNotSame($parentSpec, $groupSpec1);
		
		$groupSpec2 = call_user_func_array('\spectrum\constructionCommands\callBroker::group', $arguments);
		$this->assertInstanceOf('\spectrum\core\Spec', $groupSpec2);
		$this->assertNotSame($parentSpec, $groupSpec2);
		$this->assertNotSame($groupSpec1, $groupSpec2);
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinations
	 */
	public function testCallsAtDeclaringState_RestoreDeclaringSpecAfterCall($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\constructionCommands\callBroker::internal_setDeclaringSpec($parentSpec);
		call_user_func_array('\spectrum\constructionCommands\callBroker::group', $arguments);
		$this->assertSame($parentSpec, \spectrum\constructionCommands\callBroker::internal_getDeclaringSpec());
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinations
	 */
	public function testCallsAtDeclaringState_DeclaringSpecIsCustom_AddsGroupSpecToDeclaringSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\constructionCommands\callBroker::internal_setDeclaringSpec($parentSpec);
		$groupSpec = call_user_func_array('\spectrum\constructionCommands\callBroker::group', $arguments);
		
		$this->assertSame(array($groupSpec), $parentSpec->getChildSpecs());
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinationsWithEmptyContextAndBodyFunctions
	 */
	public function testCallsAtDeclaringState_DeclaringSpecIsCustom_DoesNotAddGroupSpecToSiblingGroupSpecs($arguments)
	{
		\spectrum\constructionCommands\callBroker::internal_setDeclaringSpec(new Spec());
		$groupSpec1 = call_user_func_array('\spectrum\constructionCommands\callBroker::group', $arguments);
		$groupSpec2 = call_user_func_array('\spectrum\constructionCommands\callBroker::group', $arguments);
		$groupSpec3 = call_user_func_array('\spectrum\constructionCommands\callBroker::group', $arguments);
		
		$this->assertSame(array(), $groupSpec1->getChildSpecs());
		$this->assertSame(array(), $groupSpec2->getChildSpecs());
		$this->assertSame(array(), $groupSpec3->getChildSpecs());
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinations
	 */
	public function testCallsAtDeclaringState_DeclaringSpecIsRoot_AddsGroupSpecToRootSpec($arguments)
	{
		$groupSpec = call_user_func_array('\spectrum\constructionCommands\callBroker::group', $arguments);
		$this->assertSame(array($groupSpec), \spectrum\constructionCommands\callBroker::internal_getRootSpec()->getChildSpecs());
	}
	
	/**
	 * @dataProvider providerAllArgumentCombinations
	 */
	public function testCallsAtDeclaringState_DeclaringSpecIsNotRoot_DoesNotAddGroupSpecToRootSpec($arguments)
	{
		\spectrum\constructionCommands\callBroker::internal_setDeclaringSpec(new Spec());
		call_user_func_array('\spectrum\constructionCommands\callBroker::group', $arguments);
		
		$this->assertSame(array(), \spectrum\constructionCommands\callBroker::internal_getRootSpec()->getChildSpecs());
	}
	
	public function providerNameIsString()
	{
		return $this->getProviderWithCorrectArgumentCombinationsForSpecDeclaringConstructionCommand('aaa bbb');
	}

	/**
	 * @dataProvider providerNameIsString
	 */
	public function testCallsAtDeclaringState_NameIsString_SetsNameToGroupSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\constructionCommands\callBroker::internal_setDeclaringSpec($parentSpec);
		$groupSpec = call_user_func_array('\spectrum\constructionCommands\callBroker::group', $arguments);
		
		$this->assertSame('aaa bbb', $groupSpec->getName());
		$this->assertSame(null, $parentSpec->getName());
	}
	
	public function providerContextsIsArray()
	{
		return $this->getProviderWithCorrectArgumentCombinationsForSpecDeclaringConstructionCommand(null, array(
			'aaa' => array(),
			'bbb' => array(),
			'ccc' => array(),
		), function(){
			\spectrum\tests\Test::$temp[] = \spectrum\constructionCommands\callBroker::group(null, null, function(){}, null);
			\spectrum\tests\Test::$temp[] = \spectrum\constructionCommands\callBroker::test(null, null, function(){}, null);
		});
	}

	/**
	 * @dataProvider providerContextsIsArray
	 */
	public function testCallsAtDeclaringState_ContextsIsArray_AddsContextSpecsBetweenGroupSpecAndBodySpecs($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\constructionCommands\callBroker::internal_setDeclaringSpec($parentSpec);
		
		\spectrum\tests\Test::$temp = array();
		$groupSpec = call_user_func_array('\spectrum\constructionCommands\callBroker::group', $arguments);
		
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
		return $this->getProviderWithCorrectArgumentCombinationsForSpecDeclaringConstructionCommand(null, function(){
			\spectrum\constructionCommands\callBroker::group('aaa', null, function(){}, null);
			\spectrum\constructionCommands\callBroker::group('bbb', null, function(){}, null);
			\spectrum\constructionCommands\callBroker::group('ccc', null, function(){}, null);
		}, function(){
			\spectrum\tests\Test::$temp[] = \spectrum\constructionCommands\callBroker::group(null, null, function(){}, null);
			\spectrum\tests\Test::$temp[] = \spectrum\constructionCommands\callBroker::test(null, null, function(){}, null);
		});
	}

	/**
	 * @dataProvider providerContextsIsFunction
	 */
	public function testCallsAtDeclaringState_ContextsIsFunction_AddsContextSpecsBetweenGroupSpecAndBodySpecs($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\constructionCommands\callBroker::internal_setDeclaringSpec($parentSpec);
		
		\spectrum\tests\Test::$temp = array();
		$groupSpec = call_user_func_array('\spectrum\constructionCommands\callBroker::group', $arguments);
		
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
		return $this->getProviderWithCorrectArgumentCombinationsForSpecDeclaringConstructionCommand(null, function(){
			\spectrum\constructionCommands\callBroker::group('aaa', null, function(){
				\spectrum\constructionCommands\callBroker::group('bbb', null, function(){}, null);
				\spectrum\constructionCommands\callBroker::group('ccc', null, function(){}, null);
			}, null);
			\spectrum\constructionCommands\callBroker::group('ddd', null, function(){}, null);
		}, function(){
			\spectrum\tests\Test::$temp[] = \spectrum\constructionCommands\callBroker::group(null, null, function(){}, null);
			\spectrum\tests\Test::$temp[] = \spectrum\constructionCommands\callBroker::test(null, null, function(){}, null);
		});
	}

	/**
	 * @dataProvider providerContextsIsFunction2
	 */
	public function testCallsAtDeclaringState_ContextsIsFunction_AddsBodySpecsToEndingContextSpecsOnly($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\constructionCommands\callBroker::internal_setDeclaringSpec($parentSpec);
		
		\spectrum\tests\Test::$temp = array();
		$groupSpec = call_user_func_array('\spectrum\constructionCommands\callBroker::group', $arguments);
		
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
		return $this->getProviderWithCorrectArgumentCombinationsForSpecDeclaringConstructionCommand(null, function(){
			\spectrum\constructionCommands\callBroker::group('aaa', null, function(){}, null);
			\spectrum\constructionCommands\callBroker::group('bbb', null, function(){}, null);
			\spectrum\constructionCommands\callBroker::test('ccc', null, function(){}, null);
		}, function(){
			\spectrum\tests\Test::$temp[] = \spectrum\constructionCommands\callBroker::group(null, null, function(){}, null);
			\spectrum\tests\Test::$temp[] = \spectrum\constructionCommands\callBroker::test(null, null, function(){}, null);
		});
	}

	/**
	 * @dataProvider providerContextsIsFunction3
	 */
	public function testCallsAtDeclaringState_ContextsIsFunction_DoesNotAddAnySpecsToTestContextSpecs($arguments)
	{
		\spectrum\constructionCommands\callBroker::internal_setDeclaringSpec(new Spec());
		
		\spectrum\tests\Test::$temp = array();
		$groupSpec = call_user_func_array('\spectrum\constructionCommands\callBroker::group', $arguments);
		
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
		return $this->getProviderWithCorrectArgumentCombinationsForSpecDeclaringConstructionCommand(null, array(), function(){
			\spectrum\tests\Test::$temp[] = \spectrum\constructionCommands\callBroker::group('aaa', null, function(){}, null);
			\spectrum\tests\Test::$temp[] = \spectrum\constructionCommands\callBroker::group('bbb', null, function(){}, null);
			\spectrum\tests\Test::$temp[] = \spectrum\constructionCommands\callBroker::test('ccc', null, function(){}, null);
			\spectrum\tests\Test::$temp[] = \spectrum\constructionCommands\callBroker::test('ddd', null, function(){}, null);
			\spectrum\tests\Test::$temp[] = \spectrum\constructionCommands\callBroker::group('eee', null, function(){}, null);
		});
	}

	/**
	 * @dataProvider providerBodyIsFunction
	 */
	public function testCallsAtDeclaringState_BodyIsFunction_AddsBodySpecsToGroupSpec($arguments)
	{
		\spectrum\tests\Test::$temp = array();
		$groupSpec = call_user_func_array('\spectrum\constructionCommands\callBroker::group', $arguments);

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
		return $this->getProviderWithCorrectArgumentCombinationsForSpecDeclaringConstructionCommand('aaa', array(), function(){
			\spectrum\constructionCommands\callBroker::group('bbb', null, function(){
				\spectrum\constructionCommands\callBroker::test('ccc', null, function(){}, null);
			}, null);
			
			\spectrum\constructionCommands\callBroker::group('ddd', null, function(){
				\spectrum\constructionCommands\callBroker::group('eee', null, function(){
					\spectrum\constructionCommands\callBroker::test('fff', null, function(){}, null);
				}, null);
				
				\spectrum\constructionCommands\callBroker::group('ggg', null, function(){
					\spectrum\constructionCommands\callBroker::test('hhh', null, function(){}, null);
					\spectrum\constructionCommands\callBroker::test('iii', null, function(){}, null);
				}, null);
			}, null);
		});
	}

	/**
	 * @dataProvider providerBodyIsFunction2
	 */
	public function testCallsAtDeclaringState_BodyIsFunction_AddsDescendantSpecsOfBodySpecsToHisParents($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\constructionCommands\callBroker::internal_setDeclaringSpec($parentSpec);
		$groupSpec = call_user_func_array('\spectrum\constructionCommands\callBroker::group', $arguments);

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
		return $this->getProviderWithCorrectArgumentCombinationsForSpecDeclaringConstructionCommand(null, null, function(){
			\spectrum\tests\Test::$temp[] = \spectrum\constructionCommands\callBroker::group('aaa', null, function(){}, null);
			\spectrum\tests\Test::$temp[] = \spectrum\constructionCommands\callBroker::test('bbb', null, function(){}, null);
		}, 'koi8-r');
	}

	/**
	 * @dataProvider providerSettingsIsString
	 */
	public function testCallsAtDeclaringState_SettingsIsString_SetsInputCharsetToGroupSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\constructionCommands\callBroker::internal_setDeclaringSpec($parentSpec);
		
		\spectrum\tests\Test::$temp = array();
		$groupSpec = call_user_func_array('\spectrum\constructionCommands\callBroker::group', $arguments);

		$this->assertSame('koi8-r', mb_strtolower($groupSpec->charset->getInputCharset()));
		$this->assertNotSame('koi8-r', mb_strtolower($parentSpec->charset->getInputCharset()));
		$this->assertNotSame('koi8-r', mb_strtolower(\spectrum\tests\Test::$temp[0]->charset->getInputCharset()));
		$this->assertNotSame('koi8-r', mb_strtolower(\spectrum\tests\Test::$temp[1]->charset->getInputCharset()));
	}
	
	public function providerSettingsIsInteger()
	{
		return $this->getProviderWithCorrectArgumentCombinationsForSpecDeclaringConstructionCommand(null, null, function(){
			\spectrum\tests\Test::$temp[] = \spectrum\constructionCommands\callBroker::group('aaa', null, function(){}, null);
			\spectrum\tests\Test::$temp[] = \spectrum\constructionCommands\callBroker::test('bbb', null, function(){}, null);
		}, 8);
	}

	/**
	 * @dataProvider providerSettingsIsInteger
	 */
	public function testCallsAtDeclaringState_SettingsIsInteger_SetsErrorHandlingLevelToGroupSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\constructionCommands\callBroker::internal_setDeclaringSpec($parentSpec);
		
		\spectrum\tests\Test::$temp = array();
		$groupSpec = call_user_func_array('\spectrum\constructionCommands\callBroker::group', $arguments);

		$this->assertSame(8, $groupSpec->errorHandling->getCatchPhpErrors());
		$this->assertNotSame(8, $parentSpec->errorHandling->getCatchPhpErrors());
		$this->assertNotSame(8, \spectrum\tests\Test::$temp[0]->errorHandling->getCatchPhpErrors());
		$this->assertNotSame(8, \spectrum\tests\Test::$temp[1]->errorHandling->getCatchPhpErrors());
	}
	
	public function providerSettingsIsTrue()
	{
		return $this->getProviderWithCorrectArgumentCombinationsForSpecDeclaringConstructionCommand(null, null, function(){
			\spectrum\tests\Test::$temp[] = \spectrum\constructionCommands\callBroker::group('aaa', null, function(){}, null);
			\spectrum\tests\Test::$temp[] = \spectrum\constructionCommands\callBroker::test('bbb', null, function(){}, null);
		}, true);
	}

	/**
	 * @dataProvider providerSettingsIsTrue
	 */
	public function testCallsAtDeclaringState_SettingsIsTrue_SetsErrorHandlingLevelToGroupSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\constructionCommands\callBroker::internal_setDeclaringSpec($parentSpec);
		
		\spectrum\tests\Test::$temp = array();
		$groupSpec = call_user_func_array('\spectrum\constructionCommands\callBroker::group', $arguments);

		$this->assertSame(-1, $groupSpec->errorHandling->getCatchPhpErrors());
		$this->assertNotSame(-1, $parentSpec->errorHandling->getCatchPhpErrors());
		$this->assertNotSame(-1, \spectrum\tests\Test::$temp[0]->errorHandling->getCatchPhpErrors());
		$this->assertNotSame(-1, \spectrum\tests\Test::$temp[1]->errorHandling->getCatchPhpErrors());
	}
	
	public function providerSettingsIsFalse()
	{
		return $this->getProviderWithCorrectArgumentCombinationsForSpecDeclaringConstructionCommand(null, null, function(){
			\spectrum\tests\Test::$temp[] = \spectrum\constructionCommands\callBroker::group('aaa', null, function(){}, null);
			\spectrum\tests\Test::$temp[] = \spectrum\constructionCommands\callBroker::test('bbb', null, function(){}, null);
		}, false);
	}

	/**
	 * @dataProvider providerSettingsIsFalse
	 */
	public function testCallsAtDeclaringState_SettingsIsFalse_SetsErrorHandlingLevelToGroupSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\constructionCommands\callBroker::internal_setDeclaringSpec($parentSpec);
		
		\spectrum\tests\Test::$temp = array();
		$groupSpec = call_user_func_array('\spectrum\constructionCommands\callBroker::group', $arguments);

		$this->assertSame(0, $groupSpec->errorHandling->getCatchPhpErrors());
		$this->assertNotSame(0, $parentSpec->errorHandling->getCatchPhpErrors());
		$this->assertNotSame(0, \spectrum\tests\Test::$temp[0]->errorHandling->getCatchPhpErrors());
		$this->assertNotSame(0, \spectrum\tests\Test::$temp[1]->errorHandling->getCatchPhpErrors());
	}
	
	public function providerSettingsIsArray()
	{
		return $this->getProviderWithCorrectArgumentCombinationsForSpecDeclaringConstructionCommand(null, null, function(){
			\spectrum\tests\Test::$temp[] = \spectrum\constructionCommands\callBroker::group('aaa', null, function(){}, null);
			\spectrum\tests\Test::$temp[] = \spectrum\constructionCommands\callBroker::test('bbb', null, function(){}, null);
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
	public function testCallsAtDeclaringState_SettingsIsArray_SetsSettingsToGroupSpec($arguments)
	{
		$parentSpec = new Spec();
		\spectrum\constructionCommands\callBroker::internal_setDeclaringSpec($parentSpec);
		
		\spectrum\tests\Test::$temp = array();
		$groupSpec = call_user_func_array('\spectrum\constructionCommands\callBroker::group', $arguments);

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
		\spectrum\constructionCommands\callBroker::group('aaa', $function1, $function2, array('inputCharset' => 'koi8-r'));
		
		$this->assertSame(array(array('aaa', $function1, $function2, array('inputCharset' => 'koi8-r'))), $passedArguments);
	}
	
	public function testCallsAtDeclaringState_UsingOfGetArgumentsForSpecDeclaringCommandConstructionCommand_UsesReturnOfCommandValues()
	{
		config::unregisterConstructionCommands('internal_getArgumentsForSpecDeclaringCommand');
		
		$calledFunctions = array();
		config::registerConstructionCommand('internal_getArgumentsForSpecDeclaringCommand', function() use(&$calledFunctions){
			return array(
				'bbb', 
				function() use(&$calledFunctions){ $calledFunctions[] = 'contexts'; }, 
				function() use(&$calledFunctions){ $calledFunctions[] = 'body'; }, 
				array('inputCharset' => 'koi8-r'),
			);
		});
		
		$isCalled = false;
		$groupSpec = \spectrum\constructionCommands\callBroker::group(
			'aaa', 
			function() use(&$isCalled){ $isCalled = true; }, 
			function() use(&$isCalled){ $isCalled = true; }, 
			array()
		);
		
		$this->assertSame('bbb', $groupSpec->getName());
		$this->assertSame(false, $isCalled);
		$this->assertSame(array('contexts', 'body'), $calledFunctions);
		$this->assertSame('koi8-r', $groupSpec->charset->getInputCharset());
	}
	
	public function testCallsAtDeclaringState_UsingOfGetArgumentsForSpecDeclaringCommandConstructionCommand_CommandReturnsNull_ThrowsException()
	{
		config::unregisterConstructionCommands('internal_getArgumentsForSpecDeclaringCommand');
		config::registerConstructionCommand('internal_getArgumentsForSpecDeclaringCommand', function(){
			return null;
		});
		
		$this->assertThrowsException('\spectrum\constructionCommands\Exception', 'Incorrect arguments in "group" command', function(){
			\spectrum\constructionCommands\callBroker::group(null, null, function(){}, null);
		});
	}
	
/**/
	
	public function testCallsAtRunningState_ThrowsException()
	{
		\spectrum\tests\Test::$temp["exception"] = null;
		
		$this->registerPluginWithCodeInEvent('
			try
			{
				\spectrum\constructionCommands\callBroker::group();
			}
			catch (\Exception $e)
			{
				\spectrum\tests\Test::$temp["exception"] = $e;
			}
		', 'onEndingSpecExecute');
		
		callBroker::internal_getRootSpec()->run();
		
		$this->assertInstanceOf('\spectrum\constructionCommands\Exception', \spectrum\tests\Test::$temp["exception"]);
		$this->assertSame('Construction command "group" should be call only at declaring state', \spectrum\tests\Test::$temp["exception"]->getMessage());
	}
}