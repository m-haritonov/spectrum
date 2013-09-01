<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core\spec;
use spectrum\config;
use spectrum\core\Spec;

require_once __DIR__ . '/../../init.php';

class RunTest extends \spectrum\tests\Test
{
	public function setUp()
	{
		parent::setUp();
		config::unregisterSpecPlugins();
	}
	
	public function providerSpecsWithMoreThanOneRootAncestors()
	{
		return array(
			array('
				->Spec
				->Spec
				Spec(spec)
			'),
			array('
				->->Spec
				->->Spec
				->Spec
				Spec(spec)
			'),
			array('
				->->Spec
				->Spec
				->Spec
				Spec(spec)
			'),
		);
	}

	/**
	 * @dataProvider providerSpecsWithMoreThanOneRootAncestors
	 */
	public function testSpecHasMoreThanOneRootAncestors_ThrowsException($specTreePattern)
	{
		$specs = $this->createSpecsTree($specTreePattern);
		$specs['spec']->setName('aaa');
		
		$this->assertThrowsException('\spectrum\core\Exception', 'Spec "aaa" has more than one root ancestors, but for run needs only one general root', function() use($specs){
			$specs['spec']->run();
		});
	}
	
	public function testSpecHasMoreThanOneRootAncestors_StopsRunByExceptionThrowing()
	{
		\spectrum\tests\Test::$temp["calledSpecs"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
					);
				}
				
				public function onSpecRunInit()
				{
					\spectrum\tests\Test::$temp["calledSpecs"][] = $this->getOwnerSpec();
					
					if (\spectrum\tests\Test::$temp["specs"]["caller"] === $this->getOwnerSpec())
						\spectrum\tests\Test::$temp["specs"]["callee"]->run();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsTree('
			->->Spec(caller)
			->Spec
			->Spec
			Spec(callee)
			->Spec
		');

		\spectrum\tests\Test::$temp["specs"]["callee"]->setName('aaa');

		$this->assertThrowsException('\spectrum\core\Exception', 'Spec "aaa" has more than one root ancestors, but for run needs only one general root', function(){
			\spectrum\tests\Test::$temp["specs"]["caller"]->run();
		});
		
		$this->assertSame(array(
			\spectrum\tests\Test::$temp["specs"]["caller"],
		), \spectrum\tests\Test::$temp["calledSpecs"]);
	}
	
/**/
	
	public function testSpecIsAlreadyRunning_ThrowsException()
	{
		\spectrum\tests\Test::$temp["exception"] = null;
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
					);
				}
				
				public function onSpecRunInit()
				{
					try
					{
						$this->getOwnerSpec()->run();
					}
					catch (\Exception $e)
					{
						\spectrum\tests\Test::$temp["exception"] = $e;
					}
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$spec = new Spec();
		$spec->setName('aaa');
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\core\Exception', \spectrum\tests\Test::$temp["exception"]);
		$this->assertSame('Spec "aaa" is already running', \spectrum\tests\Test::$temp["exception"]->getMessage());
	}
	
	public function testSpecIsAlreadyRunning_StopsRunByExceptionThrowing()
	{
		\spectrum\tests\Test::$temp["calledSpecs"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
					);
				}
				
				public function onSpecRunInit()
				{
					\spectrum\tests\Test::$temp["calledSpecs"][] = $this->getOwnerSpec();
					
					if (\spectrum\tests\Test::$temp["specs"]["spec"] === $this->getOwnerSpec())
						\spectrum\tests\Test::$temp["specs"]["spec"]->run();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsTree('
			Spec
			->Spec(spec)
			->->Spec
		');
		
		\spectrum\tests\Test::$temp["specs"]["spec"]->setName('aaa');

		$this->assertThrowsException('\spectrum\core\Exception', 'Spec "aaa" is already running', function(){
			\spectrum\tests\Test::$temp["specs"][0]->run();
		});
		
		$this->assertSame(array(
			\spectrum\tests\Test::$temp["specs"][0],
			\spectrum\tests\Test::$temp["specs"]["spec"],
		), \spectrum\tests\Test::$temp["calledSpecs"]);
	}
	
/**/
	
	public function testSpecHasAlreadyRunningSibling_ThrowsException()
	{
		\spectrum\tests\Test::$temp["exception"] = null;
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
					);
				}
				
				public function onSpecRunInit()
				{
					if (\spectrum\tests\Test::$temp["specs"]["caller"] === $this->getOwnerSpec())
					{
						try
						{
							\spectrum\tests\Test::$temp["specs"]["callee"]->run();
						}
						catch (\Exception $e)
						{
							\spectrum\tests\Test::$temp["exception"] = $e;
						}
					}
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsTree('
			Spec
			->Spec(caller)
			->Spec(callee)
		');
		
		\spectrum\tests\Test::$temp["specs"]['callee']->setName('aaa');
		\spectrum\tests\Test::$temp["specs"][0]->run();
		
		$this->assertInstanceOf('\spectrum\core\Exception', \spectrum\tests\Test::$temp["exception"]);
		$this->assertSame('Sibling spec of spec "aaa" is already running', \spectrum\tests\Test::$temp["exception"]->getMessage());
	}
	
	public function testSpecHasAlreadyRunningSibling_StopsRunByExceptionThrowing()
	{
		\spectrum\tests\Test::$temp["calledSpecs"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
					);
				}
				
				public function onSpecRunInit()
				{
					\spectrum\tests\Test::$temp["calledSpecs"][] = $this->getOwnerSpec();
					
					if (\spectrum\tests\Test::$temp["specs"]["caller"] === $this->getOwnerSpec())
						\spectrum\tests\Test::$temp["specs"]["callee"]->run();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsTree('
			Spec
			->Spec(caller)
			->->Spec
			->Spec(callee)
		');
		
		\spectrum\tests\Test::$temp["specs"]["callee"]->setName('aaa');

		$this->assertThrowsException('\spectrum\core\Exception', 'Sibling spec of spec "aaa" is already running', function(){
			\spectrum\tests\Test::$temp["specs"][0]->run();
		});
		
		$this->assertSame(array(
			\spectrum\tests\Test::$temp["specs"][0],
			\spectrum\tests\Test::$temp["specs"]["caller"],
		), \spectrum\tests\Test::$temp["calledSpecs"]);
	}
	
/**/
	
	public function providerChildSpecRunWithoutRunningParent()
	{
		return array(
			
			/* Disable siblings of self */
			
			array(
				'
				Spec(checkpoint)
				->Spec
				->Spec(callee)
				',
				array(true, false, true),
				array('checkpoint', 'callee'),
			),
			
			array(
				'
				Spec(checkpoint)
				->Spec(callee)
				->Spec
				',
				array(true, true, false),
				array('checkpoint', 'callee'),
			),
			
			array(
				'
				Spec(checkpoint)
				->Spec
				->Spec(callee)
				->Spec
				',
				array(true, false, true, false),
				array('checkpoint', 'callee'),
			),
			
			/* Disable siblings of parent */
			
			array(
				'
				Spec(checkpoint)
				->Spec
				->Spec
				->->Spec(callee)
				',
				array(true, false, true, true),
				array('checkpoint', 2, 'callee'),
			),
			
			array(
				'
				Spec(checkpoint)
				->Spec
				->->Spec(callee)
				->Spec
				',
				array(true, true, true, false),
				array('checkpoint', 1, 'callee'),
			),
			
			array(
				'
				Spec(checkpoint)
				->Spec
				->Spec
				->->Spec(callee)
				->Spec
				',
				array(true, false, true, true, false),
				array('checkpoint', 2, 'callee'),
			),
			
			/* Disable siblings of ancestor */
			
			array(
				'
				Spec(checkpoint)
				->Spec
				->Spec
				->->Spec
				->->->Spec(callee)
				',
				array(true, false, true, true, true),
				array('checkpoint', 2, 3, 'callee'),
			),
			
			array(
				'
				Spec(checkpoint)
				->Spec
				->->Spec
				->->->Spec(callee)
				->Spec
				',
				array(true, true, true, true, false),
				array('checkpoint', 1, 2, 'callee'),
			),
			
			array(
				'
				Spec(checkpoint)
				->Spec
				->Spec
				->->Spec
				->->->Spec(callee)
				->Spec
				',
				array(true, false, true, true, true, false),
				array('checkpoint', 2, 3, 'callee'),
			),
			
			/* Disable any quantity of spec */
			
			array(
				'
				Spec(checkpoint)
				->Spec
				->Spec
				->Spec
				->Spec
				->->Spec
				->->Spec
				->->Spec
				->->Spec
				->->->Spec(callee)
				->->Spec
				->->Spec
				->->Spec
				->Spec
				->Spec
				->Spec
				',
				array(true, false, false, false, true, false, false, false, true, true, false, false, false, false, false, false),
				array('checkpoint', 4, 8, 'callee'),
			),
			
			/* Does not disable self and children of self */
			
			array(
				'
				Spec(checkpoint)
				->Spec
				->Spec(callee)
				->->Spec
				->->Spec
				->->->Spec
				',
				array(true, false, true, true, true, true),
				array('checkpoint', 'callee', 3, 4, 5),
			),
			
			array(
				'
				Spec(checkpoint)
				->Spec
				->Spec
				->->Spec(callee)
				->->->Spec
				->->->Spec
				->->->->Spec
				',
				array(true, false, true, true, true, true, true),
				array('checkpoint', 2, 'callee', 4, 5, 6),
			),
			
			/* Does not disable ancestors of self */
			
			array(
				'
				Spec(checkpoint)
				->Spec
				->->Spec
				->->->Spec(callee)
				->->Spec
				',
				array(true, true, true, true, false),
				array('checkpoint', 1, 2, 'callee'),
			),
						
			/* Does not disable children of disabled specs if they are not siblings */
			
			array(
				'
				Spec(checkpoint)
				->Spec
				->->Spec
				->Spec(callee)
				',
				array(true, false, true, true),
				array('checkpoint', 'callee'),
			),
			
			array(
				'
				Spec(checkpoint)
				->Spec
				->->Spec
				->Spec
				->->Spec(callee)
				',
				array(true, false, true, true, true),
				array('checkpoint', 3, 'callee'),
			),
			
			/* Does not disable multiple parents */
			
			array(
				'
				Spec(checkpoint)
				->Spec(parent1)
				->->Spec
				->Spec(parent2)
				->->Spec
				->->Spec(callee)
				->Spec(parent3)
				->->Spec
				',
				array(true, true, false, true, false, true, true, false),
				array('checkpoint', 'parent1', 'callee', 'parent2', 'callee', 'parent3', 'callee'),
				array('parent1' => 'callee', 'parent3' => 'callee'),
			),
			
			array(
				'
				Spec(checkpoint)
				->Spec(parent1)
				->Spec(parent2)
				->Spec(parent3)
				->->Spec
				->->Spec
				->->Spec(callee)
				',
				array(true, true, true, true, false, false, true),
				array('checkpoint', 'parent1', 'callee', 'parent2', 'callee', 'parent3', 'callee'),
				array('parent1' => array(4, 5, 'callee'), 'parent2' => array(4, 5, 'callee')),
			),
			
			array(
				'
				Spec(checkpoint)
				->Spec(ancestor1)
				->Spec(ancestor2)
				->Spec(ancestor3)
				->->Spec
				->->Spec
				->->Spec
				->->Spec(parent1)
				->->Spec(parent2)
				->->Spec(parent3)
				->->->Spec
				->->->Spec
				->->->Spec(callee)
				',
				array(true, true, true, true, false, false, false, true, true, true, false, false, true),
				array(
					'checkpoint',
					'ancestor1', 'parent1', 'callee', 'parent2', 'callee', 'parent3', 'callee',
					'ancestor2', 'parent1', 'callee', 'parent2', 'callee', 'parent3', 'callee',
					'ancestor3', 'parent1', 'callee', 'parent2', 'callee', 'parent3', 'callee',
				),
				array(
					'ancestor1' => array(4, 5, 6, 'parent1', 'parent2', 'parent3'),
					'ancestor2' => array(4, 5, 6, 'parent1', 'parent2', 'parent3'),
					'parent1' => array(10, 11, 'callee'),
					'parent2' => array(10, 11, 'callee'),
				),
			),
		);
	}

	/**
	 * @dataProvider providerChildSpecRunWithoutRunningParent
	 */
	public function testChildSpecRunWithoutRunningParent_DisablesSiblingSpecsUpToRootAndRunRootSpec($specTreePattern, $specStates, $calledSpecs, $specBindings = array())
	{
		\spectrum\tests\Test::$temp["specStates"] = array();
		\spectrum\tests\Test::$temp["calledSpecs"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
					);
				}
				
				public function onSpecRunInit()
				{
					$ownerSpec = $this->getOwnerSpec();
					
					if ($ownerSpec === \spectrum\tests\Test::$temp["specs"]["checkpoint"])
					{
						foreach (\spectrum\tests\Test::$temp["specs"] as $spec)
							\spectrum\tests\Test::$temp["specStates"][] = $spec->isEnabled();
					}
					
					\spectrum\tests\Test::$temp["calledSpecs"][] = array_search($ownerSpec, \spectrum\tests\Test::$temp["specs"], true);
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsTree($specTreePattern);
		
		foreach ($specBindings as $parent => $children)
		{
			foreach ((array) $children as $child)
				\spectrum\tests\Test::$temp["specs"][$parent]->bindChildSpec(\spectrum\tests\Test::$temp["specs"][$child]);
		}
		
		\spectrum\tests\Test::$temp["specs"]["callee"]->run();
		
		$this->assertSame($specStates, \spectrum\tests\Test::$temp["specStates"]);
		$this->assertSame($calledSpecs, \spectrum\tests\Test::$temp["calledSpecs"]);
	}
	
	/**
	 * @dataProvider providerChildSpecRunWithoutRunningParent
	 */
	public function testChildSpecRunWithoutRunningParent_EnablesDisabledSpecsAfterRun($specTreePattern, $specStates, $calledSpecs, $specBindings = array())
	{
		$specs = $this->createSpecsTree($specTreePattern);
		
		foreach ($specBindings as $parent => $children)
		{
			foreach ((array) $children as $child)
				$specs[$parent]->bindChildSpec($specs[$child]);
		}
		
		$specs["callee"]->run();
		
		foreach ($specs as $spec)
			$this->assertSame(true, $spec->isEnabled());
	}
	
	public function providerDisabledChildSpecs()
	{
		return array(
			array('
				Spec
				->Spec(callee)
				->Spec(disabled)
			'),
			
			array('
				Spec
				->Spec
				->->Spec(callee)
				->->Spec(disabled)
			'),
		);
	}

	/**
	 * @dataProvider providerDisabledChildSpecs
	 */
	public function testChildSpecRunWithoutRunningParent_DoesNotEnableUserDisabledSpecsAfterRun($specTreePattern)
	{
		$specs = $this->createSpecsTree($specTreePattern);
		$specs["disabled"]->disable();
		$specs["callee"]->run();
		
		$this->assertSame(false, $specs["disabled"]->isEnabled());
	}
	
	public function testChildSpecRunWithoutRunningParent_ReturnsRootSpecRunResult()
	{
		$resultBufferClassName = $this->createClass('
			class ... extends \spectrum\core\ResultBuffer
			{
				public function getTotalResult()
				{
					if ($this->getOwnerSpec() === \spectrum\tests\Test::$temp["specs"][0])
						return true;
					else
						return false;
				}
			}
		');
		
		config::setResultBufferClass($resultBufferClassName);
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsTree('
			Spec
			->Spec
			->->Spec
		');
		
		$this->assertSame(true, \spectrum\tests\Test::$temp["specs"][2]->run());
	}
	
	public function testChildSpecRunWithoutRunningParent_RootIsAlreadyRunning_ThrowsException()
	{
		\spectrum\tests\Test::$temp["exception"] = null;
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
					);
				}
				
				public function onSpecRunInit()
				{
					if (\spectrum\tests\Test::$temp["specs"]["caller"] === $this->getOwnerSpec())
					{
						try
						{
							\spectrum\tests\Test::$temp["specs"]["callee"]->run();
						}
						catch (\Exception $e)
						{
							\spectrum\tests\Test::$temp["exception"] = $e;
						}
					}
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsTree('
			Spec(caller)
			->Spec
			->->Spec
			->->->Spec(callee)
		');
		
		\spectrum\tests\Test::$temp["specs"]["callee"]->setName('aaa');
		\spectrum\tests\Test::$temp["specs"]["caller"]->run();

		$this->assertInstanceOf('\spectrum\core\Exception', \spectrum\tests\Test::$temp["exception"]);
		$this->assertSame('Root spec of spec "aaa" is already running', \spectrum\tests\Test::$temp["exception"]->getMessage());
	}
	
	public function testChildSpecRunWithoutRunningParent_RootIsAlreadyRunning_StopsRunByExceptionThrowing()
	{
		\spectrum\tests\Test::$temp["calledSpecs"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
					);
				}
				
				public function onSpecRunInit()
				{
					\spectrum\tests\Test::$temp["calledSpecs"][] = $this->getOwnerSpec();
					
					if (\spectrum\tests\Test::$temp["specs"]["caller"] === $this->getOwnerSpec())
						\spectrum\tests\Test::$temp["specs"]["callee"]->run();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsTree('
			Spec(caller)
			->Spec
			->->Spec
			->->->Spec(callee)
		');
		
		\spectrum\tests\Test::$temp["specs"]["callee"]->setName('aaa');

		$this->assertThrowsException('\spectrum\core\Exception', 'Root spec of spec "aaa" is already running', function(){
			\spectrum\tests\Test::$temp["specs"]["caller"]->run();
		});
		
		$this->assertSame(array(\spectrum\tests\Test::$temp["specs"]["caller"]), \spectrum\tests\Test::$temp["calledSpecs"]);
	}
	
/**/
	
	public function testRootSpecRun_EnablesRunningFlagDuringRun()
	{
		\spectrum\tests\Test::$temp["isRunningCallResults"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onEndingSpecExecute", "method" => "onEndingSpecExecute", "order" => 100),
					);
				}
				
				public function onEndingSpecExecute()
				{
					if ($this->getOwnerSpec() === \spectrum\tests\Test::$temp["specs"][2])
					{
						\spectrum\tests\Test::$temp["isRunningCallResults"][] = \spectrum\tests\Test::$temp["specs"][0]->isRunning();
						\spectrum\tests\Test::$temp["isRunningCallResults"][] = \spectrum\tests\Test::$temp["specs"][1]->isRunning();
						\spectrum\tests\Test::$temp["isRunningCallResults"][] = \spectrum\tests\Test::$temp["specs"][2]->isRunning();
					}
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsTree('
			Spec
			->Spec
			->->Spec
		');
		
		$this->assertSame(false, \spectrum\tests\Test::$temp["specs"][0]->isRunning());
		$this->assertSame(false, \spectrum\tests\Test::$temp["specs"][1]->isRunning());
		$this->assertSame(false, \spectrum\tests\Test::$temp["specs"][2]->isRunning());
		
		\spectrum\tests\Test::$temp["specs"][0]->run();
		
		$this->assertSame(false, \spectrum\tests\Test::$temp["specs"][0]->isRunning());
		$this->assertSame(false, \spectrum\tests\Test::$temp["specs"][1]->isRunning());
		$this->assertSame(false, \spectrum\tests\Test::$temp["specs"][2]->isRunning());
		
		$this->assertSame(array(true, true, true), \spectrum\tests\Test::$temp["isRunningCallResults"]);
	}
	
	public function testRootSpecRun_DisablesRunningFlagAfterEachChildSpecRun()
	{
		\spectrum\tests\Test::$temp["isRunningCallResults"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onEndingSpecExecute", "method" => "onEndingSpecExecute", "order" => 100),
					);
				}
				
				public function onEndingSpecExecute()
				{
					if ($this->getOwnerSpec() === \spectrum\tests\Test::$temp["specs"][4])
					{
						\spectrum\tests\Test::$temp["isRunningCallResults"][] = \spectrum\tests\Test::$temp["specs"][0]->isRunning();
						\spectrum\tests\Test::$temp["isRunningCallResults"][] = \spectrum\tests\Test::$temp["specs"][1]->isRunning();
						\spectrum\tests\Test::$temp["isRunningCallResults"][] = \spectrum\tests\Test::$temp["specs"][2]->isRunning();
						\spectrum\tests\Test::$temp["isRunningCallResults"][] = \spectrum\tests\Test::$temp["specs"][3]->isRunning();
						\spectrum\tests\Test::$temp["isRunningCallResults"][] = \spectrum\tests\Test::$temp["specs"][4]->isRunning();
					}
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsTree('
			Spec
			->Spec
			->Spec
			->Spec
			->->Spec
		');
		
		\spectrum\tests\Test::$temp["specs"][0]->run();
		$this->assertSame(array(true, false, false, true, true), \spectrum\tests\Test::$temp["isRunningCallResults"]);
	}

	public function testRootSpecRun_RunsChildSpecsForNotEndingSpecsSequentially()
	{
		\spectrum\tests\Test::$temp["runSpecs"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
					);
				}
				
				public function onSpecRunInit()
				{
					\spectrum\tests\Test::$temp["runSpecs"][] = $this->getOwnerSpec();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
			->->Spec
			->Spec
			->->Spec
			->Spec
			->->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array($specs[0], $specs[1], $specs[2], $specs[3], $specs[4], $specs[5], $specs[6]), \spectrum\tests\Test::$temp["runSpecs"]);
	}
	
	public function testRootSpecRun_RunsEnabledSpecsOnly()
	{
		\spectrum\tests\Test::$temp["runSpecs"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
					);
				}
				
				public function onSpecRunInit()
				{
					\spectrum\tests\Test::$temp["runSpecs"][] = $this->getOwnerSpec();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
			->Spec
			->Spec
			->->Spec
			->Spec
		');
		
		$specs[1]->disable();
		$specs[2]->disable();
		$specs[5]->disable();
		$specs[0]->run();
		$this->assertSame(array($specs[0], $specs[3], $specs[4]), \spectrum\tests\Test::$temp["runSpecs"]);
	}
	
	public function testRootSpecRun_DoesNotRunChildrenOfDisabledSpecs()
	{
		\spectrum\tests\Test::$temp["runSpecs"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
					);
				}
				
				public function onSpecRunInit()
				{
					\spectrum\tests\Test::$temp["runSpecs"][] = $this->getOwnerSpec();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
			->->Spec
			->->Spec
			->Spec
		');
		
		$specs[1]->disable();
		$specs[0]->run();
		$this->assertSame(array($specs[0], $specs[4]), \spectrum\tests\Test::$temp["runSpecs"]);
	}
	
	public function testRootSpecRun_ReturnsResultBufferTotalResult()
	{
		$resultBufferClassName = $this->createClass('
			class ... extends \spectrum\core\ResultBuffer
			{
				public function getTotalResult()
				{
					return \spectrum\tests\Test::$temp["totalResult"];
				}
			}
		');
		
		config::setResultBufferClass($resultBufferClassName);
		
		$spec = new Spec();
		
		\spectrum\tests\Test::$temp["totalResult"] = true;
		$this->assertSame(true, $spec->run());
		
		\spectrum\tests\Test::$temp["totalResult"] = false;
		$this->assertSame(false, $spec->run());
		
		\spectrum\tests\Test::$temp["totalResult"] = null;
		$this->assertSame(null, $spec->run());
	}
	
/**/
	
	public function testRootSpecRun_ResultBuffer_CreatesNewResultBufferWithProperLinkToOwnerSpecForEachSpec()
	{
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$resultBufferClassName = $this->createClass('
			class ... extends \spectrum\core\ResultBuffer
			{
				public function __construct(\spectrum\core\SpecInterface $ownerSpec)
				{
					\spectrum\tests\Test::$temp["resultBuffers"][] = array(
						"resultBuffer" => $this,
						"ownerSpec" => $ownerSpec,
					);
					
					return call_user_func_array("parent::__construct", func_get_args());
				}
			}
		');
		
		config::setResultBufferClass($resultBufferClassName);
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
			->Spec
			->->Spec
			->->Spec
			->Spec(parent1)
			->Spec(parent2)
			->->Spec(childSpec1)
			->->Spec(childSpec2)
		');
		
		$specs['parent1']->bindChildSpec($specs['childSpec1']);
		$specs['parent1']->bindChildSpec($specs['childSpec2']);
		$specs[0]->run();
		
		$this->assertSame(11, count(\spectrum\tests\Test::$temp["resultBuffers"]));
		
		$this->assertInstanceOf($resultBufferClassName, \spectrum\tests\Test::$temp["resultBuffers"][0]["resultBuffer"]);
		$this->assertInstanceOf($resultBufferClassName, \spectrum\tests\Test::$temp["resultBuffers"][1]["resultBuffer"]);
		$this->assertInstanceOf($resultBufferClassName, \spectrum\tests\Test::$temp["resultBuffers"][2]["resultBuffer"]);
		$this->assertInstanceOf($resultBufferClassName, \spectrum\tests\Test::$temp["resultBuffers"][3]["resultBuffer"]);
		$this->assertInstanceOf($resultBufferClassName, \spectrum\tests\Test::$temp["resultBuffers"][4]["resultBuffer"]);
		$this->assertInstanceOf($resultBufferClassName, \spectrum\tests\Test::$temp["resultBuffers"][5]["resultBuffer"]);
		$this->assertInstanceOf($resultBufferClassName, \spectrum\tests\Test::$temp["resultBuffers"][6]["resultBuffer"]);
		$this->assertInstanceOf($resultBufferClassName, \spectrum\tests\Test::$temp["resultBuffers"][7]["resultBuffer"]);
		$this->assertInstanceOf($resultBufferClassName, \spectrum\tests\Test::$temp["resultBuffers"][8]["resultBuffer"]);
		$this->assertInstanceOf($resultBufferClassName, \spectrum\tests\Test::$temp["resultBuffers"][9]["resultBuffer"]);
		$this->assertInstanceOf($resultBufferClassName, \spectrum\tests\Test::$temp["resultBuffers"][10]["resultBuffer"]);
		
		$this->assertSame($specs[0], \spectrum\tests\Test::$temp["resultBuffers"][0]["ownerSpec"]);
		$this->assertSame($specs[1], \spectrum\tests\Test::$temp["resultBuffers"][1]["ownerSpec"]);
		$this->assertSame($specs[2], \spectrum\tests\Test::$temp["resultBuffers"][2]["ownerSpec"]);
		$this->assertSame($specs[3], \spectrum\tests\Test::$temp["resultBuffers"][3]["ownerSpec"]);
		$this->assertSame($specs[4], \spectrum\tests\Test::$temp["resultBuffers"][4]["ownerSpec"]);
		$this->assertSame($specs['parent1'], \spectrum\tests\Test::$temp["resultBuffers"][5]["ownerSpec"]);
		$this->assertSame($specs['childSpec1'], \spectrum\tests\Test::$temp["resultBuffers"][6]["ownerSpec"]);
		$this->assertSame($specs['childSpec2'], \spectrum\tests\Test::$temp["resultBuffers"][7]["ownerSpec"]);
		$this->assertSame($specs['parent2'], \spectrum\tests\Test::$temp["resultBuffers"][8]["ownerSpec"]);
		$this->assertSame($specs['childSpec1'], \spectrum\tests\Test::$temp["resultBuffers"][9]["ownerSpec"]);
		$this->assertSame($specs['childSpec2'], \spectrum\tests\Test::$temp["resultBuffers"][10]["ownerSpec"]);
		
		$this->assertSame($specs[0], \spectrum\tests\Test::$temp["resultBuffers"][0]["resultBuffer"]->getOwnerSpec());
		$this->assertSame($specs[1], \spectrum\tests\Test::$temp["resultBuffers"][1]["resultBuffer"]->getOwnerSpec());
		$this->assertSame($specs[2], \spectrum\tests\Test::$temp["resultBuffers"][2]["resultBuffer"]->getOwnerSpec());
		$this->assertSame($specs[3], \spectrum\tests\Test::$temp["resultBuffers"][3]["resultBuffer"]->getOwnerSpec());
		$this->assertSame($specs[4], \spectrum\tests\Test::$temp["resultBuffers"][4]["resultBuffer"]->getOwnerSpec());
		$this->assertSame($specs['parent1'], \spectrum\tests\Test::$temp["resultBuffers"][5]["resultBuffer"]->getOwnerSpec());
		$this->assertSame($specs['childSpec1'], \spectrum\tests\Test::$temp["resultBuffers"][6]["resultBuffer"]->getOwnerSpec());
		$this->assertSame($specs['childSpec2'], \spectrum\tests\Test::$temp["resultBuffers"][7]["resultBuffer"]->getOwnerSpec());
		$this->assertSame($specs['parent2'], \spectrum\tests\Test::$temp["resultBuffers"][8]["resultBuffer"]->getOwnerSpec());
		$this->assertSame($specs['childSpec1'], \spectrum\tests\Test::$temp["resultBuffers"][9]["resultBuffer"]->getOwnerSpec());
		$this->assertSame($specs['childSpec2'], \spectrum\tests\Test::$temp["resultBuffers"][10]["resultBuffer"]->getOwnerSpec());
		
		foreach (\spectrum\tests\Test::$temp["resultBuffers"] as $key => $val)
		{
			foreach (\spectrum\tests\Test::$temp["resultBuffers"] as $key2 => $val2)
			{
				if ($key != $key2)
					$this->assertNotSame($val2["resultBuffer"], $val["resultBuffer"]);
			}
		}
	}
	
	public function testRootSpecRun_ResultBuffer_CreatesNewResultBufferForEachRun()
	{
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$resultBufferClassName = $this->createClass('
			class ... implements \spectrum\core\ResultBufferInterface
			{
				public function __construct(\spectrum\core\SpecInterface $ownerSpec)
				{
					\spectrum\tests\Test::$temp["resultBuffers"][] = $this;
				}
			
				public function getOwnerSpec(){}
				
				public function addResult($result, $details = null){}
				public function getResults(){}
				public function getTotalResult(){}
				
				public function lock(){}
				public function isLocked(){}
			}
		');
		
		config::setResultBufferClass($resultBufferClassName);
		
		$spec = new Spec();
		$spec->run();
		$spec->run();
		$spec->run();
		
		$this->assertSame(3, count(\spectrum\tests\Test::$temp["resultBuffers"]));

		$this->assertNotSame(\spectrum\tests\Test::$temp["resultBuffers"][0], \spectrum\tests\Test::$temp["resultBuffers"][1]);
		$this->assertNotSame(\spectrum\tests\Test::$temp["resultBuffers"][0], \spectrum\tests\Test::$temp["resultBuffers"][2]);
		
		$this->assertNotSame(\spectrum\tests\Test::$temp["resultBuffers"][1], \spectrum\tests\Test::$temp["resultBuffers"][0]);
		$this->assertNotSame(\spectrum\tests\Test::$temp["resultBuffers"][1], \spectrum\tests\Test::$temp["resultBuffers"][2]);
		
		$this->assertNotSame(\spectrum\tests\Test::$temp["resultBuffers"][2], \spectrum\tests\Test::$temp["resultBuffers"][0]);
		$this->assertNotSame(\spectrum\tests\Test::$temp["resultBuffers"][2], \spectrum\tests\Test::$temp["resultBuffers"][1]);
	}
	
	public function testRootSpecRun_ResultBuffer_UnsetResultBufferLinkAfterRun()
	{
		$specs = $this->createSpecsTree('
			Spec
			->Spec
			->->Spec
			->->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(null, $specs[0]->getResultBuffer());
		$this->assertSame(null, $specs[1]->getResultBuffer());
		$this->assertSame(null, $specs[2]->getResultBuffer());
		$this->assertSame(null, $specs[3]->getResultBuffer());
	}
	
	public function testRootSpecRun_ResultBuffer_DoesNotClearResultBufferDataAfterRun()
	{
		\spectrum\tests\Test::$temp["counter"] = 0;
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onEndingSpecExecute", "method" => "onEndingSpecExecute", "order" => 100),
						array("event" => "onSpecRunFinish", "method" => "onSpecRunFinish", "order" => 100),
					);
				}
				
				public function onEndingSpecExecute()
				{
					\spectrum\tests\Test::$temp["counter"]++;
				
					$resultBuffer = $this->getOwnerSpec()->getResultBuffer();
					$resultBuffer->addResult(false, "aaa" . \spectrum\tests\Test::$temp["counter"] . "aaa");
					$resultBuffer->addResult(true, "bbb" . \spectrum\tests\Test::$temp["counter"] . "bbb");
					$resultBuffer->addResult(null, "ccc" . \spectrum\tests\Test::$temp["counter"] . "ccc");
				}
				
				public function onSpecRunFinish()
				{
					\spectrum\tests\Test::$temp["resultBuffers"][] = $this->getOwnerSpec()->getResultBuffer();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		
		$this->assertSame(2, count(\spectrum\tests\Test::$temp["resultBuffers"]));
		
		$this->assertSame(array(
			array('result' => false, 'details' => 'aaa1aaa'),
			array('result' => true, 'details' => 'bbb1bbb'),
			array('result' => null, 'details' => 'ccc1ccc'),
		), \spectrum\tests\Test::$temp["resultBuffers"][0]->getResults());
		
		$this->assertSame(array(
			array('result' => false, 'details' => $specs[1]),
		), \spectrum\tests\Test::$temp["resultBuffers"][1]->getResults());
	}
	
	public function testRootSpecRun_ResultBuffer_NotEndingSpec_CreatesLockedResultBufferAfterChildSpecsRun()
	{
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunFinish", "method" => "onSpecRunFinish", "order" => 100),
					);
				}
				
				public function onSpecRunFinish()
				{
					if ($this->getOwnerSpec() === \spectrum\tests\Test::$temp["specs"][0])
						\spectrum\tests\Test::$temp["resultBuffers"][] = \spectrum\tests\Test::$temp["specs"][0]->getResultBuffer();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		\spectrum\tests\Test::$temp["specs"][0]->run();
		$this->assertSame(1, count(\spectrum\tests\Test::$temp["resultBuffers"]));
		$this->assertSame(\spectrum\tests\Test::$temp["specs"][0], \spectrum\tests\Test::$temp["resultBuffers"][0]->getOwnerSpec());
		$this->assertSame(true, \spectrum\tests\Test::$temp["resultBuffers"][0]->isLocked());
	}
	
	public function testRootSpecRun_ResultBuffer_NotEndingSpec_DoesNotCreateResultBufferWhileChildSpecsRun()
	{
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
					);
				}
				
				public function onSpecRunInit()
				{
					if ($this->getOwnerSpec() === \spectrum\tests\Test::$temp["specs"][1])
						\spectrum\tests\Test::$temp["resultBuffers"][] = \spectrum\tests\Test::$temp["specs"][0]->getResultBuffer();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		\spectrum\tests\Test::$temp["specs"][0]->run();
		$this->assertSame(array(null), \spectrum\tests\Test::$temp["resultBuffers"]);
	}
	
	public function testRootSpecRun_ResultBuffer_NotEndingSpec_PutsChildSpecRunResultWithChildSpecObjectToResultBufferForEachChildSpec()
	{
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunFinish", "method" => "onSpecRunFinish", "order" => 100),
					);
				}
				
				public function onSpecRunFinish()
				{
					if ($this->getOwnerSpec() === \spectrum\tests\Test::$temp["specs"][0])
						\spectrum\tests\Test::$temp["resultBuffers"][] = \spectrum\tests\Test::$temp["specs"][0]->getResultBuffer();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$resultBufferClassName = $this->createClass('
			class ... extends \spectrum\core\ResultBuffer
			{
				public function getTotalResult()
				{
					if ($this->getOwnerSpec() === \spectrum\tests\Test::$temp["specs"][1])
						return true;
					else if ($this->getOwnerSpec() === \spectrum\tests\Test::$temp["specs"][2])
						return false;
					else if ($this->getOwnerSpec() === \spectrum\tests\Test::$temp["specs"][3])
						return null;
					else
						return call_user_func_array("parent::getTotalResult", func_get_args());
				}
			}
		');
		
		config::setResultBufferClass($resultBufferClassName);
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsTree('
			Spec
			->Spec
			->Spec
			->Spec
		');
		
		\spectrum\tests\Test::$temp["specs"][0]->run();
		$this->assertSame(1, count(\spectrum\tests\Test::$temp["resultBuffers"]));
		$this->assertSame(array(
			array('result' => true, 'details' => \spectrum\tests\Test::$temp["specs"][1]),
			array('result' => false, 'details' => \spectrum\tests\Test::$temp["specs"][2]),
			array('result' => null, 'details' => \spectrum\tests\Test::$temp["specs"][3]),
		), \spectrum\tests\Test::$temp["resultBuffers"][0]->getResults());
	}
	
	public function testRootSpecRun_ResultBuffer_EndingSpec_CreatesEmptyAndNotLockedResultBuffer()
	{
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunFinish", "method" => "onSpecRunFinish", "order" => 100),
					);
				}
				
				public function onSpecRunFinish()
				{
					if ($this->getOwnerSpec() === \spectrum\tests\Test::$temp["specs"][1])
						\spectrum\tests\Test::$temp["resultBuffers"][] = \spectrum\tests\Test::$temp["specs"][1]->getResultBuffer();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		\spectrum\tests\Test::$temp["specs"][0]->run();
		$this->assertSame(1, count(\spectrum\tests\Test::$temp["resultBuffers"]));
		$this->assertSame(\spectrum\tests\Test::$temp["specs"][1], \spectrum\tests\Test::$temp["resultBuffers"][0]->getOwnerSpec());
		$this->assertSame(array(), \spectrum\tests\Test::$temp["resultBuffers"][0]->getResults());
		$this->assertSame(false, \spectrum\tests\Test::$temp["resultBuffers"][0]->isLocked());
	}

/**/
	
	public function testRootSpecRun_EventDispatch_OnRootSpecRunBefore_IsDispatchedOnRootSpecRunOnly()
	{
		\spectrum\tests\Test::$temp["runSpecs"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onRootSpecRunBefore", "method" => "onRootSpecRunBefore", "order" => 100),
					);
				}
				
				public function onRootSpecRunBefore()
				{
					\spectrum\tests\Test::$temp["runSpecs"][] = $this->getOwnerSpec();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
			->->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array($specs[0]), \spectrum\tests\Test::$temp["runSpecs"]);
	}
	
	public function testRootSpecRun_EventDispatch_OnRootSpecRunBefore_DoesNotPassArgumentsToCalleeMethod()
	{
		\spectrum\tests\Test::$temp["passedArguments"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onRootSpecRunBefore", "method" => "onRootSpecRunBefore", "order" => 100),
					);
				}
				
				public function onRootSpecRunBefore()
				{
					\spectrum\tests\Test::$temp["passedArguments"][] = func_get_args();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(array()), \spectrum\tests\Test::$temp["passedArguments"]);
	}
	
	public function testRootSpecRun_EventDispatch_OnRootSpecRunBefore_IsDispatchedBeforeRunningFlagEnable()
	{
		\spectrum\tests\Test::$temp["isRunningCallResults"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onRootSpecRunBefore", "method" => "onRootSpecRunBefore", "order" => 100),
					);
				}
				
				public function onRootSpecRunBefore()
				{
					\spectrum\tests\Test::$temp["isRunningCallResults"][] = $this->getOwnerSpec()->isRunning();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(false), \spectrum\tests\Test::$temp["isRunningCallResults"]);
	}
	
	public function testRootSpecRun_EventDispatch_OnRootSpecRunBefore_IsDispatchedBeforeResultBufferCreate()
	{
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onRootSpecRunBefore", "method" => "onRootSpecRunBefore", "order" => 100),
					);
				}
				
				public function onRootSpecRunBefore()
				{
					\spectrum\tests\Test::$temp["resultBuffers"][] = $this->getOwnerSpec()->getResultBuffer();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(null), \spectrum\tests\Test::$temp["resultBuffers"]);
	}

/**/
	
	public function testRootSpecRun_EventDispatch_OnRootSpecRunAfter_IsDispatchedOnRootSpecRunOnly()
	{
		\spectrum\tests\Test::$temp["runSpecs"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onRootSpecRunAfter", "method" => "onRootSpecRunAfter", "order" => 100),
					);
				}
				
				public function onRootSpecRunAfter()
				{
					\spectrum\tests\Test::$temp["runSpecs"][] = $this->getOwnerSpec();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
			->->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array($specs[0]), \spectrum\tests\Test::$temp["runSpecs"]);
	}
	
	public function testRootSpecRun_EventDispatch_OnRootSpecRunAfter_DoesNotPassArgumentsToCalleeMethod()
	{
		\spectrum\tests\Test::$temp["passedArguments"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onRootSpecRunAfter", "method" => "onRootSpecRunAfter", "order" => 100),
					);
				}
				
				public function onRootSpecRunAfter()
				{
					\spectrum\tests\Test::$temp["passedArguments"][] = func_get_args();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(array()), \spectrum\tests\Test::$temp["passedArguments"]);
	}
	
	public function testRootSpecRun_EventDispatch_OnRootSpecRunAfter_IsDispatchedAfterRunningFlagDisabled()
	{
		\spectrum\tests\Test::$temp["isRunningCallResults"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onRootSpecRunAfter", "method" => "onRootSpecRunAfter", "order" => 100),
					);
				}
				
				public function onRootSpecRunAfter()
				{
					\spectrum\tests\Test::$temp["isRunningCallResults"][] = $this->getOwnerSpec()->isRunning();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(false), \spectrum\tests\Test::$temp["isRunningCallResults"]);
	}
	
	public function testRootSpecRun_EventDispatch_OnRootSpecRunAfter_IsDispatchedBeforeResultBufferLinkUnset()
	{
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onRootSpecRunAfter", "method" => "onRootSpecRunAfter", "order" => 100),
					);
				}
				
				public function onRootSpecRunAfter()
				{
					\spectrum\tests\Test::$temp["resultBuffers"][] = $this->getOwnerSpec()->getResultBuffer();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		
		$this->assertSame(1, count(\spectrum\tests\Test::$temp["resultBuffers"]));
		
		$this->assertInstanceOf('\spectrum\core\ResultBufferInterface', \spectrum\tests\Test::$temp["resultBuffers"][0]);
		$this->assertSame($specs[0], \spectrum\tests\Test::$temp["resultBuffers"][0]->getOwnerSpec());
	}
	
/**/
	
	public function testRootSpecRun_EventDispatch_OnSpecRunInit_IsDispatchedOnEverySpec()
	{
		\spectrum\tests\Test::$temp["runSpecs"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
					);
				}
				
				public function onSpecRunInit()
				{
					\spectrum\tests\Test::$temp["runSpecs"][] = $this->getOwnerSpec();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
			->->Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array($specs[0], $specs[1], $specs[2], $specs[3]), \spectrum\tests\Test::$temp["runSpecs"]);
	}
	
	public function testRootSpecRun_EventDispatch_OnSpecRunInit_DoesNotPassArgumentsToCalleeMethod()
	{
		\spectrum\tests\Test::$temp["passedArguments"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
					);
				}
				
				public function onSpecRunInit()
				{
					\spectrum\tests\Test::$temp["passedArguments"][] = func_get_args();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(array(), array()), \spectrum\tests\Test::$temp["passedArguments"]);
	}
	
	public function testRootSpecRun_EventDispatch_OnSpecRunInit_IsDispatchedAfterRunningFlagEnable()
	{
		\spectrum\tests\Test::$temp["isRunningCallResults"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
					);
				}
				
				public function onSpecRunInit()
				{
					\spectrum\tests\Test::$temp["isRunningCallResults"][] = $this->getOwnerSpec()->isRunning();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(true, true), \spectrum\tests\Test::$temp["isRunningCallResults"]);
	}
	
	public function testRootSpecRun_EventDispatch_OnSpecRunInit_IsDispatchedBeforeChildSpecRun()
	{
		\spectrum\tests\Test::$temp["runSpecs"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
					);
				}
				
				public function onSpecRunInit()
				{
					\spectrum\tests\Test::$temp["runSpecs"][] = $this->getOwnerSpec();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array($specs[0], $specs[1], $specs[2]), \spectrum\tests\Test::$temp["runSpecs"]);
	}
	
	public function testRootSpecRun_EventDispatch_OnSpecRunInit_IsDispatchedBeforeResultBufferCreate()
	{
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
					);
				}
				
				public function onSpecRunInit()
				{
					\spectrum\tests\Test::$temp["resultBuffers"][] = $this->getOwnerSpec()->getResultBuffer();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(null, null), \spectrum\tests\Test::$temp["resultBuffers"]);
	}
	
/**/
	
	public function testRootSpecRun_EventDispatch_OnSpecRunFinish_IsDispatchedOnEverySpec()
	{
		\spectrum\tests\Test::$temp["runSpecs"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunFinish", "method" => "onSpecRunFinish", "order" => 100),
					);
				}
				
				public function onSpecRunFinish()
				{
					\spectrum\tests\Test::$temp["runSpecs"][] = $this->getOwnerSpec();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
			->->Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array($specs[2], $specs[1], $specs[3], $specs[0]), \spectrum\tests\Test::$temp["runSpecs"]);
	}
	
	public function testRootSpecRun_EventDispatch_OnSpecRunFinish_DoesNotPassArgumentsToCalleeMethod()
	{
		\spectrum\tests\Test::$temp["passedArguments"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunFinish", "method" => "onSpecRunFinish", "order" => 100),
					);
				}
				
				public function onSpecRunFinish()
				{
					\spectrum\tests\Test::$temp["passedArguments"][] = func_get_args();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(array(), array()), \spectrum\tests\Test::$temp["passedArguments"]);
	}
	
	public function testRootSpecRun_EventDispatch_OnSpecRunFinish_IsDispatchedBeforeRunningFlagDisable()
	{
		\spectrum\tests\Test::$temp["isRunningCallResults"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunFinish", "method" => "onSpecRunFinish", "order" => 100),
					);
				}
				
				public function onSpecRunFinish()
				{
					\spectrum\tests\Test::$temp["isRunningCallResults"][] = $this->getOwnerSpec()->isRunning();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(true, true), \spectrum\tests\Test::$temp["isRunningCallResults"]);
	}
	
	public function testRootSpecRun_EventDispatch_OnSpecRunFinish_IsDispatchedAfterChildSpecsRun()
	{
		\spectrum\tests\Test::$temp["runSpecs"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunFinish", "method" => "onSpecRunFinish", "order" => 100),
					);
				}
				
				public function onSpecRunFinish()
				{
					\spectrum\tests\Test::$temp["runSpecs"][] = $this->getOwnerSpec();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array($specs[1], $specs[2], $specs[0]), \spectrum\tests\Test::$temp["runSpecs"]);
	}
	
	public function testRootSpecRun_EventDispatch_OnSpecRunFinish_IsDispatchedBeforeResultBufferLinkUnset()
	{
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunFinish", "method" => "onSpecRunFinish", "order" => 100),
					);
				}
				
				public function onSpecRunFinish()
				{
					\spectrum\tests\Test::$temp["resultBuffers"][] = $this->getOwnerSpec()->getResultBuffer();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		
		$this->assertSame(2, count(\spectrum\tests\Test::$temp["resultBuffers"]));
		
		$this->assertInstanceOf('\spectrum\core\ResultBufferInterface', \spectrum\tests\Test::$temp["resultBuffers"][0]);
		$this->assertSame($specs[1], \spectrum\tests\Test::$temp["resultBuffers"][0]->getOwnerSpec());
		
		$this->assertInstanceOf('\spectrum\core\ResultBufferInterface', \spectrum\tests\Test::$temp["resultBuffers"][1]);
		$this->assertSame($specs[0], \spectrum\tests\Test::$temp["resultBuffers"][1]->getOwnerSpec());
	}
	
/**/
	
	public function providerEndingSpecExecuteEvents()
	{
		return array(
			array('onEndingSpecExecuteBefore'),
			array('onEndingSpecExecute'),
			array('onEndingSpecExecuteAfter'),
		);
	}
	
	/**
	 * @dataProvider providerEndingSpecExecuteEvents
	 */
	public function testRootSpecRun_EventDispatch_EndingSpecExecuteEvents_IsDispatchedOnEndingSpecsOnly($eventName)
	{
		\spectrum\tests\Test::$temp["runSpecs"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "' . $eventName . '", "method" => "' . $eventName . '", "order" => 100),
					);
				}
				
				public function ' . $eventName . '()
				{
					\spectrum\tests\Test::$temp["runSpecs"][] = $this->getOwnerSpec();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
			->->Spec
			->->Spec
			->Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array($specs[2], $specs[3], $specs[4], $specs[5]), \spectrum\tests\Test::$temp["runSpecs"]);
	}
	
	/**
	 * @dataProvider providerEndingSpecExecuteEvents
	 */
	public function testRootSpecRun_EventDispatch_EndingSpecExecuteEvents_DoesNotPassArgumentsToCalleeMethod($eventName)
	{
		\spectrum\tests\Test::$temp["passedArguments"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "' . $eventName . '", "method" => "' . $eventName . '", "order" => 100),
					);
				}
				
				public function ' . $eventName . '()
				{
					\spectrum\tests\Test::$temp["passedArguments"][] = func_get_args();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame(array(array()), \spectrum\tests\Test::$temp["passedArguments"]);
	}
	
	/**
	 * @dataProvider providerEndingSpecExecuteEvents
	 */
	public function testRootSpecRun_EventDispatch_EndingSpecExecuteEvents_IsDispatchedAfterRunningFlagEnable($eventName)
	{
		\spectrum\tests\Test::$temp["isRunningCallResults"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "' . $eventName . '", "method" => "' . $eventName . '", "order" => 100),
					);
				}
				
				public function ' . $eventName . '()
				{
					\spectrum\tests\Test::$temp["isRunningCallResults"][] = $this->getOwnerSpec()->isRunning();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame(array(true), \spectrum\tests\Test::$temp["isRunningCallResults"]);
	}
	
	/**
	 * @dataProvider providerEndingSpecExecuteEvents
	 */
	public function testRootSpecRun_EventDispatch_EndingSpecExecuteEvents_IsDispatchedAfterResultBufferCreate($eventName)
	{
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "' . $eventName . '", "method" => "' . $eventName . '", "order" => 100),
					);
				}
				
				public function ' . $eventName . '()
				{
					\spectrum\tests\Test::$temp["resultBuffers"][] = $this->getOwnerSpec()->getResultBuffer();
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame(1, count(\spectrum\tests\Test::$temp["resultBuffers"]));
		$this->assertInstanceOf('\spectrum\core\ResultBufferInterface', \spectrum\tests\Test::$temp["resultBuffers"][0]);
	}
	
	/**
	 * @dataProvider providerEndingSpecExecuteEvents
	 */
	public function testRootSpecRun_EventDispatch_EndingSpecExecuteEvents_CatchesExceptionsAndAddsItToResultBufferAsFail($eventName)
	{
		\spectrum\tests\Test::$temp["thrownExceptions"] = array();
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "' . $eventName . '", "method" => "' . $eventName . '", "order" => 100),
					);
				}
				
				public function ' . $eventName . '()
				{
					$e = new \Exception("aaa");
					\spectrum\tests\Test::$temp["thrownExceptions"][] = $e;
					\spectrum\tests\Test::$temp["resultBuffers"][] = $this->getOwnerSpec()->getResultBuffer();
					
					throw $e;
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame(1, count(\spectrum\tests\Test::$temp["thrownExceptions"]));
		
		$results = \spectrum\tests\Test::$temp["resultBuffers"][0]->getResults();
		$this->assertSame(array(
			array('result' => false, 'details' => \spectrum\tests\Test::$temp["thrownExceptions"][0]),
		), $results);
		
		$this->assertSame('aaa', $results[0]['details']->getMessage());
	}
	
	/**
	 * @dataProvider providerEndingSpecExecuteEvents
	 */
	public function testRootSpecRun_EventDispatch_EndingSpecExecuteEvents_CatchesBreakExceptionAndDoesNotAddResultsToResultBuffer($eventName)
	{
		\spectrum\tests\Test::$temp["thrownExceptions"] = array();
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "' . $eventName . '", "method" => "' . $eventName . '", "order" => 100),
					);
				}
				
				public function ' . $eventName . '()
				{
					$e = new \spectrum\core\ExceptionBreak();
					\spectrum\tests\Test::$temp["thrownExceptions"][] = $e;
					\spectrum\tests\Test::$temp["resultBuffers"][] = $this->getOwnerSpec()->getResultBuffer();
					
					throw $e;
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame(1, count(\spectrum\tests\Test::$temp["thrownExceptions"]));
		$this->assertSame(array(), \spectrum\tests\Test::$temp["resultBuffers"][0]->getResults());
	}
	
/**/
	
	public function testRootSpecRun_EventDispatch_DispatchesRunEventsInTrueOrder()
	{
		\spectrum\tests\Test::$temp["calledEvents"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onRootSpecRunBefore", "method" => "onRootSpecRunBefore", "order" => 100),
						array("event" => "onSpecRunInit", "method" => "onSpecRunInit", "order" => 100),
						
						array("event" => "onEndingSpecExecuteBefore", "method" => "onEndingSpecExecuteBefore", "order" => 100),
						array("event" => "onEndingSpecExecute", "method" => "onEndingSpecExecute", "order" => 100),
						array("event" => "onEndingSpecExecuteAfter", "method" => "onEndingSpecExecuteAfter", "order" => 100),
						
						array("event" => "onSpecRunFinish", "method" => "onSpecRunFinish", "order" => 100),
						array("event" => "onRootSpecRunAfter", "method" => "onRootSpecRunAfter", "order" => 100),
					);
				}
				
				public function onRootSpecRunBefore(){ \spectrum\tests\Test::$temp["calledEvents"][] = __FUNCTION__; }
				public function onSpecRunInit(){ \spectrum\tests\Test::$temp["calledEvents"][] = __FUNCTION__; }
				
				public function onEndingSpecExecuteBefore(){ \spectrum\tests\Test::$temp["calledEvents"][] = __FUNCTION__; }
				public function onEndingSpecExecute(){ \spectrum\tests\Test::$temp["calledEvents"][] = __FUNCTION__; }
				public function onEndingSpecExecuteAfter(){ \spectrum\tests\Test::$temp["calledEvents"][] = __FUNCTION__; }
				
				public function onSpecRunFinish(){ \spectrum\tests\Test::$temp["calledEvents"][] = __FUNCTION__; }
				public function onRootSpecRunAfter(){ \spectrum\tests\Test::$temp["calledEvents"][] = __FUNCTION__; }
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame(array(
			'onRootSpecRunBefore',
			'onSpecRunInit',
			
			'onEndingSpecExecuteBefore',
			'onEndingSpecExecute',
			'onEndingSpecExecuteAfter',
			
			'onSpecRunFinish',
			'onRootSpecRunAfter',
		), \spectrum\tests\Test::$temp["calledEvents"]);
	}
}