<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\core\plugins;

use spectrum\config;
use spectrum\core\Spec;

require_once __DIR__ . '/../../init.php';

class TestTest extends \spectrum\tests\Test
{
	public function testSetFunction_SetsNewFunction()
	{
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->test->setFunction($function1);
		$this->assertSame($function1, $spec->test->getFunction());
		
		$spec->test->setFunction($function2);
		$this->assertSame($function2, $spec->test->getFunction());
	}
	
	public function testSetFunction_CallOnRun_ThrowsExceptionAndDoesNotChangeFunction()
	{
		\spectrum\tests\Test::$temp["exception"] = null;
		
		$this->registerPluginWithCodeInEvent('
			try
			{
				$this->getOwnerSpec()->test->setFunction(function(){});
			}
			catch (\Exception $e)
			{
				\spectrum\tests\Test::$temp["exception"] = $e;
			}
		');
		
		$function = function(){};

		$spec = new Spec();
		$spec->test->setFunction($function);
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\core\plugins\Exception', \spectrum\tests\Test::$temp["exception"]);
		$this->assertSame('Call of "\spectrum\core\plugins\Test::setFunction" method is forbidden on run', \spectrum\tests\Test::$temp["exception"]->getMessage());
		$this->assertSame($function, $spec->test->getFunction());
	}
	
/**/
	
	public function testGetFunction_ReturnsSetFunction()
	{
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->test->setFunction($function1);
		$this->assertSame($function1, $spec->test->getFunction());
		
		$spec->test->setFunction($function2);
		$this->assertSame($function2, $spec->test->getFunction());
	}
	
	public function testGetFunction_ReturnsNullByDefault()
	{
		$spec = new Spec();
		$this->assertSame(null, $spec->test->getFunction());
	}

/**/
	
	public function testGetFunctionThroughRunningAncestors_ReturnsFunctionFromRunningAncestorOrFromSelf()
	{
		\spectrum\tests\Test::$temp["returnValues"] = array();
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["returnValues"][] = $this->getOwnerSpec()->test->getFunctionThroughRunningAncestors();
		', 'onEndingSpecExecute');
		
		$specs = $this->createSpecsByListPattern('
			Spec
			->Spec(endingSpec1)
			->Spec(parent1)
			->Spec(parent2)
			->Spec(parent3)
			->->Spec(endingSpec2)
		', array('parent1' => 'endingSpec2', 'parent2' => 'endingSpec2'));
		
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		$function4 = function(){};
		
		$specs[0]->test->setFunction($function1);
		$specs['endingSpec1']->test->setFunction($function2);
		$specs['parent1']->test->setFunction($function3);
		$specs['parent2']->test->setFunction($function4);
		
		$specs[0]->run();
		
		$this->assertSame(array($function2, $function3, $function4, $function1), \spectrum\tests\Test::$temp["returnValues"]);
	}
	
	public function testGetFunctionThroughRunningAncestors_ReturnsNullByDefault()
	{
		$spec = new Spec();
		$this->assertSame(null, $spec->test->getFunctionThroughRunningAncestors());
	}
	
/**/
	
	public function testGetContextData_ReturnsNullByDefault()
	{
		$spec = new Spec();
		$this->assertSame(null, $spec->test->getContextData());
	}
	
/**/
	
	public function testFunctionCall_CallsFunctionOnEndingSpec()
	{
		$specs = $this->createSpecsByListPattern('
			Spec
			->Spec
		');

		$callCount = 0;
		$specs[1]->test->setFunction(function() use(&$callCount){ $callCount++; });
		$specs[0]->run();
		
		$this->assertSame(1, $callCount);
	}
	
	public function testFunctionCall_DoesNotCallsFunctionOnNotEndingSpecs()
	{
		$specs = $this->createSpecsByListPattern('
			Spec
			->Spec
		');

		$callCount = array('notEndingSpec' => 0, 'endingSpec' => 0);
		$specs[0]->test->setFunction(function() use(&$callCount){ $callCount['notEndingSpec']++; });
		$specs[1]->test->setFunction(function() use(&$callCount){ $callCount['endingSpec']++; });
		$specs[0]->run();
		
		$this->assertSame(array('notEndingSpec' => 0, 'endingSpec' => 1), $callCount);
	}
	
	public function testFunctionCall_DoesNotPassArgumentsToFunction()
	{
		$spec = new Spec();
		$passedArguments = array();
		$spec->test->setFunction(function() use(&$passedArguments){
			$passedArguments[] = func_get_args();
		});
		
		$spec->run();
		$this->assertSame(array(array()), $passedArguments);
	}
	
	public function testFunctionCall_GetsFunctionFromAncestorsOrSelf()
	{
		$specs = $this->createSpecsByListPattern('
			Spec
			->Spec
			->Spec
			->Spec
			->->Spec
		', array(2 => 4));

		$calls = array();
		$specs[0]->test->setFunction(function() use(&$calls){ $calls[] = 0; });
		$specs[1]->test->setFunction(function() use(&$calls){ $calls[] = 1; });
		$specs[2]->test->setFunction(function() use(&$calls){ $calls[] = 2; });
		$specs[0]->run();
		
		$this->assertSame(array(1, 2, 0), $calls);
	}

	
	public function testFunctionCall_InitializesContextDataBeforeFunctionCall()
	{
		$spec = new Spec();
		$contextData = array();
		$spec->test->setFunction(function() use(&$contextData, $spec){
			$contextData = $spec->test->getContextData();
		});
		
		$spec->run();
		$this->assertInstanceOf('\spectrum\core\ContextData', $contextData);
	}
	
	public function testFunctionCall_SetsContextDataToNullAfterFunctionCall()
	{
		$spec = new Spec();
		$spec->test->setFunction(function(){});
		$spec->run();
		
		$this->assertNull($spec->test->getContextData());
	}
	
	public function testFunctionCall_CreatesNewInstanceOfContextDataOnEveryRun()
	{
		$spec = new Spec();
		$contextDataItems = array();
		$spec->test->setFunction(function() use(&$contextDataItems, $spec){
			$contextDataItems[] = $spec->test->getContextData();
		});
		
		$spec->run();
		$spec->run();
		$spec->run();
		
		$this->assertSame(3, count($contextDataItems));
		
		$this->assertInstanceOf('\spectrum\core\ContextData', $contextDataItems[0]);
		$this->assertInstanceOf('\spectrum\core\ContextData', $contextDataItems[1]);
		$this->assertInstanceOf('\spectrum\core\ContextData', $contextDataItems[2]);
		
		$this->assertNotSame($contextDataItems[0], $contextDataItems[1]);
		$this->assertNotSame($contextDataItems[1], $contextDataItems[2]);
		$this->assertNotSame($contextDataItems[2], $contextDataItems[0]);
	}
	
	public function testFunctionCall_ApplyBeforeFunctionsToContextDataBeforeFunctionCallAndInDirectOrder()
	{
		$specs = $this->createSpecsByVisualPattern('
			0
			|
			1
		');
		
		$specs[0]->contextModifiers->add(function() use($specs){ $specs[1]->test->getContextData()->aaa .= '1'; }, 'before');
		$specs[0]->contextModifiers->add(function() use($specs){ $specs[1]->test->getContextData()->aaa .= '2'; }, 'before');
		$specs[0]->contextModifiers->add(function() use($specs){ $specs[1]->test->getContextData()->aaa .= '3'; }, 'after');
		$specs[0]->contextModifiers->add(function() use($specs){ $specs[1]->test->getContextData()->aaa .= '4'; }, 'before');
		
		$specs[1]->contextModifiers->add(function() use($specs){ $specs[1]->test->getContextData()->aaa .= '5'; }, 'before');
		$specs[1]->contextModifiers->add(function() use($specs){ $specs[1]->test->getContextData()->aaa .= '6'; }, 'before');
		$specs[1]->contextModifiers->add(function() use($specs){ $specs[1]->test->getContextData()->aaa .= '7'; }, 'after');
		$specs[1]->contextModifiers->add(function() use($specs){ $specs[1]->test->getContextData()->aaa .= '8'; }, 'before');
		
		$properties = array();
		$specs[1]->test->setFunction(function() use(&$properties, $specs){
			$properties[] = get_object_vars($specs[1]->test->getContextData());
		});
		
		$specs[0]->run();
		
		$this->assertSame(array(array('aaa' => '124568')), $properties);
	}
	
	public function testFunctionCall_ApplyAfterFunctionsToContextDataAfterFunctionCallAndInBackwardOrder()
	{
		$specs = $this->createSpecsByVisualPattern('
			0
			|
			1
		');
		
		$specs[0]->contextModifiers->add(function() use($specs){ $specs[1]->test->getContextData()->aaa .= '1'; }, 'after');
		$specs[0]->contextModifiers->add(function() use($specs){ $specs[1]->test->getContextData()->aaa .= '2'; }, 'after');
		$specs[0]->contextModifiers->add(function() use($specs){ $specs[1]->test->getContextData()->aaa .= '3'; }, 'before');
		$specs[0]->contextModifiers->add(function() use($specs){ $specs[1]->test->getContextData()->aaa .= '4'; }, 'after');
		
		$specs[1]->contextModifiers->add(function() use($specs){ $specs[1]->test->getContextData()->aaa .= '5'; }, 'after');
		$specs[1]->contextModifiers->add(function() use($specs){ $specs[1]->test->getContextData()->aaa .= '6'; }, 'after');
		$specs[1]->contextModifiers->add(function() use($specs){ $specs[1]->test->getContextData()->aaa .= '7'; }, 'before');
		$specs[1]->contextModifiers->add(function() use($specs){ $specs[1]->test->getContextData()->aaa .= '8'; }, 'after');
		
		$properties = array();
		$contextDataItems = array();
		$specs[1]->test->setFunction(function() use(&$properties, &$contextDataItems, $specs){
			$properties[] = get_object_vars($specs[1]->test->getContextData());
			$contextDataItems[] = $specs[1]->test->getContextData();
		});
		
		$specs[0]->run();
		
		$this->assertSame(array(array('aaa' => '37')), $properties);
		$this->assertSame(array('aaa' => '37865421'), get_object_vars($contextDataItems[0]));
	}
	
	public function testFunctionCall_UsesConfigForContextDataClassGetting()
	{
		$contextDataClassName = $this->createClass('class ... extends \spectrum\core\ContextData {}');
		config::setClassReplacement('\spectrum\core\ContextData', $contextDataClassName);

		$spec = new Spec();
		$contextData = array();
		$spec->test->setFunction(function() use(&$contextData, $spec){
			$contextData = $spec->test->getContextData();
		});
		
		$spec->run();
		
		$this->assertInstanceOf($contextDataClassName, $contextData);
	}
	
	public function testFunctionCall_FunctionNotSet_DoesNotTryToCallFunction()
	{
		$spec = new Spec();
		$spec->test->setFunction(null);
		$spec->run();
	}
	
	public function testFunctionCall_FunctionNotSet_DoesNotInitializeContextData()
	{
		\spectrum\tests\Test::$temp["contextDataInitializeCount"] = 0;
		config::setClassReplacement('\spectrum\core\ContextData', $this->createClass('
			class ... extends \spectrum\core\ContextData
			{
				public function __construct()
				{
					\spectrum\tests\Test::$temp["contextDataInitializeCount"]++;
				}
			}
		'));
		
		$spec = new Spec();
		$spec->run();
		$this->assertSame(0, \spectrum\tests\Test::$temp["contextDataInitializeCount"]);
	}
}