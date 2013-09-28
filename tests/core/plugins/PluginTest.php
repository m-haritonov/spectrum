<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core\plugins;

use spectrum\config;
use spectrum\core\Spec;

require_once __DIR__ . '/../../init.php';

class PluginTest extends \spectrum\tests\Test
{
	public function testGetAccessName_ReturnsNullByDefault()
	{
		$pluginClassName = $this->createClass('class ... extends \spectrum\core\plugins\Plugin {}');
		$this->assertSame(null, $pluginClassName::getAccessName());
	}
	
/**/
	
	public function testGetActivateMoment_ReturnsFirstAccessStringByDefault()
	{
		$pluginClassName = $this->createClass('class ... extends \spectrum\core\plugins\Plugin {}');
		$this->assertSame('firstAccess', $pluginClassName::getActivateMoment());
	}
	
/**/
	
	public function testGetEventListeners_ReturnsEmptyArrayByDefault()
	{
		$pluginClassName = $this->createClass('class ... extends \spectrum\core\plugins\Plugin {}');
		$this->assertSame(array(), $pluginClassName::getEventListeners());
	}
	
/**/
	
	public function testGetOwnerSpec_ReturnsPassedToConstructorSpecInstance()
	{
		$pluginClassName = $this->createClass('class ... extends \spectrum\core\plugins\Plugin {}');
		$spec = new Spec();
		$plugin = new $pluginClassName($spec);
		$this->assertSame($spec, $plugin->getOwnerSpec());
	}
	
/**/
	
	public function providerCallMethodThroughRunningAncestorSpecs()
	{
		return array(
			array(
				'
				Spec
				->Spec
				->Spec
				->->Spec(checkpoint)
				',
				array(1 => 'checkpoint'),
				array(
					0 => 'aaa',
					1 => 'bbb',
					2 => 'ccc',
					'checkpoint' => 'ddd',
				),
				array('ddd', 'ddd'),
			),
			
			array(
				'
				Spec
				->Spec
				->Spec
				->->Spec(checkpoint)
				',
				array(1 => 'checkpoint'),
				array(
					0 => 'aaa',
					1 => 'bbb',
					2 => 'ccc',
				),
				array('bbb', 'ccc'),
			),
			
			array(
				'
				Spec
				->Spec
				->Spec
				->->Spec(checkpoint)
				',
				array(1 => 'checkpoint'),
				array(0 => 'aaa'),
				array('aaa', 'aaa'),
			),
		);
	}
	
	/**
	 * @dataProvider providerCallMethodThroughRunningAncestorSpecs
	 */
	public function testCallMethodThroughRunningAncestorSpecs_ReturnsValueFromFirstRunningSpecFromSelfToUp($specTreePattern, $specBindings, $pluginValues, $returnValues)
	{
		\spectrum\tests\Test::$temp["returnValues"] = array();
		
		config::registerSpecPlugin($this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				public $value = null;
				
				static public function getAccessName()
				{
					return "testPlugin";
				}
				
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunStart", "method" => "onSpecRunStart", "order" => 100),
					);
				}
				
				public function getValue()
				{
					return $this->value;
				}
				
				protected function onSpecRunStart()
				{
					if ($this->getOwnerSpec() === \spectrum\tests\Test::$temp["specs"]["checkpoint"])
						\spectrum\tests\Test::$temp["returnValues"][] = $this->callMethodThroughRunningAncestorSpecs("getValue");
				}
			}
		'));
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsTree($specTreePattern, $specBindings);
		
		foreach ($pluginValues as $specKey => $pluginValue)
			\spectrum\tests\Test::$temp["specs"][$specKey]->testPlugin->value = $pluginValue;
		
		\spectrum\tests\Test::$temp["specs"][0]->run();
		
		$this->assertSame($returnValues, \spectrum\tests\Test::$temp["returnValues"]);
	}
	
	public function testCallMethodThroughRunningAncestorSpecs_PassesArgumentsToCalleeMethod()
	{
		\spectrum\tests\Test::$temp["passedArguments"] = array();
		
		config::registerSpecPlugin($this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				public $value = null;
				
				static public function getAccessName()
				{
					return "testPlugin";
				}
				
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunStart", "method" => "onSpecRunStart", "order" => 100),
					);
				}
				
				public function getValue()
				{
					\spectrum\tests\Test::$temp["passedArguments"][] = func_get_args();
					return $this->value;
				}
				
				protected function onSpecRunStart()
				{
					$this->callMethodThroughRunningAncestorSpecs("getValue", array("aaa", "bbb", "ccc"));
				}
			}
		'));
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame(array(array("aaa", "bbb", "ccc")), \spectrum\tests\Test::$temp["passedArguments"]);
	}
	
	public function providerCallMethodThroughRunningAncestorSpecs2()
	{
		return array(
			array(
				'
				Spec
				->Spec
				->Spec
				->->Spec(checkpoint)
				',
				array(1 => 'checkpoint'),
				array(
					0 => 222,
					1 => 333,
					2 => 111,
					'checkpoint' => 111,
				),
				111,
				true,
				array(333, 222),
			),
			
			array(
				'
				Spec
				->Spec
				->Spec
				->->Spec(checkpoint)
				',
				array(1 => 'checkpoint'),
				array(
					0 => 222,
					1 => '111',
					2 => 111,
					'checkpoint' => 111,
				),
				111,
				true,
				array('111', 222),
			),
			
			array(
				'
				Spec
				->Spec
				->Spec
				->->Spec(checkpoint)
				',
				array(1 => 'checkpoint'),
				array(
					0 => 222,
					1 => '111',
					2 => 111,
					'checkpoint' => 111,
				),
				111,
				false,
				array(222, 222),
			),
		);
	}
	
	/**
	 * @dataProvider providerCallMethodThroughRunningAncestorSpecs2
	 */
	public function testCallMethodThroughRunningAncestorSpecs_DiscardsIgnoredReturnValues($specTreePattern, $specBindings, $pluginValues, $ignoredReturnValue, $useStrictComparison, $returnValues)
	{
		\spectrum\tests\Test::$temp["returnValues"] = array();
		\spectrum\tests\Test::$temp["ignoredReturnValue"] = $ignoredReturnValue;
		\spectrum\tests\Test::$temp["useStrictComparison"] = $useStrictComparison;
		
		config::registerSpecPlugin($this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				public $value;
				
				static public function getAccessName()
				{
					return "testPlugin";
				}
				
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunStart", "method" => "onSpecRunStart", "order" => 100),
					);
				}
				
				public function getValue()
				{
					return $this->value;
				}
				
				protected function onSpecRunStart()
				{
					if ($this->getOwnerSpec() === \spectrum\tests\Test::$temp["specs"]["checkpoint"])
						\spectrum\tests\Test::$temp["returnValues"][] = $this->callMethodThroughRunningAncestorSpecs("getValue", array(), null, \spectrum\tests\Test::$temp["ignoredReturnValue"], \spectrum\tests\Test::$temp["useStrictComparison"]);
				}
			}
		'));
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsTree($specTreePattern, $specBindings);
		
		foreach ($pluginValues as $specKey => $pluginValue)
			\spectrum\tests\Test::$temp["specs"][$specKey]->testPlugin->value = $pluginValue;
		
		\spectrum\tests\Test::$temp["specs"][0]->run();
		
		$this->assertSame($returnValues, \spectrum\tests\Test::$temp["returnValues"]);
	}
	
	public function testCallMethodThroughRunningAncestorSpecs_ProperReturnValueIsNotFound_ReturnsDefaultReturnValue()
	{
		\spectrum\tests\Test::$temp["returnValues"] = array();
		
		config::registerSpecPlugin($this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				public $value = null;
				
				static public function getAccessName()
				{
					return "testPlugin";
				}
				
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunStart", "method" => "onSpecRunStart", "order" => 100),
					);
				}
				
				public function getValue()
				{
					return $this->value;
				}
				
				protected function onSpecRunStart()
				{
					if ($this->getOwnerSpec() === \spectrum\tests\Test::$temp["specs"]["checkpoint"])
						\spectrum\tests\Test::$temp["returnValues"][] = $this->callMethodThroughRunningAncestorSpecs("getValue", array(), "some text", null);
				}
			}
		'));
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsTree('
			Spec
			->Spec
			->Spec
			->->Spec(checkpoint)
		', array(1 => 'checkpoint'));
		
		\spectrum\tests\Test::$temp["specs"][0]->run();
		
		$this->assertSame(array('some text', 'some text'), \spectrum\tests\Test::$temp["returnValues"]);
	}
	
/**/
	
	public function testDispatchPluginEvent_DispatchesCustomEventWithArguments()
	{
		\spectrum\tests\Test::$temp["dispatchedEvents"] = array();
		
		config::registerSpecPlugin($this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getAccessName()
				{
					return "testPlugin";
				}
				
				public function dispatchMyTestEvent()
				{
					$this->dispatchPluginEvent("onMyTestEvent", array("aaa", "bbb", "ccc"));
				}
			}
		'));

		config::registerSpecPlugin($this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onMyTestEvent", "method" => "onMyTestEvent", "order" => 100),
					);
				}
				
				protected function onMyTestEvent()
				{
					\spectrum\tests\Test::$temp["dispatchedEvents"][] = array(__FUNCTION__, func_get_args());
				}
			}
		'));
		
		$spec = new Spec();
		$spec->testPlugin->dispatchMyTestEvent();
		
		$this->assertSame(array(
			array("onMyTestEvent", array("aaa", "bbb", "ccc")),
		), \spectrum\tests\Test::$temp["dispatchedEvents"]);
	}
	
/**/
	
	public function testHandleModifyDeny_SpecWithoutParentsIsRunning_ThrowsException()
	{
		\spectrum\tests\Test::$temp["caughtException"] = null;

		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getAccessName()
				{
					return "testPlugin";
				}
				
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunStart", "method" => "onSpecRunStart", "order" => 100),
					);
				}
				
				protected function onSpecRunStart()
				{
					try
					{
						$this->getOwnerSpec()->testPlugin->handleModifyDeny("aaa");
					}
					catch(\Exception $e)
					{
						\spectrum\tests\Test::$temp["caughtException"] = $e;
					}
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\core\plugins\Exception', \spectrum\tests\Test::$temp["caughtException"]);
		$this->assertSame('Call of "' . $pluginClassName . '::aaa" method is forbidden on run', \spectrum\tests\Test::$temp["caughtException"]->getMessage());
	}
	
	public function testHandleModifyDeny_RootSpecIsRunning_ThrowsException()
	{
		\spectrum\tests\Test::$temp["caughtException"] = null;

		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getAccessName()
				{
					return "testPlugin";
				}
				
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunStart", "method" => "onSpecRunStart", "order" => 100),
					);
				}
				
				protected function onSpecRunStart()
				{
					if ($this->getOwnerSpec() === \spectrum\tests\Test::$temp["specs"]["checkpoint"])
					{
						try
						{
							\spectrum\tests\Test::$temp["specs"]["tested"]->testPlugin->handleModifyDeny("aaa");
						}
						catch(\Exception $e)
						{
							\spectrum\tests\Test::$temp["caughtException"] = $e;
						}
					}
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsTree('
			Spec
			->Spec(checkpoint)
			->->Spec(tested)
		');
		
		\spectrum\tests\Test::$temp["specs"][0]->run();
		
		$this->assertInstanceOf('\spectrum\core\plugins\Exception', \spectrum\tests\Test::$temp["caughtException"]);
		$this->assertSame('Call of "' . $pluginClassName . '::aaa" method is forbidden on run', \spectrum\tests\Test::$temp["caughtException"]->getMessage());
	}
}