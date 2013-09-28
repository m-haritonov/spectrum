<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core;
use spectrum\config;
use spectrum\core\Spec;

require_once __DIR__ . '/../init.php';

class SpecTest extends \spectrum\tests\Test
{
	public function setUp()
	{
		parent::setUp();
		config::unregisterSpecPlugins();
	}
	
	public function testPlugins_ThrowsExceptionOnAccessToNotExistingPlugin()
	{
		$spec = new Spec();
		$this->assertThrowsException('\spectrum\core\Exception', 'Undefined plugin with access name "asdfgscvsadf" in "spectrum\core\Spec" class', function() use($spec){
			$spec->asdfgscvsadf;
		});
	}
	
	public function testPlugins_ThrowsExceptionOnAccessToPluginWithEmptyAccessName()
	{
		$pluginClassName1 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return null; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		$pluginClassName2 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return ""; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		config::registerSpecPlugin($pluginClassName1);
		config::registerSpecPlugin($pluginClassName2);
		$spec = new Spec();
		
		$this->assertThrowsException('\spectrum\core\Exception', 'Access to plugins by empty access name is deny', function() use($spec){
			$spec->{null};
		});
		
		$this->assertThrowsException('\spectrum\core\Exception', 'Access to plugins by empty access name is deny', function() use($spec){
			$spec->{''};
		});
	}
	
/**/
	
	public function testPlugins_Activation_ActivateMomentIsFirstAccess_ActivatesPluginOnAccessAndReturnsProperPluginInstance()
	{
		\spectrum\tests\Test::$temp["activateCount"] = 0;
		\spectrum\tests\Test::$temp["pluginInstanceOnActivate"] = null;
		
		$pluginClassName = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec)
				{
					\spectrum\tests\Test::$temp["activateCount"]++;
					\spectrum\tests\Test::$temp["pluginInstanceOnActivate"] = $this;
				}
				
				public function getOwnerSpec(){}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		$spec = new Spec();
		$this->assertSame(0, \spectrum\tests\Test::$temp["activateCount"]);
		$spec->aaa;
		$this->assertSame(1, \spectrum\tests\Test::$temp["activateCount"]);
		$this->assertSame($spec->aaa, \spectrum\tests\Test::$temp["pluginInstanceOnActivate"]);
	}
	
	public function testPlugins_Activation_ActivateMomentIsFirstAccess_ActivatesAllPluginsWithCorrectClasses()
	{
		\spectrum\tests\Test::$temp["plugin1"]["pluginInstanceOnActivate"] = null;
		\spectrum\tests\Test::$temp["plugin2"]["pluginInstanceOnActivate"] = null;
		\spectrum\tests\Test::$temp["plugin3"]["pluginInstanceOnActivate"] = null;
		
		$pluginClassName1 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec)
				{
					\spectrum\tests\Test::$temp["plugin1"]["pluginInstanceOnActivate"] = $this;
				}
				
				public function getOwnerSpec(){}
			}
		');
		
		$pluginClassName2 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "bbb"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec)
				{
					\spectrum\tests\Test::$temp["plugin2"]["pluginInstanceOnActivate"] = $this;
				}
				
				public function getOwnerSpec(){}
			}
		');
		
		$pluginClassName3 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "ccc"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec)
				{
					\spectrum\tests\Test::$temp["plugin3"]["pluginInstanceOnActivate"] = $this;
				}
				
				public function getOwnerSpec(){}
			}
		');
		
		config::registerSpecPlugin($pluginClassName1);
		config::registerSpecPlugin($pluginClassName2);
		config::registerSpecPlugin($pluginClassName3);
		$spec = new Spec();
		
		$spec->aaa;
		$this->assertSame($spec->aaa, \spectrum\tests\Test::$temp["plugin1"]["pluginInstanceOnActivate"]);
		
		$spec->bbb;
		$this->assertSame($spec->bbb, \spectrum\tests\Test::$temp["plugin2"]["pluginInstanceOnActivate"]);
		
		$spec->ccc;
		$this->assertSame($spec->ccc, \spectrum\tests\Test::$temp["plugin3"]["pluginInstanceOnActivate"]);
		
		$this->assertInstanceOf($pluginClassName1, \spectrum\tests\Test::$temp["plugin1"]["pluginInstanceOnActivate"]);
		$this->assertInstanceOf($pluginClassName2, \spectrum\tests\Test::$temp["plugin2"]["pluginInstanceOnActivate"]);
		$this->assertInstanceOf($pluginClassName3, \spectrum\tests\Test::$temp["plugin3"]["pluginInstanceOnActivate"]);
		
		$this->assertNotSame(\spectrum\tests\Test::$temp["plugin1"]["pluginInstanceOnActivate"], \spectrum\tests\Test::$temp["plugin2"]["pluginInstanceOnActivate"]);
		$this->assertNotSame(\spectrum\tests\Test::$temp["plugin2"]["pluginInstanceOnActivate"], \spectrum\tests\Test::$temp["plugin3"]["pluginInstanceOnActivate"]);
		$this->assertNotSame(\spectrum\tests\Test::$temp["plugin3"]["pluginInstanceOnActivate"], \spectrum\tests\Test::$temp["plugin1"]["pluginInstanceOnActivate"]);
	}
	
	public function testPlugins_Activation_ActivateMomentIsFirstAccess_DoesNotActivatePluginOnSpecInstanceCreation()
	{
		\spectrum\tests\Test::$temp["activateCount"] = 0;
		
		$pluginClassName = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec)
				{
					\spectrum\tests\Test::$temp["activateCount"]++;
				}
				
				public function getOwnerSpec(){}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		new Spec();
		$this->assertSame(0, \spectrum\tests\Test::$temp["activateCount"]);
	}
	
	public function testPlugins_Activation_ActivateMomentIsFirstAccess_DoesNotReactivatePluginOnPluginAccess()
	{
		\spectrum\tests\Test::$temp["activateCount"] = 0;
		
		$pluginClassName = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec)
				{
					\spectrum\tests\Test::$temp["activateCount"]++;
				}
				
				public function getOwnerSpec(){}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		$spec = new Spec();
		$spec->aaa;
		$spec->aaa;
		$spec->aaa;
		$this->assertSame(1, \spectrum\tests\Test::$temp["activateCount"]);
	}
		
	public function testPlugins_Activation_ActivateMomentIsFirstAccess_DoesNotReactivatePluginOnPluginEventDispatching()
	{
		\spectrum\tests\Test::$temp["activateCount"] = 0;
		\spectrum\tests\Test::$temp["eventDispatchCount"] = 0;
		
		$pluginClassName = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunStart", "method" => "onSpecRunStart", "order" => 100),
					);
				}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec)
				{
					\spectrum\tests\Test::$temp["activateCount"]++;
				}
				
				public function getOwnerSpec(){}
				public function onSpecRunStart()
				{
					\spectrum\tests\Test::$temp["eventDispatchCount"]++;
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		$spec = new Spec();
		$spec->run();
		$this->assertSame(1, \spectrum\tests\Test::$temp["activateCount"]);
		$this->assertSame(1, \spectrum\tests\Test::$temp["eventDispatchCount"]);
	}
	
/**/
	
	public function testPlugins_Activation_ActivateMomentIsEveryAccess_ActivatesPluginOnEveryAccessAndReturnsProperPluginInstance()
	{
		\spectrum\tests\Test::$temp["activateCount"] = 0;
		\spectrum\tests\Test::$temp["pluginInstanceOnActivate"] = null;
		
		$pluginClassName = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "everyAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec)
				{
					\spectrum\tests\Test::$temp["activateCount"]++;
					\spectrum\tests\Test::$temp["pluginInstanceOnActivate"] = $this;
				}
				
				public function getOwnerSpec(){}
			}
		');
		
		$instances = array();
		
		config::registerSpecPlugin($pluginClassName);
		$spec = new Spec();
		$this->assertSame(0, \spectrum\tests\Test::$temp["activateCount"]);
		
		$instance = $spec->aaa;
		$this->assertSame(1, \spectrum\tests\Test::$temp["activateCount"]);
		$this->assertSame($instance, \spectrum\tests\Test::$temp["pluginInstanceOnActivate"]);
		$this->assertFalse(in_array(\spectrum\tests\Test::$temp["pluginInstanceOnActivate"], $instances, true));
		$instances[] = \spectrum\tests\Test::$temp["pluginInstanceOnActivate"];
		
		$instance = $spec->aaa;
		$this->assertSame(2, \spectrum\tests\Test::$temp["activateCount"]);
		$this->assertSame($instance, \spectrum\tests\Test::$temp["pluginInstanceOnActivate"]);
		$this->assertFalse(in_array(\spectrum\tests\Test::$temp["pluginInstanceOnActivate"], $instances, true));
		$instances[] = \spectrum\tests\Test::$temp["pluginInstanceOnActivate"];
		
		$instance = $spec->aaa;
		$this->assertSame(3, \spectrum\tests\Test::$temp["activateCount"]);
		$this->assertSame($instance, \spectrum\tests\Test::$temp["pluginInstanceOnActivate"]);
		$this->assertFalse(in_array(\spectrum\tests\Test::$temp["pluginInstanceOnActivate"], $instances, true));
		$instances[] = \spectrum\tests\Test::$temp["pluginInstanceOnActivate"];
	}
	
	public function testPlugins_Activation_ActivateMomentIsEveryAccess_ActivatesAllPluginsWithCorrectClasses()
	{
		\spectrum\tests\Test::$temp["plugin1"]["pluginInstanceOnActivate"] = null;
		\spectrum\tests\Test::$temp["plugin2"]["pluginInstanceOnActivate"] = null;
		\spectrum\tests\Test::$temp["plugin3"]["pluginInstanceOnActivate"] = null;
		
		$pluginClassName1 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "everyAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec)
				{
					\spectrum\tests\Test::$temp["plugin1"]["pluginInstanceOnActivate"] = $this;
				}
				
				public function getOwnerSpec(){}
			}
		');
		
		$pluginClassName2 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "bbb"; }
				static public function getActivateMoment(){ return "everyAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec)
				{
					\spectrum\tests\Test::$temp["plugin2"]["pluginInstanceOnActivate"] = $this;
				}
				
				public function getOwnerSpec(){}
			}
		');
		
		$pluginClassName3 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "ccc"; }
				static public function getActivateMoment(){ return "everyAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec)
				{
					\spectrum\tests\Test::$temp["plugin3"]["pluginInstanceOnActivate"] = $this;
				}
				
				public function getOwnerSpec(){}
			}
		');
		
		config::registerSpecPlugin($pluginClassName1);
		config::registerSpecPlugin($pluginClassName2);
		config::registerSpecPlugin($pluginClassName3);
		$spec = new Spec();
		
		$spec->aaa;
		$this->assertSame($spec->aaa, \spectrum\tests\Test::$temp["plugin1"]["pluginInstanceOnActivate"]);
		
		$spec->bbb;
		$this->assertSame($spec->bbb, \spectrum\tests\Test::$temp["plugin2"]["pluginInstanceOnActivate"]);
		
		$spec->ccc;
		$this->assertSame($spec->ccc, \spectrum\tests\Test::$temp["plugin3"]["pluginInstanceOnActivate"]);
		
		$this->assertInstanceOf($pluginClassName1, \spectrum\tests\Test::$temp["plugin1"]["pluginInstanceOnActivate"]);
		$this->assertInstanceOf($pluginClassName2, \spectrum\tests\Test::$temp["plugin2"]["pluginInstanceOnActivate"]);
		$this->assertInstanceOf($pluginClassName3, \spectrum\tests\Test::$temp["plugin3"]["pluginInstanceOnActivate"]);
		
		$this->assertNotSame(\spectrum\tests\Test::$temp["plugin1"]["pluginInstanceOnActivate"], \spectrum\tests\Test::$temp["plugin2"]["pluginInstanceOnActivate"]);
		$this->assertNotSame(\spectrum\tests\Test::$temp["plugin2"]["pluginInstanceOnActivate"], \spectrum\tests\Test::$temp["plugin3"]["pluginInstanceOnActivate"]);
		$this->assertNotSame(\spectrum\tests\Test::$temp["plugin3"]["pluginInstanceOnActivate"], \spectrum\tests\Test::$temp["plugin1"]["pluginInstanceOnActivate"]);
	}
	
	public function testPlugins_Activation_ActivateMomentIsEveryAccess_ReactivatesPluginOnEveryEventDispatching()
	{
		\spectrum\tests\Test::$temp["activateCount"] = 0;
		\spectrum\tests\Test::$temp["eventDispatchCount"] = 0;
		\spectrum\tests\Test::$temp["pluginInstanceOnActivate"] = null;
		\spectrum\tests\Test::$temp["pluginInstanceOnEventDispatch"] = null;

		$pluginClassName = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "everyAccess"; }
				static public function getEventListeners()
				{
					return array(
						array("event" => "onSpecRunStart", "method" => "onSpecRunStart", "order" => 100),
					);
				}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec)
				{
					\spectrum\tests\Test::$temp["activateCount"]++;
					\spectrum\tests\Test::$temp["pluginInstanceOnActivate"] = $this;
				}
				
				public function getOwnerSpec(){}
				public function onSpecRunStart()
				{
					\spectrum\tests\Test::$temp["eventDispatchCount"]++;
					\spectrum\tests\Test::$temp["pluginInstanceOnEventDispatch"] = $this;
				}
			}
		');
		
		$instances = array();
		config::registerSpecPlugin($pluginClassName);
		$this->assertSame(0, \spectrum\tests\Test::$temp["activateCount"]);
		$spec = new Spec();
		
		$spec->run();
		$this->assertSame(1, \spectrum\tests\Test::$temp["activateCount"]);
		$this->assertSame(1, \spectrum\tests\Test::$temp["eventDispatchCount"]);
		$this->assertSame(\spectrum\tests\Test::$temp["pluginInstanceOnEventDispatch"], \spectrum\tests\Test::$temp["pluginInstanceOnActivate"]);
		$this->assertFalse(in_array(\spectrum\tests\Test::$temp["pluginInstanceOnEventDispatch"], $instances, true));
		$instances[] = \spectrum\tests\Test::$temp["pluginInstanceOnEventDispatch"];
		
		$spec->run();
		$this->assertSame(2, \spectrum\tests\Test::$temp["activateCount"]);
		$this->assertSame(2, \spectrum\tests\Test::$temp["eventDispatchCount"]);
		$this->assertSame(\spectrum\tests\Test::$temp["pluginInstanceOnEventDispatch"], \spectrum\tests\Test::$temp["pluginInstanceOnActivate"]);
		$this->assertFalse(in_array(\spectrum\tests\Test::$temp["pluginInstanceOnEventDispatch"], $instances, true));
		$instances[] = \spectrum\tests\Test::$temp["pluginInstanceOnEventDispatch"];
		
		$spec->run();
		$this->assertSame(3, \spectrum\tests\Test::$temp["activateCount"]);
		$this->assertSame(3, \spectrum\tests\Test::$temp["eventDispatchCount"]);
		$this->assertSame(\spectrum\tests\Test::$temp["pluginInstanceOnEventDispatch"], \spectrum\tests\Test::$temp["pluginInstanceOnActivate"]);
		$this->assertFalse(in_array(\spectrum\tests\Test::$temp["pluginInstanceOnEventDispatch"], $instances, true));
		$instances[] = \spectrum\tests\Test::$temp["pluginInstanceOnEventDispatch"];
	}
	
	public function testPlugins_Activation_ActivateMomentIsEveryAccess_DoesNotActivatePluginOnSpecInstanceCreation()
	{
		\spectrum\tests\Test::$temp["activateCount"] = 0;
		
		$pluginClassName = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "everyAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec)
				{
					\spectrum\tests\Test::$temp["activateCount"]++;
				}
				
				public function getOwnerSpec(){}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		new Spec();
		$this->assertSame(0, \spectrum\tests\Test::$temp["activateCount"]);
	}
	
/**/
	
	public function testPlugins_EventDispatch_CallsSpecifiedMethodWithPassedArguments()
	{
		\spectrum\tests\Test::$temp["calledMethods"] = array();
		
		$pluginClassName1 = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getAccessName(){ return "abc"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				public function dispatchPluginEvent($eventName, array $arguments = array())
				{
					return parent::dispatchPluginEvent($eventName, $arguments);
				}
			}
		');
		
		$pluginClassName2 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return null; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners()
				{
					return array(
						array("event" => "testEvent1", "method" => "methodForTestEvent1", "order" => 100),
						array("event" => "testEvent2", "method" => "methodForTestEvent2", "order" => 100),
						array("event" => "testEvent3", "method" => "methodForTestEvent3", "order" => 100),
					);
				}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
				
				public function methodForTestEvent1()
				{
					\spectrum\tests\Test::$temp["calledMethods"][] = array(__FUNCTION__, func_get_args());
				}
				
				public function methodForTestEvent2()
				{
					\spectrum\tests\Test::$temp["calledMethods"][] = array(__FUNCTION__, func_get_args());
				}
				
				public function methodForTestEvent3()
				{
					\spectrum\tests\Test::$temp["calledMethods"][] = array(__FUNCTION__, func_get_args());
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName1);
		config::registerSpecPlugin($pluginClassName2);
		$spec = new Spec();
		
		$spec->abc->dispatchPluginEvent('testEvent1', array('aaa1', 'bbb1', 'ccc1'));
		$this->assertSame(array(
			array('methodForTestEvent1', array('aaa1', 'bbb1', 'ccc1')),
		), \spectrum\tests\Test::$temp["calledMethods"]);
		
		$spec->abc->dispatchPluginEvent('testEvent2', array('aaa2', 'bbb2', 'ccc2'));
		$this->assertSame(array(
			array('methodForTestEvent1', array('aaa1', 'bbb1', 'ccc1')),
			array('methodForTestEvent2', array('aaa2', 'bbb2', 'ccc2')),
		), \spectrum\tests\Test::$temp["calledMethods"]);
		
		$spec->abc->dispatchPluginEvent('testEvent3', array('aaa3', 'bbb3', 'ccc3'));
		$this->assertSame(array(
			array('methodForTestEvent1', array('aaa1', 'bbb1', 'ccc1')),
			array('methodForTestEvent2', array('aaa2', 'bbb2', 'ccc2')),
			array('methodForTestEvent3', array('aaa3', 'bbb3', 'ccc3')),
		), \spectrum\tests\Test::$temp["calledMethods"]);
	}
	
	public function testPlugins_EventDispatch_CallsSpecifiedMethodInOrderFromLowerNegativeToHigherPositiveNumber()
	{
		\spectrum\tests\Test::$temp["calledMethods"] = array();
		
		$pluginClassName1 = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getAccessName(){ return "abc"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				public function dispatchPluginEvent($eventName, array $arguments = array())
				{
					return parent::dispatchPluginEvent($eventName, $arguments);
				}
			}
		');
		
		$pluginClassName2 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return null; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners()
				{
					return array(
						array("event" => "testEvent", "method" => "methodForTestEvent", "order" => 10),
					);
				}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
				
				public function methodForTestEvent()
				{
					\spectrum\tests\Test::$temp["calledMethods"][] = "\\\\" . __METHOD__;
				}
			}
		');
		
		$pluginClassName3 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return null; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners()
				{
					return array(
						array("event" => "testEvent", "method" => "methodForTestEvent", "order" => 0),
					);
				}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
				
				public function methodForTestEvent()
				{
					\spectrum\tests\Test::$temp["calledMethods"][] = "\\\\" . __METHOD__;
				}
			}
		');
		
		$pluginClassName4 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return null; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners()
				{
					return array(
						array("event" => "testEvent", "method" => "methodForTestEvent", "order" => -20),
					);
				}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
				
				public function methodForTestEvent()
				{
					\spectrum\tests\Test::$temp["calledMethods"][] = "\\\\" . __METHOD__;
				}
			}
		');
		
		$pluginClassName5 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return null; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners()
				{
					return array(
						array("event" => "testEvent", "method" => "methodForTestEvent", "order" => -10),
					);
				}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
				
				public function methodForTestEvent()
				{
					\spectrum\tests\Test::$temp["calledMethods"][] = "\\\\" . __METHOD__;
				}
			}
		');
		
		config::registerSpecPlugin($pluginClassName1);
		config::registerSpecPlugin($pluginClassName2);
		config::registerSpecPlugin($pluginClassName3);
		config::registerSpecPlugin($pluginClassName4);
		config::registerSpecPlugin($pluginClassName5);
		$spec = new Spec();
		
		$spec->abc->dispatchPluginEvent('testEvent');
		$this->assertSame(array(
			$pluginClassName4 . '::methodForTestEvent',
			$pluginClassName5 . '::methodForTestEvent',
			$pluginClassName3 . '::methodForTestEvent',
			$pluginClassName2 . '::methodForTestEvent',
		), \spectrum\tests\Test::$temp["calledMethods"]);
	}
	
/**/
	
	public function testPlugins_EventDispatch_OnSpecConstruct_IsDispatchedOnSpecInstanceCreation()
	{
		\spectrum\tests\Test::$temp["createdSpecs"] = array();
		$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["createdSpecs"][] = $this->getOwnerSpec();', 'onSpecConstruct');
		$specs = array(new Spec(), new Spec(), new Spec());
		$this->assertSame($specs, \spectrum\tests\Test::$temp["createdSpecs"]);
	}
	
	public function testPlugins_EventDispatch_OnSpecConstruct_DoesNotPassArgumentsToCalleeMethod()
	{
		\spectrum\tests\Test::$temp["passedArguments"] = array();
		$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["passedArguments"][] = func_get_args();', 'onSpecConstruct');
		new Spec();
		new Spec();
		$this->assertSame(array(array(), array()), \spectrum\tests\Test::$temp["passedArguments"]);
	}
	
/**/
	
	public function testEnable_EnablesSpec()
	{
		$spec = new Spec();
		$spec->disable();
		$this->assertSame(false, $spec->isEnabled());
		$spec->enable();
		$this->assertSame(true, $spec->isEnabled());
	}
	
	public function testEnable_CallOnRun_ThrowsExceptionAndDoesNotEnableSpec()
	{
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["specs"][1]->enable();
		');
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		\spectrum\tests\Test::$temp["specs"][1]->disable();
		
		$this->assertThrowsException('\spectrum\core\Exception', 'Call of "\spectrum\core\Spec::enable" method is forbidden on run', function(){
			\spectrum\tests\Test::$temp["specs"][0]->run();
		});
		
		$this->assertSame(false, \spectrum\tests\Test::$temp["specs"][1]->isEnabled());
	}
	
/**/
	
	public function testDisable_DisablesSpec()
	{
		$spec = new Spec();
		$spec->enable();
		$this->assertSame(true, $spec->isEnabled());
		$spec->disable();
		$this->assertSame(false, $spec->isEnabled());
	}
	
	public function testDisable_CallOnRun_ThrowsExceptionAndDoesNotDisableSpec()
	{
		$this->registerPluginWithCodeInEvent('$this->getOwnerSpec()->disable();');
		$spec = new Spec();
		$this->assertThrowsException('\spectrum\core\Exception', 'Call of "\spectrum\core\Spec::disable" method is forbidden on run', function() use($spec){
			$spec->run();
		});
		
		$this->assertSame(true, $spec->isEnabled());
	}
	
/**/
	
	public function testIsEnabled_ReturnsTrueForEnabledSpec()
	{
		$spec = new Spec();
		$spec->enable();
		$this->assertSame(true, $spec->isEnabled());
	}
	
	public function testIsEnabled_ReturnsFalseForDisabledSpec()
	{
		$spec = new Spec();
		$spec->disable();
		$this->assertSame(false, $spec->isEnabled());
	}
	
/**/
	
	public function testSetName_SetsSpecName()
	{
		$spec = new Spec();
		
		$spec->setName('aaa');
		$this->assertSame('aaa', $spec->getName());
		
		$spec->setName('bbb');
		$this->assertSame('bbb', $spec->getName());
	}
	
	public function testSetName_CallOnRun_ThrowsExceptionAndDoesNotChangeName()
	{
		$this->registerPluginWithCodeInEvent('$this->getOwnerSpec()->setName("bbb");');
		$spec = new Spec();
		$spec->setName('aaa');
		
		$this->assertThrowsException('\spectrum\core\Exception', 'Call of "\spectrum\core\Spec::setName" method is forbidden on run', function() use($spec){
			$spec->run();
		});
		
		$this->assertSame('aaa', $spec->getName());
	}
	
/**/
	
	public function testGetName_ReturnsSpecName()
	{
		$spec = new Spec();
		$spec->setName('aaa');
		$this->assertSame('aaa', $spec->getName());
	}
	
	public function testGetName_ReturnsNullByDefault()
	{
		$spec = new Spec();
		$this->assertSame(null, $spec->getName());
	}
	
/**/
	
	public function testIsAnonymous_ReturnsTrueForSpecWithEmptyNameAndWithChildren()
	{
		$spec = new Spec();
		$spec->bindChildSpec(new Spec());
		$this->assertSame(true, $spec->isAnonymous());
		
		$spec->setName(null);
		$this->assertSame(true, $spec->isAnonymous());
		
		$spec->setName('');
		$this->assertSame(true, $spec->isAnonymous());
	}
	
	public function testIsAnonymous_ReturnsFalseForSpecWithNoEmptyNameOrWithoutChildren()
	{
		$spec = new Spec();
		$spec->setName('aaa');
		$this->assertSame(false, $spec->isAnonymous());
		
		$spec = new Spec();
		$this->assertSame(false, $spec->isAnonymous());
		
		$spec = new Spec();
		$spec->setName('aaa');
		$spec->bindChildSpec(new Spec());
		$this->assertSame(false, $spec->isAnonymous());
	}
	
/**/
	
	public function testGetParentSpecs_ReturnsEmptyArrayByDefault()
	{
		$spec = new Spec();
		$this->assertSame(array(), $spec->getParentSpecs());
	}
	
/**/
	
	public function testHasParentSpec_ReturnsTrueForBoundSpec()
	{
		$spec = new Spec();
		$parentSpec = new Spec();
		$spec->bindParentSpec($parentSpec);
		$this->assertSame(true, $spec->hasParentSpec($parentSpec));
	}
	
	public function testHasParentSpec_ReturnsFalseForNotBoundSpec()
	{
		$spec = new Spec();
		$spec->bindParentSpec(new Spec());
		$this->assertSame(false, $spec->hasParentSpec(new Spec()));
	}
	
/**/
	
	public function testBindParentSpec_CreatesConnectionBetweenSpecs()
	{
		$spec = new Spec();
		$parentSpec = new Spec();
		$spec->bindParentSpec($parentSpec);
		$this->assertSame(array($parentSpec), $spec->getParentSpecs());
		$this->assertSame(array($spec), $parentSpec->getChildSpecs());
	}
	
	public function testBindParentSpec_DoesNotCreateConnectionBetweenAlreadyConnectedSpecs()
	{
		$spec = new Spec();
		$parentSpec = new Spec();
		
		$spec->bindParentSpec($parentSpec);
		$this->assertSame(array($parentSpec), $spec->getParentSpecs());
		$this->assertSame(array($spec), $parentSpec->getChildSpecs());
		
		$spec->bindParentSpec($parentSpec);
		$this->assertSame(array($parentSpec), $spec->getParentSpecs());
		$this->assertSame(array($spec), $parentSpec->getChildSpecs());
	}
	
	public function testBindParentSpec_CallOnRun_ThrowsExceptionAndDoesNotBindSpec()
	{
		$this->registerPluginWithCodeInEvent('$this->getOwnerSpec()->bindParentSpec(new \spectrum\core\Spec());');
		$spec = new Spec();
		
		$this->assertThrowsException('\spectrum\core\Exception', 'Call of "\spectrum\core\Spec::bindParentSpec" method is forbidden on run', function() use($spec){
			$spec->run();
		});
		
		$this->assertSame(array(), $spec->getParentSpecs());
	}
	
/**/
	
	public function testUnbindParentSpec_BreaksConnectionBetweenSpecs()
	{
		$spec = new Spec();
		$parentSpec = new Spec();
		
		$spec->bindParentSpec($parentSpec);
		$this->assertSame(array($parentSpec), $spec->getParentSpecs());
		$this->assertSame(array($spec), $parentSpec->getChildSpecs());
		
		$spec->unbindParentSpec($parentSpec);
		$this->assertSame(array(), $spec->getParentSpecs());
		$this->assertSame(array(), $parentSpec->getChildSpecs());
	}
	
	public function testUnbindParentSpec_DoesNotBreaksOtherConnections()
	{
		$spec = new Spec();
		$parentSpec1 = new Spec();
		$parentSpec2 = new Spec();
		
		$spec->bindParentSpec($parentSpec1);
		$spec->bindParentSpec($parentSpec2);
		$this->assertSame(array($parentSpec1, $parentSpec2), $spec->getParentSpecs());
		$this->assertSame(array($spec), $parentSpec1->getChildSpecs());
		$this->assertSame(array($spec), $parentSpec2->getChildSpecs());
		
		$spec->unbindParentSpec($parentSpec1);
		$this->assertSame(array($parentSpec2), $spec->getParentSpecs());
		$this->assertSame(array(), $parentSpec1->getChildSpecs());
		$this->assertSame(array($spec), $parentSpec2->getChildSpecs());
	}
	
	public function testUnbindParentSpec_ResetsArrayIndexes()
	{
		$spec = new Spec();
		$parentSpec1 = new Spec();
		$parentSpec2 = new Spec();
		$parentSpec3 = new Spec();
		
		$spec->bindParentSpec($parentSpec1);
		$spec->bindParentSpec($parentSpec2);
		$spec->bindParentSpec($parentSpec3);
		
		$spec->unbindParentSpec($parentSpec2);
		$this->assertSame(array($parentSpec1, $parentSpec3), $spec->getParentSpecs());
	}
	
	public function testUnbindParentSpec_ResetsArrayIndexesInUnboundSpec()
	{
		$spec = new Spec();
		$childSpec1 = new Spec();
		$childSpec2 = new Spec();
		$childSpec3 = new Spec();
		
		$spec->bindChildSpec($childSpec1);
		$spec->bindChildSpec($childSpec2);
		$spec->bindChildSpec($childSpec3);
		
		$childSpec2->unbindParentSpec($spec);
		$this->assertSame(array($childSpec1, $childSpec3), $spec->getChildSpecs());
	}
	
	public function testUnbindParentSpec_NoConnectionBetweenSpecs_DoesNotTriggersError()
	{
		$spec = new Spec();
		$parentSpec = new Spec();
		
		$spec->unbindParentSpec($parentSpec);
		$this->assertSame(array(), $spec->getParentSpecs());
		$this->assertSame(array(), $parentSpec->getChildSpecs());
	}
	
	public function testUnbindParentSpec_CallOnRun_ThrowsExceptionAndDoesNotUnbindSpec()
	{
		\spectrum\tests\Test::$temp["newSpec"] = new Spec();
		$this->registerPluginWithCodeInEvent('$this->getOwnerSpec()->unbindParentSpec(\spectrum\tests\Test::$temp["newSpec"]);');
		$spec = new Spec();
		$spec->bindParentSpec(\spectrum\tests\Test::$temp["newSpec"]);
		
		$this->assertThrowsException('\spectrum\core\Exception', 'Call of "\spectrum\core\Spec::unbindParentSpec" method is forbidden on run', function() use($spec){
			$spec->run();
		});
		
		$this->assertSame(array(\spectrum\tests\Test::$temp["newSpec"]), $spec->getParentSpecs());
	}
	
/**/
	
	public function testUnbindAllParentSpecs_BreaksConnectionsWithAllParentSpecs()
	{
		$spec = new Spec();
		$parentSpec1 = new Spec();
		$parentSpec2 = new Spec();
		
		$spec->bindParentSpec($parentSpec1);
		$spec->bindParentSpec($parentSpec2);
		$this->assertSame(array($parentSpec1, $parentSpec2), $spec->getParentSpecs());
		$this->assertSame(array($spec), $parentSpec1->getChildSpecs());
		$this->assertSame(array($spec), $parentSpec2->getChildSpecs());
		
		$spec->unbindAllParentSpecs();
		$this->assertSame(array(), $spec->getParentSpecs());
		$this->assertSame(array(), $parentSpec1->getChildSpecs());
		$this->assertSame(array(), $parentSpec2->getChildSpecs());
	}
	
	public function testUnbindAllParentSpecs_ResetsArrayIndexes()
	{
		$spec = new Spec();
		$parentSpec1 = new Spec();
		$parentSpec2 = new Spec();
		
		$spec->bindParentSpec($parentSpec1);
		$spec->bindParentSpec($parentSpec2);
		$this->assertSame(array($parentSpec1, $parentSpec2), $spec->getParentSpecs());
		$this->assertSame(array($spec), $parentSpec1->getChildSpecs());
		$this->assertSame(array($spec), $parentSpec2->getChildSpecs());
		
		$spec->unbindAllParentSpecs();
		$parentSpec3 = new Spec();
		$spec->bindParentSpec($parentSpec3);
		$this->assertSame(array($parentSpec3), $spec->getParentSpecs());
	}
	
	public function testUnbindAllParentSpecs_ResetsArrayIndexesInUnboundSpec()
	{
		$spec = new Spec();
		$childSpec1 = new Spec();
		$childSpec2 = new Spec();
		$childSpec3 = new Spec();
		
		$spec->bindChildSpec($childSpec1);
		$spec->bindChildSpec($childSpec2);
		$spec->bindChildSpec($childSpec3);
		
		$childSpec2->unbindAllParentSpecs();
		$this->assertSame(array($childSpec1, $childSpec3), $spec->getChildSpecs());
	}
	
	public function testUnbindAllParentSpecs_CallOnRun_ThrowsExceptionAndDoesNotUnbindSpecs()
	{
		$this->registerPluginWithCodeInEvent('$this->getOwnerSpec()->unbindAllParentSpecs();');
		$newSpec = new Spec();
		$spec = new Spec();
		$spec->bindParentSpec($newSpec);
		
		$this->assertThrowsException('\spectrum\core\Exception', 'Call of "\spectrum\core\Spec::unbindAllParentSpecs" method is forbidden on run', function() use($spec){
			$spec->run();
		});
		
		$this->assertSame(array($newSpec), $spec->getParentSpecs());
	}
	
/**/
	
	public function testGetChildSpecs_ReturnsEmptyArrayByDefault()
	{
		$spec = new Spec();
		$this->assertSame(array(), $spec->getChildSpecs());
	}
	
/**/
	
	public function testGetChildSpecsByName_ReturnsAllSpecsWithProperName()
	{
		$spec = new Spec();
		
		$childSpec1 = new Spec();
		$childSpec1->setName('aaa');
		$spec->bindChildSpec($childSpec1);
		
		$childSpec2 = new Spec();
		$childSpec2->setName('bbb');
		$spec->bindChildSpec($childSpec2);
		
		$childSpec3 = new Spec();
		$childSpec3->setName('aaa');
		$spec->bindChildSpec($childSpec3);
		
		$this->assertSame(array($childSpec1, $childSpec3), $spec->getChildSpecsByName('aaa'));
		$this->assertSame(array($childSpec2), $spec->getChildSpecsByName('bbb'));
	}
	
	public function testGetChildSpecsByName_NoProperChildren_ReturnsEmptyArray()
	{
		$spec = new Spec();
		$this->assertSame(array(), $spec->getChildSpecsByName('aaa'));
	}
	
/**/
	
	public function testGetChildSpecByNumber_ReturnsSpecWithProperNumber()
	{
		$spec = new Spec();
		
		$childSpec1 = new Spec();
		$spec->bindChildSpec($childSpec1);
		
		$childSpec2 = new Spec();
		$spec->bindChildSpec($childSpec2);
		
		$childSpec3 = new Spec();
		$spec->bindChildSpec($childSpec3);
		
		$this->assertSame($childSpec1, $spec->getChildSpecByNumber(1));
		$this->assertSame($childSpec2, $spec->getChildSpecByNumber(2));
		$this->assertSame($childSpec3, $spec->getChildSpecByNumber(3));
	}
	
	public function testGetChildSpecByNumber_NoChildWithProperNumber_ReturnsNull()
	{
		$spec = new Spec();
		$this->assertSame(null, $spec->getChildSpecByNumber(1));
	}
	
/**/
	
	public function testHasChildSpec_ReturnsTrueForBoundSpec()
	{
		$spec = new Spec();
		$childSpec = new Spec();
		$spec->bindChildSpec($childSpec);
		$this->assertSame(true, $spec->hasChildSpec($childSpec));
	}
	
	public function testHasChildSpec_ReturnsFalseForNotBoundSpec()
	{
		$spec = new Spec();
		$spec->bindChildSpec(new Spec());
		$this->assertSame(false, $spec->hasChildSpec(new Spec()));
	}
	
/**/
	
	public function testBindChildSpec_CreatesConnectionBetweenSpecs()
	{
		$spec = new Spec();
		$childSpec = new Spec();
		$spec->bindChildSpec($childSpec);
		$this->assertSame(array($childSpec), $spec->getChildSpecs());
		$this->assertSame(array($spec), $childSpec->getParentSpecs());
	}
	
	public function testBindChildSpec_DoesNotCreateConnectionBetweenAlreadyConnectedSpecs()
	{
		$spec = new Spec();
		$childSpec = new Spec();
		
		$spec->bindChildSpec($childSpec);
		$this->assertSame(array($childSpec), $spec->getChildSpecs());
		$this->assertSame(array($spec), $childSpec->getParentSpecs());
		
		$spec->bindChildSpec($childSpec);
		$this->assertSame(array($childSpec), $spec->getChildSpecs());
		$this->assertSame(array($spec), $childSpec->getParentSpecs());
	}
	
	public function testBindChildSpec_CallOnRun_ThrowsExceptionAndDoesNotBindSpec()
	{
		$this->registerPluginWithCodeInEvent('$this->getOwnerSpec()->bindChildSpec(new \spectrum\core\Spec());');
		$spec = new Spec();
		$this->assertThrowsException('\spectrum\core\Exception', 'Call of "\spectrum\core\Spec::bindChildSpec" method is forbidden on run', function() use($spec){
			$spec->run();
		});
		
		$this->assertSame(array(), $spec->getChildSpecs());
	}
	
/**/
	
	public function testUnbindChildSpec_BreaksConnectionBetweenSpecs()
	{
		$spec = new Spec();
		$childSpec = new Spec();
		
		$spec->bindChildSpec($childSpec);
		$this->assertSame(array($childSpec), $spec->getChildSpecs());
		$this->assertSame(array($spec), $childSpec->getParentSpecs());
		
		$spec->unbindChildSpec($childSpec);
		$this->assertSame(array(), $spec->getChildSpecs());
		$this->assertSame(array(), $childSpec->getParentSpecs());
	}
	
	public function testUnbindChildSpec_DoesNotBreaksOtherConnections()
	{
		$spec = new Spec();
		$childSpec1 = new Spec();
		$childSpec2 = new Spec();
		
		$spec->bindChildSpec($childSpec1);
		$spec->bindChildSpec($childSpec2);
		$this->assertSame(array($childSpec1, $childSpec2), $spec->getChildSpecs());
		$this->assertSame(array($spec), $childSpec1->getParentSpecs());
		$this->assertSame(array($spec), $childSpec2->getParentSpecs());
		
		$spec->unbindChildSpec($childSpec1);
		$this->assertSame(array($childSpec2), $spec->getChildSpecs());
		$this->assertSame(array(), $childSpec1->getParentSpecs());
		$this->assertSame(array($spec), $childSpec2->getParentSpecs());
	}
	
	public function testUnbindChildSpec_ResetsArrayIndexes()
	{
		$spec = new Spec();
		$childSpec1 = new Spec();
		$childSpec2 = new Spec();
		$childSpec3 = new Spec();
		
		$spec->bindChildSpec($childSpec1);
		$spec->bindChildSpec($childSpec2);
		$spec->bindChildSpec($childSpec3);
		
		$spec->unbindChildSpec($childSpec2);
		$this->assertSame(array($childSpec1, $childSpec3), $spec->getChildSpecs());
	}
	
	public function testUnbindChildSpec_ResetsArrayIndexesInUnboundSpec()
	{
		$spec = new Spec();
		$parentSpec1 = new Spec();
		$parentSpec2 = new Spec();
		$parentSpec3 = new Spec();
		
		$spec->bindParentSpec($parentSpec1);
		$spec->bindParentSpec($parentSpec2);
		$spec->bindParentSpec($parentSpec3);
		
		$parentSpec2->unbindChildSpec($spec);
		$this->assertSame(array($parentSpec1, $parentSpec3), $spec->getParentSpecs());
	}
	
	public function testUnbindChildSpec_NoConnectionBetweenSpecs_DoesNotTriggersError()
	{
		$spec = new Spec();
		$childSpec = new Spec();
		
		$spec->unbindChildSpec($childSpec);
		$this->assertSame(array(), $spec->getChildSpecs());
		$this->assertSame(array(), $childSpec->getParentSpecs());
	}

	public function testUnbindChildSpec_CallOnRun_ThrowsExceptionAndDoesNotUnbindSpec()
	{
		\spectrum\tests\Test::$temp["newSpec"] = new Spec();
		$this->registerPluginWithCodeInEvent('$this->getOwnerSpec()->unbindChildSpec(\spectrum\tests\Test::$temp["newSpec"]);');
		$spec = new Spec();
		$spec->bindChildSpec(\spectrum\tests\Test::$temp["newSpec"]);
		
		$this->assertThrowsException('\spectrum\core\Exception', 'Call of "\spectrum\core\Spec::unbindChildSpec" method is forbidden on run', function() use($spec){
			$spec->run();
		});
		
		$this->assertSame(array(\spectrum\tests\Test::$temp["newSpec"]), $spec->getChildSpecs());
	}
	
/**/
	
	public function testUnbindAllChildSpecs_BreaksConnectionsWithAllChildSpecs()
	{
		$spec = new Spec();
		$childSpec1 = new Spec();
		$childSpec2 = new Spec();
		
		$spec->bindChildSpec($childSpec1);
		$spec->bindChildSpec($childSpec2);
		$this->assertSame(array($childSpec1, $childSpec2), $spec->getChildSpecs());
		$this->assertSame(array($spec), $childSpec1->getParentSpecs());
		$this->assertSame(array($spec), $childSpec2->getParentSpecs());
		
		$spec->unbindAllChildSpecs();
		$this->assertSame(array(), $spec->getChildSpecs());
		$this->assertSame(array(), $childSpec1->getParentSpecs());
		$this->assertSame(array(), $childSpec2->getParentSpecs());
	}
	
	public function testUnbindAllChildSpecs_ResetsArrayIndexes()
	{
		$spec = new Spec();
		$childSpec1 = new Spec();
		$childSpec2 = new Spec();
		
		$spec->bindChildSpec($childSpec1);
		$spec->bindChildSpec($childSpec2);
		$this->assertSame(array($childSpec1, $childSpec2), $spec->getChildSpecs());
		$this->assertSame(array($spec), $childSpec1->getParentSpecs());
		$this->assertSame(array($spec), $childSpec2->getParentSpecs());
		
		$spec->unbindAllChildSpecs();
		$childSpec3 = new Spec();
		$spec->bindChildSpec($childSpec3);
		$this->assertSame(array($childSpec3), $spec->getChildSpecs());
	}
	
	public function testUnbindAllChildSpecs_ResetsArrayIndexesInUnboundSpec()
	{
		$spec = new Spec();
		$parentSpec1 = new Spec();
		$parentSpec2 = new Spec();
		$parentSpec3 = new Spec();
		
		$spec->bindParentSpec($parentSpec1);
		$spec->bindParentSpec($parentSpec2);
		$spec->bindParentSpec($parentSpec3);
		
		$parentSpec2->unbindAllChildSpecs();
		$this->assertSame(array($parentSpec1, $parentSpec3), $spec->getParentSpecs());
	}

	public function testUnbindAllChildSpecs_CallOnRun_ThrowsExceptionAndDoesNotUnbindSpecs()
	{
		$this->registerPluginWithCodeInEvent('$this->getOwnerSpec()->unbindAllChildSpecs();');
		$newSpec = new Spec();
		$spec = new Spec();
		$spec->bindChildSpec($newSpec);
		
		$this->assertThrowsException('\spectrum\core\Exception', 'Call of "\spectrum\core\Spec::unbindAllChildSpecs" method is forbidden on run', function() use($spec){
			$spec->run();
		});
		
		$this->assertSame(array($newSpec), $spec->getChildSpecs());
	}

/**/

	public function testGetRootSpec_ReturnsRootSpec()
	{
		$specs = $this->createSpecsTree('
			->Spec
			Spec(aaa)
		');
		$this->assertSame($specs[0], $specs['aaa']->getRootSpec());
		
		$specs = $this->createSpecsTree('
			->->Spec
			->Spec
			Spec(aaa)
		');
		$this->assertSame($specs[0], $specs['aaa']->getRootSpec());
		
		$specs = $this->createSpecsTree('
			->->->Spec
			->->Spec
			->Spec
			Spec(aaa)
		');
		$this->assertSame($specs[0], $specs['aaa']->getRootSpec());
	}
	
	public function testGetRootSpec_SpecHasNoParents_ReturnsSelfSpec()
	{
		$spec = new Spec();
		$this->assertSame($spec, $spec->getRootSpec());
		
		$spec = new Spec();
		$spec->bindChildSpec(new Spec());
		$this->assertSame($spec, $spec->getRootSpec());
	}
	
/**/
	
	public function testGetRootSpecs_ReturnsAllRootSpecs()
	{
		$specs = $this->createSpecsTree('
			->Spec
			->Spec
			Spec(aaa)
		');
		$this->assertSame(array($specs[0], $specs[1]), $specs['aaa']->getRootSpecs());
		
		$specs = $this->createSpecsTree('
			->->Spec
			->Spec
			->->Spec
			->Spec
			Spec(aaa)
		');
		$this->assertSame(array($specs[0], $specs[2]), $specs['aaa']->getRootSpecs());
	}
	
	public function testGetRootSpecs_SpecHasNoParents_ReturnsEmptyArray()
	{
		$spec = new Spec();
		$this->assertSame(array(), $spec->getRootSpecs());
		
		$spec = new Spec();
		$spec->bindChildSpec(new Spec());
		$this->assertSame(array(), $spec->getRootSpecs());
	}
	
/**/
	
	public function testGetEndingSpecs_ReturnsAllEndingSpecs()
	{
		$specs = $this->createSpecsTree('
			Spec
			->Spec(endingSpec1)
			->Spec
			->->Spec(endingSpec2)
			->Spec
			->->Spec
			->->->Spec(endingSpec3)
			->->->Spec(endingSpec4)
		');
		$this->assertSame(array($specs['endingSpec1'], $specs['endingSpec2'], $specs['endingSpec3'], $specs['endingSpec4']), $specs[0]->getEndingSpecs());
	}
	
	public function testGetEndingSpecs_SpecHasNoChildren_ReturnsEmptyArray()
	{
		$spec = new Spec();
		$this->assertSame(array(), $spec->getEndingSpecs());
		
		$spec = new Spec();
		$spec->bindParentSpec(new Spec());
		$this->assertSame(array(), $spec->getEndingSpecs());
	}
	
/**/
	
	public function testGetRunningParentSpec_ReturnsRunningParentSpec()
	{
		\spectrum\tests\Test::$temp["specs"] = array();
		
		$this->registerPluginWithCodeInEvent('
			if (\spectrum\tests\Test::$temp["checkpoint"] === $this->getOwnerSpec())
				\spectrum\tests\Test::$temp["specs"][] = $this->getOwnerSpec()->getRunningParentSpec();
		');
		
		$specs = $this->createSpecsTree('
			->Spec
			->Spec
			Spec
		');
		
		$rootSpec = new Spec();
		$rootSpec->bindChildSpec($specs[0]);
		$rootSpec->bindChildSpec($specs[1]);
		
		\spectrum\tests\Test::$temp["checkpoint"] = $specs[2];
		$rootSpec->run();
		
		$this->assertSame(array($specs[0], $specs[1]), \spectrum\tests\Test::$temp["specs"]);
	}
	
	public function testGetRunningParentSpec_NoRunningParentSpec_ReturnsNull()
	{
		$specs = $this->createSpecsTree('
			->Spec
			->Spec
			Spec(aaa)
		');
		$this->assertSame(null, $specs['aaa']->getRunningParentSpec());
	}
	
/**/

	public function testGetRunningAncestorSpecs_ReturnsRunningAncestorSpecs()
	{
		\spectrum\tests\Test::$temp["specs"] = array();
		
		$this->registerPluginWithCodeInEvent('
			if (\spectrum\tests\Test::$temp["checkpoint"] === $this->getOwnerSpec())
				\spectrum\tests\Test::$temp["specs"][] = $this->getOwnerSpec()->getRunningAncestorSpecs();
		');
		
		$specs = $this->createSpecsTree('
			->Spec
			->Spec
			Spec
		');
		
		$rootSpec = new Spec();
		$rootSpec->bindChildSpec($specs[0]);
		$rootSpec->bindChildSpec($specs[1]);
		
		\spectrum\tests\Test::$temp["checkpoint"] = $specs[2];
		$rootSpec->run();
		
		$this->assertSame(array(
			array($specs[0], $rootSpec),
			array($specs[1], $rootSpec),
		), \spectrum\tests\Test::$temp["specs"]);
	}
	
	public function testGetRunningAncestorSpecs_NoRunningParentSpec_ReturnsEmptyArray()
	{
		$specs = $this->createSpecsTree('
			->Spec
			->Spec
			Spec(aaa)
		');
		$this->assertSame(array(), $specs['aaa']->getRunningAncestorSpecs());
	}

/**/
	
	public function testGetRunningEndingSpec_ReturnsRunningEndingSpec()
	{
		\spectrum\tests\Test::$temp["runningEndingSpecs"] = array();
		$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["runningEndingSpecs"][] = \spectrum\tests\Test::$temp["specs"][0]->getRunningEndingSpec();');
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsTree('
			Spec
			->Spec
			->->Spec
		');
		
		\spectrum\tests\Test::$temp["specs"][0]->run();
		
		$this->assertSame(array(
			null,
			null,
			\spectrum\tests\Test::$temp["specs"][2],
		), \spectrum\tests\Test::$temp["runningEndingSpecs"]);
	}
	
	public function testGetRunningEndingSpec_NoRunningChildren_ReturnsNull()
	{
		$spec = new Spec();
		$this->assertSame(null, $spec->getRunningEndingSpec());
	}
	
	public function testGetRunningEndingSpec_NoRunningChildrenAndSelfIsRunning_ReturnsNull()
	{
		\spectrum\tests\Test::$temp["runningEndingSpecs"] = array();
		
		$this->registerPluginWithCodeInEvent('
			if (\spectrum\tests\Test::$temp["checkpoint"] === $this->getOwnerSpec())
				\spectrum\tests\Test::$temp["runningEndingSpecs"][] = $this->getOwnerSpec()->getRunningEndingSpec();
		');
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		\spectrum\tests\Test::$temp["checkpoint"] = $specs[0];
		$specs[0]->run();
		
		$this->assertSame(array(null), \spectrum\tests\Test::$temp["runningEndingSpecs"]);
	}	
	
/**/
	
	public function testGetRunningChildSpec_ReturnsRunningChildSpec()
	{
		\spectrum\tests\Test::$temp["returnSpecs"] = array();
		
		$this->registerPluginWithCodeInEvent('
			if ($this->getOwnerSpec() === \spectrum\tests\Test::$temp["specs"]["checkpoint"])
			{
				\spectrum\tests\Test::$temp["returnSpecs"][] = \spectrum\tests\Test::$temp["specs"][0]->getRunningChildSpec();
				\spectrum\tests\Test::$temp["returnSpecs"][] = \spectrum\tests\Test::$temp["specs"][1]->getRunningChildSpec();
				\spectrum\tests\Test::$temp["returnSpecs"][] = \spectrum\tests\Test::$temp["specs"]["checkpoint"]->getRunningChildSpec();
			}
		', 'onEndingSpecExecute');
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsTree('
			Spec
			->Spec
			->->Spec(checkpoint)
		');
		
		\spectrum\tests\Test::$temp["specs"][0]->run();
		
		$this->assertSame(array(
			\spectrum\tests\Test::$temp["specs"][1],
			\spectrum\tests\Test::$temp["specs"]["checkpoint"],
			null,
		), \spectrum\tests\Test::$temp["returnSpecs"]);
	}	
	
	public function testGetRunningChildSpec_NoRunningChildren_ReturnsNull()
	{
		$spec = new Spec();
		$this->assertSame(null, $spec->getRunningChildSpec());
	}
	
/**/
	
	public function testGetResultBuffer_ReturnsNullByDefault()
	{
		$spec = new Spec();
		$this->assertSame(null, $spec->getResultBuffer());
	}
	
/**/
	
	public function testIsRunning_ReturnsFalseByDefault()
	{
		$spec = new Spec();
		$this->assertSame(false, $spec->isRunning());
	}
	
/**/
	
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
	public function testRun_SpecHasMoreThanOneRootAncestors_ThrowsException($specTreePattern)
	{
		$specs = $this->createSpecsTree($specTreePattern);
		$specs['spec']->setName('aaa');
		
		$this->assertThrowsException('\spectrum\core\Exception', 'Spec "aaa" has more than one root ancestors, but for run needs only one general root', function() use($specs){
			$specs['spec']->run();
		});
	}
	
	public function testRun_SpecHasMoreThanOneRootAncestors_StopsRunByExceptionThrowing()
	{
		\spectrum\tests\Test::$temp["calledSpecs"] = array();
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["calledSpecs"][] = $this->getOwnerSpec();
			
			if (\spectrum\tests\Test::$temp["specs"]["caller"] === $this->getOwnerSpec())
				\spectrum\tests\Test::$temp["specs"]["callee"]->run();
		');
		
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
	
	public function testRun_SpecIsAlreadyRunning_ThrowsException()
	{
		\spectrum\tests\Test::$temp["exception"] = null;
		
		$this->registerPluginWithCodeInEvent('
			try
			{
				$this->getOwnerSpec()->run();
			}
			catch (\Exception $e)
			{
				\spectrum\tests\Test::$temp["exception"] = $e;
			}
		');
		
		$spec = new Spec();
		$spec->setName('aaa');
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\core\Exception', \spectrum\tests\Test::$temp["exception"]);
		$this->assertSame('Spec "aaa" is already running', \spectrum\tests\Test::$temp["exception"]->getMessage());
	}
	
	public function testRun_SpecIsAlreadyRunning_StopsRunByExceptionThrowing()
	{
		\spectrum\tests\Test::$temp["calledSpecs"] = array();
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["calledSpecs"][] = $this->getOwnerSpec();
			
			if (\spectrum\tests\Test::$temp["specs"]["spec"] === $this->getOwnerSpec())
				\spectrum\tests\Test::$temp["specs"]["spec"]->run();
		');
		
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
	
	public function testRun_SpecHasAlreadyRunningSibling_ThrowsException()
	{
		\spectrum\tests\Test::$temp["exception"] = null;
		
		$this->registerPluginWithCodeInEvent('
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
		');
		
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
	
	public function testRun_SpecHasAlreadyRunningSibling_StopsRunByExceptionThrowing()
	{
		\spectrum\tests\Test::$temp["calledSpecs"] = array();
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["calledSpecs"][] = $this->getOwnerSpec();
			
			if (\spectrum\tests\Test::$temp["specs"]["caller"] === $this->getOwnerSpec())
				\spectrum\tests\Test::$temp["specs"]["callee"]->run();
		');
		
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
	public function testRun_ChildSpecRunWithoutRunningParent_DisablesSiblingSpecsUpToRootAndRunRootSpec($specTreePattern, $specStates, $calledSpecs, $specBindings = array())
	{
		\spectrum\tests\Test::$temp["specStates"] = array();
		\spectrum\tests\Test::$temp["calledSpecs"] = array();
		
		$this->registerPluginWithCodeInEvent('
			$ownerSpec = $this->getOwnerSpec();
			
			if ($ownerSpec === \spectrum\tests\Test::$temp["specs"]["checkpoint"])
			{
				foreach (\spectrum\tests\Test::$temp["specs"] as $spec)
					\spectrum\tests\Test::$temp["specStates"][] = $spec->isEnabled();
			}
			
			\spectrum\tests\Test::$temp["calledSpecs"][] = array_search($ownerSpec, \spectrum\tests\Test::$temp["specs"], true);
		');
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsTree($specTreePattern, $specBindings);
		\spectrum\tests\Test::$temp["specs"]["callee"]->run();
		
		$this->assertSame($specStates, \spectrum\tests\Test::$temp["specStates"]);
		$this->assertSame($calledSpecs, \spectrum\tests\Test::$temp["calledSpecs"]);
	}
	
	/**
	 * @dataProvider providerChildSpecRunWithoutRunningParent
	 */
	public function testRun_ChildSpecRunWithoutRunningParent_EnablesDisabledSpecsAfterRun($specTreePattern, $specStates, $calledSpecs, $specBindings = array())
	{
		$specs = $this->createSpecsTree($specTreePattern, $specBindings);
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
	public function testRun_ChildSpecRunWithoutRunningParent_DoesNotEnableUserDisabledSpecsAfterRun($specTreePattern)
	{
		$specs = $this->createSpecsTree($specTreePattern);
		$specs["disabled"]->disable();
		$specs["callee"]->run();
		
		$this->assertSame(false, $specs["disabled"]->isEnabled());
	}
	
	public function testRun_ChildSpecRunWithoutRunningParent_ReturnsRootSpecRunResult()
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
	
	public function testRun_ChildSpecRunWithoutRunningParent_RootIsAlreadyRunning_ThrowsException()
	{
		\spectrum\tests\Test::$temp["exception"] = null;
		
		$this->registerPluginWithCodeInEvent('
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
		');
		
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
	
	public function testRun_ChildSpecRunWithoutRunningParent_RootIsAlreadyRunning_StopsRunByExceptionThrowing()
	{
		\spectrum\tests\Test::$temp["calledSpecs"] = array();
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["calledSpecs"][] = $this->getOwnerSpec();
			
			if (\spectrum\tests\Test::$temp["specs"]["caller"] === $this->getOwnerSpec())
				\spectrum\tests\Test::$temp["specs"]["callee"]->run();
		');
		
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
	
	public function testRun_RootSpecRun_EnablesRunningFlagDuringRun()
	{
		\spectrum\tests\Test::$temp["isRunningCallResults"] = array();
		
		$this->registerPluginWithCodeInEvent('
			if ($this->getOwnerSpec() === \spectrum\tests\Test::$temp["specs"][2])
			{
				\spectrum\tests\Test::$temp["isRunningCallResults"][] = \spectrum\tests\Test::$temp["specs"][0]->isRunning();
				\spectrum\tests\Test::$temp["isRunningCallResults"][] = \spectrum\tests\Test::$temp["specs"][1]->isRunning();
				\spectrum\tests\Test::$temp["isRunningCallResults"][] = \spectrum\tests\Test::$temp["specs"][2]->isRunning();
			}
		', 'onEndingSpecExecute');
		
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
	
	public function testRun_RootSpecRun_DisablesRunningFlagAfterEachChildSpecRun()
	{
		\spectrum\tests\Test::$temp["isRunningCallResults"] = array();
		
		$this->registerPluginWithCodeInEvent('
			if ($this->getOwnerSpec() === \spectrum\tests\Test::$temp["specs"][4])
			{
				\spectrum\tests\Test::$temp["isRunningCallResults"][] = \spectrum\tests\Test::$temp["specs"][0]->isRunning();
				\spectrum\tests\Test::$temp["isRunningCallResults"][] = \spectrum\tests\Test::$temp["specs"][1]->isRunning();
				\spectrum\tests\Test::$temp["isRunningCallResults"][] = \spectrum\tests\Test::$temp["specs"][2]->isRunning();
				\spectrum\tests\Test::$temp["isRunningCallResults"][] = \spectrum\tests\Test::$temp["specs"][3]->isRunning();
				\spectrum\tests\Test::$temp["isRunningCallResults"][] = \spectrum\tests\Test::$temp["specs"][4]->isRunning();
			}
		', 'onEndingSpecExecute');
		
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

	public function testRun_RootSpecRun_RunsChildSpecsForNotEndingSpecsSequentially()
	{
		\spectrum\tests\Test::$temp["runSpecs"] = array();
		
		$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["runSpecs"][] = $this->getOwnerSpec();');
		
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
	
	public function testRun_RootSpecRun_RunsEnabledSpecsOnly()
	{
		\spectrum\tests\Test::$temp["runSpecs"] = array();
		
		$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["runSpecs"][] = $this->getOwnerSpec();');
		
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
	
	public function testRun_RootSpecRun_DoesNotRunChildrenOfDisabledSpecs()
	{
		\spectrum\tests\Test::$temp["runSpecs"] = array();
		
		$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["runSpecs"][] = $this->getOwnerSpec();');
		
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
	
	public function testRun_RootSpecRun_ReturnsResultBufferTotalResult()
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
	
	public function testRun_RootSpecRun_ResultBuffer_UsesConfigForResultBufferClassGetting()
	{
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		$resultBufferClassName = $this->createClass('class ... extends \spectrum\core\ResultBuffer {}');
		config::setResultBufferClass($resultBufferClassName);
		$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["resultBuffers"][] = $this->getOwnerSpec()->getResultBuffer();', 'onSpecRunFinish');
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		
		$this->assertSame(2, count(\spectrum\tests\Test::$temp["resultBuffers"]));
		$this->assertInstanceOf($resultBufferClassName, \spectrum\tests\Test::$temp["resultBuffers"][0]);
		$this->assertInstanceOf($resultBufferClassName, \spectrum\tests\Test::$temp["resultBuffers"][1]);
	}
	
	public function testRun_RootSpecRun_ResultBuffer_CreatesNewResultBufferWithProperLinkToOwnerSpecForEachSpec()
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
	
	public function testRun_RootSpecRun_ResultBuffer_CreatesNewResultBufferForEachRun()
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
	
	public function testRun_RootSpecRun_ResultBuffer_UnsetResultBufferLinkAfterRun()
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
	
	public function testRun_RootSpecRun_ResultBuffer_DoesNotClearResultBufferDataAfterRun()
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
	
	public function testRun_RootSpecRun_ResultBuffer_NotEndingSpec_CreatesLockedResultBufferAfterChildSpecsRun()
	{
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$this->registerPluginWithCodeInEvent('
			if ($this->getOwnerSpec() === \spectrum\tests\Test::$temp["specs"][0])
				\spectrum\tests\Test::$temp["resultBuffers"][] = \spectrum\tests\Test::$temp["specs"][0]->getResultBuffer();
		', 'onSpecRunFinish');
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		\spectrum\tests\Test::$temp["specs"][0]->run();
		$this->assertSame(1, count(\spectrum\tests\Test::$temp["resultBuffers"]));
		$this->assertSame(\spectrum\tests\Test::$temp["specs"][0], \spectrum\tests\Test::$temp["resultBuffers"][0]->getOwnerSpec());
		$this->assertSame(true, \spectrum\tests\Test::$temp["resultBuffers"][0]->isLocked());
	}
	
	public function testRun_RootSpecRun_ResultBuffer_NotEndingSpec_DoesNotCreateResultBufferWhileChildSpecsRun()
	{
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$this->registerPluginWithCodeInEvent('
			if ($this->getOwnerSpec() === \spectrum\tests\Test::$temp["specs"][1])
				\spectrum\tests\Test::$temp["resultBuffers"][] = \spectrum\tests\Test::$temp["specs"][0]->getResultBuffer();
		');
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		\spectrum\tests\Test::$temp["specs"][0]->run();
		$this->assertSame(array(null), \spectrum\tests\Test::$temp["resultBuffers"]);
	}
	
	public function testRun_RootSpecRun_ResultBuffer_NotEndingSpec_PutsChildSpecRunResultWithChildSpecObjectToResultBufferForEachChildSpec()
	{
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$this->registerPluginWithCodeInEvent('
			if ($this->getOwnerSpec() === \spectrum\tests\Test::$temp["specs"][0])
				\spectrum\tests\Test::$temp["resultBuffers"][] = \spectrum\tests\Test::$temp["specs"][0]->getResultBuffer();
		', 'onSpecRunFinish');
		
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
	
	public function testRun_RootSpecRun_ResultBuffer_EndingSpec_CreatesEmptyAndNotLockedResultBuffer()
	{
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$this->registerPluginWithCodeInEvent('
			if ($this->getOwnerSpec() === \spectrum\tests\Test::$temp["specs"][1])
				\spectrum\tests\Test::$temp["resultBuffers"][] = \spectrum\tests\Test::$temp["specs"][1]->getResultBuffer();
		', 'onSpecRunFinish');
		
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
	
	public function testRun_RootSpecRun_EventDispatch_OnRootSpecRunBefore_IsDispatchedOnRootSpecRunOnly()
	{
		\spectrum\tests\Test::$temp["runSpecs"] = array();
		
		$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["runSpecs"][] = $this->getOwnerSpec();', 'onRootSpecRunBefore');
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
			->->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array($specs[0]), \spectrum\tests\Test::$temp["runSpecs"]);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnRootSpecRunBefore_DoesNotPassArgumentsToCalleeMethod()
	{
		\spectrum\tests\Test::$temp["passedArguments"] = array();
		
		$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["passedArguments"][] = func_get_args();', 'onRootSpecRunBefore');
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(array()), \spectrum\tests\Test::$temp["passedArguments"]);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnRootSpecRunBefore_IsDispatchedBeforeRunningFlagEnable()
	{
		\spectrum\tests\Test::$temp["isRunningCallResults"] = array();
		
		$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["isRunningCallResults"][] = $this->getOwnerSpec()->isRunning();', 'onRootSpecRunBefore');
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(false), \spectrum\tests\Test::$temp["isRunningCallResults"]);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnRootSpecRunBefore_IsDispatchedBeforeResultBufferCreate()
	{
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["resultBuffers"][] = $this->getOwnerSpec()->getResultBuffer();', 'onRootSpecRunBefore');
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(null), \spectrum\tests\Test::$temp["resultBuffers"]);
	}

/**/
	
	public function testRun_RootSpecRun_EventDispatch_OnRootSpecRunAfter_IsDispatchedOnRootSpecRunOnly()
	{
		\spectrum\tests\Test::$temp["runSpecs"] = array();
		
		$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["runSpecs"][] = $this->getOwnerSpec();', 'onRootSpecRunAfter');
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
			->->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array($specs[0]), \spectrum\tests\Test::$temp["runSpecs"]);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnRootSpecRunAfter_DoesNotPassArgumentsToCalleeMethod()
	{
		\spectrum\tests\Test::$temp["passedArguments"] = array();
		
		$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["passedArguments"][] = func_get_args();', 'onRootSpecRunAfter');
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(array()), \spectrum\tests\Test::$temp["passedArguments"]);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnRootSpecRunAfter_IsDispatchedAfterRunningFlagDisabled()
	{
		\spectrum\tests\Test::$temp["isRunningCallResults"] = array();
		
		$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["isRunningCallResults"][] = $this->getOwnerSpec()->isRunning();', 'onRootSpecRunAfter');
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(false), \spectrum\tests\Test::$temp["isRunningCallResults"]);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnRootSpecRunAfter_IsDispatchedBeforeResultBufferLinkUnset()
	{
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["resultBuffers"][] = $this->getOwnerSpec()->getResultBuffer();', 'onRootSpecRunAfter');
		
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
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunStart_IsDispatchedOnEverySpec()
	{
		\spectrum\tests\Test::$temp["runSpecs"] = array();
		
		$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["runSpecs"][] = $this->getOwnerSpec();');
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
			->->Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array($specs[0], $specs[1], $specs[2], $specs[3]), \spectrum\tests\Test::$temp["runSpecs"]);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunStart_DoesNotPassArgumentsToCalleeMethod()
	{
		\spectrum\tests\Test::$temp["passedArguments"] = array();
		
		$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["passedArguments"][] = func_get_args();');
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(array(), array()), \spectrum\tests\Test::$temp["passedArguments"]);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunStart_IsDispatchedAfterRunningFlagEnable()
	{
		\spectrum\tests\Test::$temp["isRunningCallResults"] = array();
		
		$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["isRunningCallResults"][] = $this->getOwnerSpec()->isRunning();');
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(true, true), \spectrum\tests\Test::$temp["isRunningCallResults"]);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunStart_IsDispatchedBeforeChildSpecRun()
	{
		\spectrum\tests\Test::$temp["runSpecs"] = array();
		
		$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["runSpecs"][] = $this->getOwnerSpec();');
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array($specs[0], $specs[1], $specs[2]), \spectrum\tests\Test::$temp["runSpecs"]);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunStart_IsDispatchedBeforeResultBufferCreate()
	{
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["resultBuffers"][] = $this->getOwnerSpec()->getResultBuffer();');
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(null, null), \spectrum\tests\Test::$temp["resultBuffers"]);
	}
	
/**/
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunFinish_IsDispatchedOnEverySpec()
	{
		\spectrum\tests\Test::$temp["runSpecs"] = array();
		
		$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["runSpecs"][] = $this->getOwnerSpec();', 'onSpecRunFinish');
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
			->->Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array($specs[2], $specs[1], $specs[3], $specs[0]), \spectrum\tests\Test::$temp["runSpecs"]);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunFinish_DoesNotPassArgumentsToCalleeMethod()
	{
		\spectrum\tests\Test::$temp["passedArguments"] = array();
		
		$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["passedArguments"][] = func_get_args();', 'onSpecRunFinish');
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(array(), array()), \spectrum\tests\Test::$temp["passedArguments"]);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunFinish_IsDispatchedBeforeRunningFlagDisable()
	{
		\spectrum\tests\Test::$temp["isRunningCallResults"] = array();
		
		$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["isRunningCallResults"][] = $this->getOwnerSpec()->isRunning();', 'onSpecRunFinish');
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array(true, true), \spectrum\tests\Test::$temp["isRunningCallResults"]);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunFinish_IsDispatchedAfterChildSpecsRun()
	{
		\spectrum\tests\Test::$temp["runSpecs"] = array();
		
		$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["runSpecs"][] = $this->getOwnerSpec();', 'onSpecRunFinish');
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
			->Spec
		');
		
		$specs[0]->run();
		$this->assertSame(array($specs[1], $specs[2], $specs[0]), \spectrum\tests\Test::$temp["runSpecs"]);
	}
	
	public function testRun_RootSpecRun_EventDispatch_OnSpecRunFinish_IsDispatchedBeforeResultBufferLinkUnset()
	{
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["resultBuffers"][] = $this->getOwnerSpec()->getResultBuffer();', 'onSpecRunFinish');
		
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
	public function testRun_RootSpecRun_EventDispatch_EndingSpecExecuteEvents_IsDispatchedOnEndingSpecsOnly($eventName)
	{
		\spectrum\tests\Test::$temp["runSpecs"] = array();
		
		$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["runSpecs"][] = $this->getOwnerSpec();', $eventName);
		
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
	public function testRun_RootSpecRun_EventDispatch_EndingSpecExecuteEvents_DoesNotPassArgumentsToCalleeMethod($eventName)
	{
		\spectrum\tests\Test::$temp["passedArguments"] = array();
		
		$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["passedArguments"][] = func_get_args();', $eventName);
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame(array(array()), \spectrum\tests\Test::$temp["passedArguments"]);
	}
	
	/**
	 * @dataProvider providerEndingSpecExecuteEvents
	 */
	public function testRun_RootSpecRun_EventDispatch_EndingSpecExecuteEvents_IsDispatchedAfterRunningFlagEnable($eventName)
	{
		\spectrum\tests\Test::$temp["isRunningCallResults"] = array();
		
		$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["isRunningCallResults"][] = $this->getOwnerSpec()->isRunning();', $eventName);
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame(array(true), \spectrum\tests\Test::$temp["isRunningCallResults"]);
	}
	
	/**
	 * @dataProvider providerEndingSpecExecuteEvents
	 */
	public function testRun_RootSpecRun_EventDispatch_EndingSpecExecuteEvents_IsDispatchedAfterResultBufferCreate($eventName)
	{
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["resultBuffers"][] = $this->getOwnerSpec()->getResultBuffer();', $eventName);
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame(1, count(\spectrum\tests\Test::$temp["resultBuffers"]));
		$this->assertInstanceOf('\spectrum\core\ResultBufferInterface', \spectrum\tests\Test::$temp["resultBuffers"][0]);
	}
	
	/**
	 * @dataProvider providerEndingSpecExecuteEvents
	 */
	public function testRun_RootSpecRun_EventDispatch_EndingSpecExecuteEvents_CatchesExceptionsAndAddsItToResultBufferAsFail($eventName)
	{
		\spectrum\tests\Test::$temp["thrownExceptions"] = array();
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$this->registerPluginWithCodeInEvent('
			$e = new \Exception("aaa");
			\spectrum\tests\Test::$temp["thrownExceptions"][] = $e;
			\spectrum\tests\Test::$temp["resultBuffers"][] = $this->getOwnerSpec()->getResultBuffer();
			
			throw $e;
		', $eventName);
		
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
	public function testRun_RootSpecRun_EventDispatch_EndingSpecExecuteEvents_CatchesBreakExceptionAndDoesNotAddResultsToResultBuffer($eventName)
	{
		\spectrum\tests\Test::$temp["thrownExceptions"] = array();
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$this->registerPluginWithCodeInEvent('
			$e = new \spectrum\core\BreakException();
			\spectrum\tests\Test::$temp["thrownExceptions"][] = $e;
			\spectrum\tests\Test::$temp["resultBuffers"][] = $this->getOwnerSpec()->getResultBuffer();
			
			throw $e;
		', $eventName);
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame(1, count(\spectrum\tests\Test::$temp["thrownExceptions"]));
		$this->assertSame(array(), \spectrum\tests\Test::$temp["resultBuffers"][0]->getResults());
	}
	
	/**
	 * @dataProvider providerEndingSpecExecuteEvents
	 */
	public function testRun_RootSpecRun_EventDispatch_EndingSpecExecuteEvents_DoesNotBreakOtherEventsByException($testEventName)
	{
		$this->registerPluginWithCodeInEvent('throw new \Exception("aaa");', $testEventName);
		
		\spectrum\tests\Test::$temp["calledEvents"] = array();
		
		$otherEvents = array('onEndingSpecExecuteBefore', 'onEndingSpecExecute', 'onEndingSpecExecuteAfter');
		unset($otherEvents[array_search($testEventName, $otherEvents)]);
		$otherEvents = array_values($otherEvents);
		
		foreach ($otherEvents as $otherEventName)
			$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["calledEvents"][] = "' . $otherEventName . '";', $otherEventName);
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame($otherEvents, \spectrum\tests\Test::$temp["calledEvents"]);
	}
	
	/**
	 * @dataProvider providerEndingSpecExecuteEvents
	 */
	public function testRun_RootSpecRun_EventDispatch_EndingSpecExecuteEvents_DoesNotBreakOtherEventsByBreakException($testEventName)
	{
		$this->registerPluginWithCodeInEvent('throw new \spectrum\core\BreakException();', $testEventName);
		
		\spectrum\tests\Test::$temp["calledEvents"] = array();
		
		$otherEvents = array('onEndingSpecExecuteBefore', 'onEndingSpecExecute', 'onEndingSpecExecuteAfter');
		unset($otherEvents[array_search($testEventName, $otherEvents)]);
		$otherEvents = array_values($otherEvents);
		
		foreach ($otherEvents as $otherEventName)
			$this->registerPluginWithCodeInEvent('\spectrum\tests\Test::$temp["calledEvents"][] = "' . $otherEventName . '";', $otherEventName);
		
		$spec = new Spec();
		$spec->run();
		
		$this->assertSame($otherEvents, \spectrum\tests\Test::$temp["calledEvents"]);
	}
	
/**/
	
	public function testRun_RootSpecRun_EventDispatch_DispatchesRunEventsInTrueOrder()
	{
		\spectrum\tests\Test::$temp["calledEvents"] = array();
		
		$pluginClassName = $this->createClass('
			class ... extends \spectrum\core\plugins\Plugin
			{
				static public function getEventListeners()
				{
					return array(
						array("event" => "onRootSpecRunBefore", "method" => "onRootSpecRunBefore", "order" => 100),
						array("event" => "onSpecRunStart", "method" => "onSpecRunStart", "order" => 100),
						
						array("event" => "onEndingSpecExecuteBefore", "method" => "onEndingSpecExecuteBefore", "order" => 100),
						array("event" => "onEndingSpecExecute", "method" => "onEndingSpecExecute", "order" => 100),
						array("event" => "onEndingSpecExecuteAfter", "method" => "onEndingSpecExecuteAfter", "order" => 100),
						
						array("event" => "onSpecRunFinish", "method" => "onSpecRunFinish", "order" => 100),
						array("event" => "onRootSpecRunAfter", "method" => "onRootSpecRunAfter", "order" => 100),
					);
				}
				
				public function onRootSpecRunBefore(){ \spectrum\tests\Test::$temp["calledEvents"][] = __FUNCTION__; }
				public function onSpecRunStart(){ \spectrum\tests\Test::$temp["calledEvents"][] = __FUNCTION__; }
				
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
			'onSpecRunStart',
			
			'onEndingSpecExecuteBefore',
			'onEndingSpecExecute',
			'onEndingSpecExecuteAfter',
			
			'onSpecRunFinish',
			'onRootSpecRunAfter',
		), \spectrum\tests\Test::$temp["calledEvents"]);
	}
}