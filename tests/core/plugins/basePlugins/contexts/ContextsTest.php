<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core\plugins\basePlugins\contexts;

use spectrum\config;
use spectrum\core\Spec;

require_once __DIR__ . '/../../../../init.php';

class ContextsTest extends \spectrum\tests\Test
{
	public function testAdd_AddsFunctionAndType()
	{
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		$function4 = function(){};
		
		$spec = new Spec();
		$spec->contexts->add($function1, 'before');
		$spec->contexts->add($function2, 'after');
		$spec->contexts->add($function3, 'before');
		$spec->contexts->add($function4, 'after');
		
		$this->assertSame(array(
			array('function' => $function1, 'type' => 'before'),
			array('function' => $function2, 'type' => 'after'),
			array('function' => $function3, 'type' => 'before'),
			array('function' => $function4, 'type' => 'after'),
		), $spec->contexts->getAll());
	}
	
	public function testAdd_ConvertsTypeToLowercase()
	{
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->contexts->add($function1, 'beFOre');
		$spec->contexts->add($function2, 'AFTer');
		
		$this->assertSame(array(
			array('function' => $function1, 'type' => 'before'),
			array('function' => $function2, 'type' => 'after'),
		), $spec->contexts->getAll());
	}
	
	public function testAdd_CallOnRun_ThrowsExceptionAndDoesNotAddValue()
	{
		\spectrum\tests\Test::$temp["exception"] = null;
		
		$this->registerPluginWithCodeInEvent('
			try
			{
				$this->getOwnerSpec()->contexts->add(function(){}, "before");
			}
			catch (\Exception $e)
			{
				\spectrum\tests\Test::$temp["exception"] = $e;
			}
		');
		

		$spec = new Spec();
		$spec->run();
		$this->assertInstanceOf('\spectrum\core\plugins\Exception', \spectrum\tests\Test::$temp["exception"]);
		$this->assertSame('Call of "\spectrum\core\plugins\basePlugins\contexts\Contexts::add" method is forbidden on run', \spectrum\tests\Test::$temp["exception"]->getMessage());
		$this->assertSame(array(), $spec->contexts->getAll());
	}
	
	public function testAdd_ThrowsExceptionForInvalidTypes()
	{
		$spec = new Spec();
		$this->assertThrowsException('\spectrum\core\plugins\Exception', 'Unknown type "aaa" in plugin "contexts"', function() use($spec){
			$spec->contexts->add(function(){}, 'aaa');
		});
	}

/**/
	
	public function testGetAll_ThrowsExceptionForInvalidTypes()
	{
		$spec = new Spec();
		$this->assertThrowsException('\spectrum\core\plugins\Exception', 'Unknown type "aaa" in plugin "contexts"', function() use($spec){
			$spec->contexts->getAll('aaa');
		});
	}
	
/**/
	
	public function testGetAll_TypeIsNull_ReturnsAllRowsWithAnyType()
	{
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		$function4 = function(){};
		
		$spec = new Spec();
		$spec->contexts->add($function1, 'before');
		$spec->contexts->add($function2, 'after');
		$spec->contexts->add($function3, 'before');
		$spec->contexts->add($function4, 'after');
		
		$this->assertSame(array(
			array('function' => $function1, 'type' => 'before'),
			array('function' => $function2, 'type' => 'after'),
			array('function' => $function3, 'type' => 'before'),
			array('function' => $function4, 'type' => 'after'),
		), $spec->contexts->getAll());
	}
	
	public function testGetAll_TypeIsNull_ReturnsEmptyArrayWhenNoRows()
	{
		$spec = new Spec();
		$this->assertSame(array(), $spec->contexts->getAll());
		
		$spec->contexts->add(function(){}, 'before');
		$spec->contexts->remove(0);
		$this->assertSame(array(), $spec->contexts->getAll());
		
		$spec->contexts->add(function(){}, 'before');
		$spec->contexts->removeAll();
		$this->assertSame(array(), $spec->contexts->getAll());
	}
	
/**/
	
	public function testGetAll_TypeIsBefore_ReturnsAllRowsWithSameType()
	{
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->contexts->add($function1, 'before');
		$spec->contexts->add(function(){}, 'after');
		$spec->contexts->add($function2, 'before');
		$spec->contexts->add(function(){}, 'after');
		
		$this->assertSame(array(
			0 => array('function' => $function1, 'type' => 'before'),
			2 => array('function' => $function2, 'type' => 'before'),
		), $spec->contexts->getAll('before'));
	}
	
	public function testGetAll_TypeIsBefore_PreservesIndexes()
	{
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		
		$spec = new Spec();
		$spec->contexts->add($function1, 'before');
		$spec->contexts->add(function(){}, 'after');
		$spec->contexts->add($function2, 'before');
		$spec->contexts->add(function(){}, 'after');
		$spec->contexts->add(function(){}, 'after');
		$spec->contexts->add($function3, 'before');
		
		$this->assertSame(array(
			0 => array('function' => $function1, 'type' => 'before'),
			2 => array('function' => $function2, 'type' => 'before'),
			5 => array('function' => $function3, 'type' => 'before'),
		), $spec->contexts->getAll('before'));
	}
	
	public function testGetAll_TypeIsBefore_IgnoresTypeCase()
	{
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->contexts->add($function1, 'before');
		$spec->contexts->add($function2, 'before');
		
		$this->assertSame(array(
			0 => array('function' => $function1, 'type' => 'before'),
			1 => array('function' => $function2, 'type' => 'before'),
		), $spec->contexts->getAll('BEFOre'));
	}
	
	public function testGetAll_TypeIsBefore_ReturnsEmptyArrayWhenNoRows()
	{
		$spec = new Spec();
		$this->assertSame(array(), $spec->contexts->getAll('before'));
		
		$spec->contexts->add(function(){}, 'before');
		$spec->contexts->remove(0);
		$this->assertSame(array(), $spec->contexts->getAll('before'));
		
		$spec->contexts->add(function(){}, 'before');
		$spec->contexts->removeAll();
		$this->assertSame(array(), $spec->contexts->getAll('before'));
	}
	
/**/
	
	public function testGetAll_TypeIsAfter_ReturnsAllRowsWithSameType()
	{
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->contexts->add($function1, 'after');
		$spec->contexts->add(function(){}, 'before');
		$spec->contexts->add($function2, 'after');
		$spec->contexts->add(function(){}, 'before');
		
		$this->assertSame(array(
			0 => array('function' => $function1, 'type' => 'after'),
			2 => array('function' => $function2, 'type' => 'after'),
		), $spec->contexts->getAll('after'));
	}
	
	public function testGetAll_TypeIsAfter_PreservesIndexes()
	{
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		
		$spec = new Spec();
		$spec->contexts->add($function1, 'after');
		$spec->contexts->add(function(){}, 'before');
		$spec->contexts->add($function2, 'after');
		$spec->contexts->add(function(){}, 'before');
		$spec->contexts->add(function(){}, 'before');
		$spec->contexts->add($function3, 'after');
		
		$this->assertSame(array(
			0 => array('function' => $function1, 'type' => 'after'),
			2 => array('function' => $function2, 'type' => 'after'),
			5 => array('function' => $function3, 'type' => 'after'),
		), $spec->contexts->getAll('after'));
	}
	
	public function testGetAll_TypeIsAfter_IgnoresTypeCase()
	{
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->contexts->add($function1, 'after');
		$spec->contexts->add($function2, 'after');
		
		$this->assertSame(array(
			0 => array('function' => $function1, 'type' => 'after'),
			1 => array('function' => $function2, 'type' => 'after'),
		), $spec->contexts->getAll('AFTer'));
	}
	
	public function testGetAll_TypeIsAfter_ReturnsEmptyArrayWhenNoRows()
	{
		$spec = new Spec();
		$this->assertSame(array(), $spec->contexts->getAll('after'));
		
		$spec->contexts->add(function(){}, 'after');
		$spec->contexts->remove(0);
		$this->assertSame(array(), $spec->contexts->getAll('after'));
		
		$spec->contexts->add(function(){}, 'after');
		$spec->contexts->removeAll();
		$this->assertSame(array(), $spec->contexts->getAll('after'));
	}
	
/**/
	
	public function testGetAllThroughRunningAncestors_ThrowsExceptionForInvalidTypes()
	{
		$spec = new Spec();
		$this->assertThrowsException('\spectrum\core\plugins\Exception', 'Unknown type "aaa" in plugin "contexts"', function() use($spec){
			$spec->contexts->getAllThroughRunningAncestors('aaa');
		});
	}
	
/**/
	
	public function testGetAllThroughRunningAncestors_TypeIsBefore_ReturnsAllRowsWithSameType()
	{
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->contexts->add($function1, 'before');
		$spec->contexts->add(function(){}, 'after');
		$spec->contexts->add($function2, 'before');
		$spec->contexts->add(function(){}, 'after');
		
		$this->assertSame(array(
			array('function' => $function1, 'type' => 'before'),
			array('function' => $function2, 'type' => 'before'),
		), $spec->contexts->getAllThroughRunningAncestors('before'));
	}
	
	public function testGetAllThroughRunningAncestors_TypeIsBefore_DoesNotPreserveIndexes()
	{
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		
		$spec = new Spec();
		$spec->contexts->add($function1, 'before');
		$spec->contexts->add(function(){}, 'after');
		$spec->contexts->add($function2, 'before');
		$spec->contexts->add(function(){}, 'after');
		$spec->contexts->add(function(){}, 'after');
		$spec->contexts->add($function3, 'before');
		
		$this->assertSame(array(
			array('function' => $function1, 'type' => 'before'),
			array('function' => $function2, 'type' => 'before'),
			array('function' => $function3, 'type' => 'before'),
		), $spec->contexts->getAllThroughRunningAncestors('before'));
	}
	
	public function testGetAllThroughRunningAncestors_TypeIsBefore_ReturnsRowsInOrderFromRootRunningAncestorToSelf()
	{
		\spectrum\tests\Test::$temp["returnValues"] = array();
		
		$this->registerPluginWithCodeInEvent('
			if ($this->getOwnerSpec() === \spectrum\tests\Test::$temp["specs"]["checkpoint"])
				\spectrum\tests\Test::$temp["returnValues"][] = $this->getOwnerSpec()->contexts->getAllThroughRunningAncestors("before");
		');
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsTree('
			Spec
			->Spec
			->Spec
			->->Spec(checkpoint)
		', array(1 => 'checkpoint'));
		
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		$function4 = function(){};
		$function5 = function(){};
		$function6 = function(){};
		$function7 = function(){};
		$function8 = function(){};
		
		\spectrum\tests\Test::$temp["specs"][0]->contexts->add($function1, 'before');
		\spectrum\tests\Test::$temp["specs"][0]->contexts->add($function2, 'before');
		\spectrum\tests\Test::$temp["specs"][1]->contexts->add($function3, 'before');
		\spectrum\tests\Test::$temp["specs"][1]->contexts->add($function4, 'before');
		\spectrum\tests\Test::$temp["specs"][2]->contexts->add($function5, 'before');
		\spectrum\tests\Test::$temp["specs"][2]->contexts->add($function6, 'before');
		\spectrum\tests\Test::$temp["specs"]['checkpoint']->contexts->add($function7, 'before');
		\spectrum\tests\Test::$temp["specs"]['checkpoint']->contexts->add($function8, 'before');
		
		\spectrum\tests\Test::$temp["specs"][0]->run();
		$this->assertSame(array(
			array(
				array('function' => $function1, 'type' => 'before'),
				array('function' => $function2, 'type' => 'before'),
				array('function' => $function3, 'type' => 'before'),
				array('function' => $function4, 'type' => 'before'),
				array('function' => $function7, 'type' => 'before'),
				array('function' => $function8, 'type' => 'before'),
			),
			array(
				array('function' => $function1, 'type' => 'before'),
				array('function' => $function2, 'type' => 'before'),
				array('function' => $function5, 'type' => 'before'),
				array('function' => $function6, 'type' => 'before'),
				array('function' => $function7, 'type' => 'before'),
				array('function' => $function8, 'type' => 'before'),
			),
		), \spectrum\tests\Test::$temp["returnValues"]);
	}
	
	public function testGetAllThroughRunningAncestors_TypeIsBefore_IgnoresTypeCase()
	{
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->contexts->add($function1, 'before');
		$spec->contexts->add($function2, 'before');
		
		$this->assertSame(array(
			array('function' => $function1, 'type' => 'before'),
			array('function' => $function2, 'type' => 'before'),
		), $spec->contexts->getAllThroughRunningAncestors('BEFOre'));
	}
	
	public function testGetAllThroughRunningAncestors_TypeIsBefore_ReturnsEmptyArrayWhenNoRows()
	{
		$spec = new Spec();
		$this->assertSame(array(), $spec->contexts->getAllThroughRunningAncestors('before'));
		
		$spec->contexts->add(function(){}, 'before');
		$spec->contexts->remove(0);
		$this->assertSame(array(), $spec->contexts->getAllThroughRunningAncestors('before'));
		
		$spec->contexts->add(function(){}, 'before');
		$spec->contexts->removeAll();
		$this->assertSame(array(), $spec->contexts->getAllThroughRunningAncestors('before'));
	}
	
/**/
	
	public function testGetAllThroughRunningAncestors_TypeIsAfter_ReturnsAllRowsWithSameType()
	{
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->contexts->add($function1, 'after');
		$spec->contexts->add(function(){}, 'before');
		$spec->contexts->add($function2, 'after');
		$spec->contexts->add(function(){}, 'before');
		
		$this->assertSame(array(
			array('function' => $function2, 'type' => 'after'),
			array('function' => $function1, 'type' => 'after'),
		), $spec->contexts->getAllThroughRunningAncestors('after'));
	}
	
	public function testGetAllThroughRunningAncestors_TypeIsAfter_DoesNotPreserveIndexes()
	{
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		
		$spec = new Spec();
		$spec->contexts->add($function1, 'after');
		$spec->contexts->add(function(){}, 'before');
		$spec->contexts->add($function2, 'after');
		$spec->contexts->add(function(){}, 'before');
		$spec->contexts->add(function(){}, 'before');
		$spec->contexts->add($function3, 'after');
		
		$this->assertSame(array(
			array('function' => $function3, 'type' => 'after'),
			array('function' => $function2, 'type' => 'after'),
			array('function' => $function1, 'type' => 'after'),
		), $spec->contexts->getAllThroughRunningAncestors('after'));
	}
	
	public function testGetAllThroughRunningAncestors_TypeIsAfter_ReturnsRowsInOrderFromSelfToRootRunningAncestor()
	{
		\spectrum\tests\Test::$temp["returnValues"] = array();
		
		$this->registerPluginWithCodeInEvent('
			if ($this->getOwnerSpec() === \spectrum\tests\Test::$temp["specs"]["checkpoint"])
				\spectrum\tests\Test::$temp["returnValues"][] = $this->getOwnerSpec()->contexts->getAllThroughRunningAncestors("after");
		');
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsTree('
			Spec
			->Spec
			->Spec
			->->Spec(checkpoint)
		', array(1 => 'checkpoint'));
		
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		$function4 = function(){};
		$function5 = function(){};
		$function6 = function(){};
		$function7 = function(){};
		$function8 = function(){};
		
		\spectrum\tests\Test::$temp["specs"][0]->contexts->add($function1, 'after');
		\spectrum\tests\Test::$temp["specs"][0]->contexts->add($function2, 'after');
		\spectrum\tests\Test::$temp["specs"][1]->contexts->add($function3, 'after');
		\spectrum\tests\Test::$temp["specs"][1]->contexts->add($function4, 'after');
		\spectrum\tests\Test::$temp["specs"][2]->contexts->add($function5, 'after');
		\spectrum\tests\Test::$temp["specs"][2]->contexts->add($function6, 'after');
		\spectrum\tests\Test::$temp["specs"]['checkpoint']->contexts->add($function7, 'after');
		\spectrum\tests\Test::$temp["specs"]['checkpoint']->contexts->add($function8, 'after');
		
		\spectrum\tests\Test::$temp["specs"][0]->run();
		$this->assertSame(array(
			array(
				array('function' => $function8, 'type' => 'after'),
				array('function' => $function7, 'type' => 'after'),
				array('function' => $function4, 'type' => 'after'),
				array('function' => $function3, 'type' => 'after'),
				array('function' => $function2, 'type' => 'after'),
				array('function' => $function1, 'type' => 'after'),
				
			),
			array(
				array('function' => $function8, 'type' => 'after'),
				array('function' => $function7, 'type' => 'after'),
				array('function' => $function6, 'type' => 'after'),
				array('function' => $function5, 'type' => 'after'),
				array('function' => $function2, 'type' => 'after'),
				array('function' => $function1, 'type' => 'after'),
			),
		), \spectrum\tests\Test::$temp["returnValues"]);
	}
	
	public function testGetAllThroughRunningAncestors_TypeIsAfter_IgnoresTypeCase()
	{
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->contexts->add($function1, 'after');
		$spec->contexts->add($function2, 'after');
		
		$this->assertSame(array(
			array('function' => $function2, 'type' => 'after'),
			array('function' => $function1, 'type' => 'after'),
		), $spec->contexts->getAllThroughRunningAncestors('AFTer'));
	}
	
	public function testGetAllThroughRunningAncestors_TypeIsAfter_ReturnsEmptyArrayWhenNoRows()
	{
		$spec = new Spec();
		$this->assertSame(array(), $spec->contexts->getAllThroughRunningAncestors('after'));
		
		$spec->contexts->add(function(){}, 'after');
		$spec->contexts->remove(0);
		$this->assertSame(array(), $spec->contexts->getAllThroughRunningAncestors('after'));
		
		$spec->contexts->add(function(){}, 'after');
		$spec->contexts->removeAll();
		$this->assertSame(array(), $spec->contexts->getAllThroughRunningAncestors('after'));
	}
	
/**/
	
	public function testRemove_RemovesValueByIndex()
	{
		$spec = new Spec();
		$spec->contexts->add(function(){}, 'before');
		$spec->contexts->add(function(){}, 'after');
		$spec->contexts->remove(0);
		$spec->contexts->remove(1);
		$this->assertSame(array(), $spec->contexts->getAll());
	}

	public function testRemove_PreventsIndexes()
	{
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		$function4 = function(){};
		
		$spec = new Spec();
		$spec->contexts->add($function1, 'before');
		$spec->contexts->add($function2, 'after');
		$spec->contexts->add($function3, 'before');
		
		$spec->contexts->remove(1);
		$this->assertSame(array(
			0 => array('function' => $function1, 'type' => 'before'),
			2 => array('function' => $function3, 'type' => 'before'),
		), $spec->contexts->getAll());
		
		$spec->contexts->remove(0);
		$this->assertSame(array(
			2 => array('function' => $function3, 'type' => 'before'),
		), $spec->contexts->getAll());
		
		$spec->contexts->remove(2);
		$this->assertSame(array(), $spec->contexts->getAll());
		
		$spec->contexts->add($function4, 'before');
		$this->assertSame(array(
			3 => array('function' => $function4, 'type' => 'before'),
		), $spec->contexts->getAll());
	}
	
	public function testRemove_CallOnRun_ThrowsExceptionAndDoesNotRemoveValue()
	{
		\spectrum\tests\Test::$temp["exception"] = null;
		
		$this->registerPluginWithCodeInEvent('
			try
			{
				$this->getOwnerSpec()->contexts->remove(0);
			}
			catch (\Exception $e)
			{
				\spectrum\tests\Test::$temp["exception"] = $e;
			}
		');
		
		$function1 = function(){};

		$spec = new Spec();
		$spec->contexts->add($function1, 'before');
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\core\plugins\Exception', \spectrum\tests\Test::$temp["exception"]);
		$this->assertSame('Call of "\spectrum\core\plugins\basePlugins\contexts\Contexts::remove" method is forbidden on run', \spectrum\tests\Test::$temp["exception"]->getMessage());
		$this->assertSame(array(
			array('function' => $function1, 'type' => 'before'), 
		), $spec->contexts->getAll());
	}
	
/**/
	
	public function testRemoveAll_RemovesAllValues()
	{
		$spec = new Spec();
		$spec->contexts->add(function(){}, 'before');
		$spec->contexts->add(function(){}, 'after');
		$spec->contexts->removeAll();
		$this->assertSame(array(), $spec->contexts->getAll());
	}

	public function testRemoveAll_DoesNotPreventIndexes()
	{
		$spec = new Spec();
		
		$spec->contexts->add(function(){}, 'before');
		$spec->contexts->add(function(){}, 'after');
		$spec->contexts->removeAll();
		$this->assertSame(array(), $spec->contexts->getAll());
		
		$function1 = function(){};
		$spec->contexts->add($function1, 'before');
		$this->assertSame(array(
			0 => array('function' => $function1, 'type' => 'before'),
		), $spec->contexts->getAll());
	}
	
	public function testRemoveAll_CallOnRun_ThrowsExceptionAndDoesNotRemoveValues()
	{
		\spectrum\tests\Test::$temp["exception"] = null;
		
		$this->registerPluginWithCodeInEvent('
			try
			{
				$this->getOwnerSpec()->contexts->removeAll();
			}
			catch (\Exception $e)
			{
				\spectrum\tests\Test::$temp["exception"] = $e;
			}
		');
		
		$function1 = function(){};

		$spec = new Spec();
		$spec->contexts->add($function1, 'before');
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\core\plugins\Exception', \spectrum\tests\Test::$temp["exception"]);
		$this->assertSame('Call of "\spectrum\core\plugins\basePlugins\contexts\Contexts::removeAll" method is forbidden on run', \spectrum\tests\Test::$temp["exception"]->getMessage());
		$this->assertSame(array(
			array('function' => $function1, 'type' => 'before'), 
		), $spec->contexts->getAll());
	}
	
/**/
	
	public function testCallFunctionInContext_CallsFunctionWithPassedArguments()
	{
		\spectrum\tests\Test::$temp["passedArguments"] = array();
		
		$this->registerPluginWithCodeInEvent('
			$this->getOwnerSpec()->contexts->callFunctionInContext(function(){
				\spectrum\tests\Test::$temp["passedArguments"][] = func_get_args();
			}, array("aaa", "bbb", "ccc"));
		', 'onEndingSpecExecute');
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame(array(array("aaa", "bbb", "ccc")), \spectrum\tests\Test::$temp["passedArguments"]);
	}
	
	public function testCallFunctionInContext_ReturnsFunctionReturnValue()
	{
		\spectrum\tests\Test::$temp["callResults"] = array();
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["callResults"][] = $this->getOwnerSpec()->contexts->callFunctionInContext(function(){
				return "aaa";
			});
		', 'onEndingSpecExecute');
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame(array("aaa"), \spectrum\tests\Test::$temp["callResults"]);
	}
	
	public function testCallFunctionInContext_PhpIsGreaterThanOrEqualTo54_BindContextDataToThisVariable()
	{
		if (version_compare(PHP_VERSION, '5.4', '<'))
			return;
		
		\spectrum\tests\Test::$temp["contextData"] = null;
		\spectrum\tests\Test::$temp["thisValue"] = null;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["contextData"] = $this->getOwnerSpec()->contexts->getContextData();
			$this->getOwnerSpec()->contexts->callFunctionInContext(function(){
				\spectrum\tests\Test::$temp["thisValue"] = $this;
			});
		', 'onEndingSpecExecute');
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\core\plugins\basePlugins\contexts\Data', \spectrum\tests\Test::$temp["this"]);
		$this->assertSame(\spectrum\tests\Test::$temp["contextData"], \spectrum\tests\Test::$temp["this"]);
	}
	
	public function testCallFunctionInContext_CallBeforeContextDataInitialization_ThrowsExceptionAndDoesNotCallFunction()
	{
		\spectrum\tests\Test::$temp["exception"] = null;
		\spectrum\tests\Test::$temp["callCount"] = 0;
		
		$this->registerPluginWithCodeInEvent('
			try
			{
				$this->getOwnerSpec()->contexts->callFunctionInContext(function(){
					\spectrum\tests\Test::$temp["callCount"]++;
				});
			}
			catch (\Exception $e)
			{
				\spectrum\tests\Test::$temp["exception"] = $e;
			}
		');
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\core\plugins\Exception', \spectrum\tests\Test::$temp["exception"]);
		$this->assertSame('Context data is not initialized (call this method on spec run)', \spectrum\tests\Test::$temp["exception"]->getMessage());
		$this->assertSame(0, \spectrum\tests\Test::$temp["callCount"]);
	}
	
/**/
	
	public function testGetContextData_ReturnsNullByDefault()
	{
		$spec = new Spec();
		$this->assertSame(null, $spec->contexts->getContextData());
	}
	
	public function testGetContextData_ContextDataIsInitialized_ReturnsInstanceOfContextData()
	{
		\spectrum\tests\Test::$temp["contextData"] = null;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["contextData"] = $this->getOwnerSpec()->contexts->getContextData();
		', 'onEndingSpecExecute');
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\core\plugins\basePlugins\contexts\Data', \spectrum\tests\Test::$temp["contextData"]);
	}
	
	public function testGetContextData_ContextDataIsInitialized_ReturnsNewInstanceOnEveryRun()
	{
		\spectrum\tests\Test::$temp["contextData"] = array();
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["contextData"][] = $this->getOwnerSpec()->contexts->getContextData();
		', 'onEndingSpecExecute');
		
		$spec = new Spec();
		$spec->run();
		$spec->run();
		$spec->run();
		
		$this->assertSame(3, count(\spectrum\tests\Test::$temp["contextData"]));
		$this->assertInstanceOf('\spectrum\core\plugins\basePlugins\contexts\Data', \spectrum\tests\Test::$temp["contextData"][0]);
		$this->assertInstanceOf('\spectrum\core\plugins\basePlugins\contexts\Data', \spectrum\tests\Test::$temp["contextData"][1]);
		$this->assertInstanceOf('\spectrum\core\plugins\basePlugins\contexts\Data', \spectrum\tests\Test::$temp["contextData"][2]);
		
		$this->assertNotSame(\spectrum\tests\Test::$temp["contextData"][0], \spectrum\tests\Test::$temp["contextData"][1]);
		$this->assertNotSame(\spectrum\tests\Test::$temp["contextData"][1], \spectrum\tests\Test::$temp["contextData"][2]);
		$this->assertNotSame(\spectrum\tests\Test::$temp["contextData"][2], \spectrum\tests\Test::$temp["contextData"][0]);
	}
	
/**/
	
	public function testContextDataInitialization_CreatesContextDataBeforeEndingSpecExecute()
	{
		\spectrum\tests\Test::$temp["contextData"] = null;
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["contextData"] = $this->getOwnerSpec()->contexts->getContextData();
		', 'onEndingSpecExecute');
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\core\plugins\basePlugins\contexts\Data', \spectrum\tests\Test::$temp["contextData"]);
	}
	
	public function testContextDataInitialization_UsesConfigForContextDataClassGetting()
	{
		$contextDataClassName = $this->createClass('class ... extends \spectrum\core\plugins\basePlugins\contexts\Data {}');
		config::setContextDataClass($contextDataClassName);

		\spectrum\tests\Test::$temp["contextData"] = null;
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["contextData"] = $this->getOwnerSpec()->contexts->getContextData();
		', 'onEndingSpecExecute');
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertInstanceOf($contextDataClassName, \spectrum\tests\Test::$temp["contextData"]);
	}
	
	public function testContextDataInitialization_ApplyBeforeFunctionsToContextDataBeforeEndingSpecExecuteInDirectOrder()
	{
		\spectrum\tests\Test::$temp["properties"] = array();
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["properties"][] = get_object_vars($this->getOwnerSpec()->contexts->getContextData());
		', 'onEndingSpecExecute');
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		$specs[0]->contexts->add(function() use($specs){ $specs[1]->contexts->getContextData()->aaa .= '1'; }, 'before');
		$specs[0]->contexts->add(function() use($specs){ $specs[1]->contexts->getContextData()->aaa .= '2'; }, 'before');
		$specs[0]->contexts->add(function() use($specs){ $specs[1]->contexts->getContextData()->aaa .= '3'; }, 'after');
		$specs[0]->contexts->add(function() use($specs){ $specs[1]->contexts->getContextData()->aaa .= '4'; }, 'before');
		
		$specs[1]->contexts->add(function() use($specs){ $specs[1]->contexts->getContextData()->aaa .= '5'; }, 'before');
		$specs[1]->contexts->add(function() use($specs){ $specs[1]->contexts->getContextData()->aaa .= '6'; }, 'before');
		$specs[1]->contexts->add(function() use($specs){ $specs[1]->contexts->getContextData()->aaa .= '7'; }, 'after');
		$specs[1]->contexts->add(function() use($specs){ $specs[1]->contexts->getContextData()->aaa .= '8'; }, 'before');
		
		$specs[0]->run();
		
		$this->assertSame(array(array('aaa' => '124568')), \spectrum\tests\Test::$temp["properties"]);
	}
	
	public function testContextDataInitialization_ApplyAfterFunctionsToContextDataAfterEndingSpecExecuteInBackwardOrder()
	{
		\spectrum\tests\Test::$temp["properties"] = array();
		\spectrum\tests\Test::$temp["contextData"] = array();
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["properties"][] = get_object_vars($this->getOwnerSpec()->contexts->getContextData());
			\spectrum\tests\Test::$temp["contextData"][] = $this->getOwnerSpec()->contexts->getContextData();
		', 'onEndingSpecExecute');
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		$specs[0]->contexts->add(function() use($specs){ $specs[1]->contexts->getContextData()->aaa .= '1'; }, 'after');
		$specs[0]->contexts->add(function() use($specs){ $specs[1]->contexts->getContextData()->aaa .= '2'; }, 'after');
		$specs[0]->contexts->add(function() use($specs){ $specs[1]->contexts->getContextData()->aaa .= '3'; }, 'before');
		$specs[0]->contexts->add(function() use($specs){ $specs[1]->contexts->getContextData()->aaa .= '4'; }, 'after');
		
		$specs[1]->contexts->add(function() use($specs){ $specs[1]->contexts->getContextData()->aaa .= '5'; }, 'after');
		$specs[1]->contexts->add(function() use($specs){ $specs[1]->contexts->getContextData()->aaa .= '6'; }, 'after');
		$specs[1]->contexts->add(function() use($specs){ $specs[1]->contexts->getContextData()->aaa .= '7'; }, 'before');
		$specs[1]->contexts->add(function() use($specs){ $specs[1]->contexts->getContextData()->aaa .= '8'; }, 'after');
		
		$specs[0]->run();
		
		$this->assertSame(array(array('aaa' => '37')), \spectrum\tests\Test::$temp["properties"]);
		$this->assertSame(array('aaa' => '37865421'), get_object_vars(\spectrum\tests\Test::$temp["contextData"][0]));
	}
	
	public function testContextDataInitialization_UnsetContextDataLinkAfterEndingSpecExecute()
	{
		\spectrum\tests\Test::$temp["contextData"] = false;
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["contextData"] = $this->getOwnerSpec()->contexts->getContextData();
		', 'onSpecRunFinish');
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame(null, \spectrum\tests\Test::$temp["contextData"]);
	}
}