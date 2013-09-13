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

class PluginsTest extends \spectrum\tests\Test
{
	public function setUp()
	{
		parent::setUp();
		config::unregisterSpecPlugins();
	}
	
	public function testThrowsExceptionOnAccessToNotExistingPlugin()
	{
		$spec = new Spec();
		$this->assertThrowsException('\spectrum\core\Exception', 'Undefined plugin with access name "asdfgscvsadf" in "spectrum\core\Spec" class', function() use($spec){
			$spec->asdfgscvsadf;
		});
	}
	
	public function testThrowsExceptionOnAccessToPluginWithEmptyAccessName()
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
	
	public function testActivation_ActivateMomentIsFirstAccess_ActivatesPluginOnAccessAndReturnsProperPluginInstance()
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
	
	public function testActivation_ActivateMomentIsFirstAccess_ActivatesAllPluginsWithCorrectClasses()
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
	
	public function testActivation_ActivateMomentIsFirstAccess_DoesNotActivatePluginOnSpecInstanceCreation()
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
	
	public function testActivation_ActivateMomentIsFirstAccess_DoesNotReactivatePluginOnPluginAccess()
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
		
	public function testActivation_ActivateMomentIsFirstAccess_DoesNotReactivatePluginOnPluginEventDispatching()
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
	
	public function testActivation_ActivateMomentIsEveryAccess_ActivatesPluginOnEveryAccessAndReturnsProperPluginInstance()
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
	
	public function testActivation_ActivateMomentIsEveryAccess_ActivatesAllPluginsWithCorrectClasses()
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
	
	public function testActivation_ActivateMomentIsEveryAccess_ReactivatesPluginOnEveryEventDispatching()
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
	
	public function testActivation_ActivateMomentIsEveryAccess_DoesNotActivatePluginOnSpecInstanceCreation()
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
	
	public function testActivation_ActivateMomentIsSpecConstruct_ActivatesPluginOnSpecInstanceCreation()
	{
		\spectrum\tests\Test::$temp["activateCount"] = 0;
		
		$pluginClassName = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "specConstruct"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec)
				{
					\spectrum\tests\Test::$temp["activateCount"]++;
				}
				
				public function getOwnerSpec(){}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		$this->assertSame(0, \spectrum\tests\Test::$temp["activateCount"]);
		$spec = new Spec();
		$this->assertSame(1, \spectrum\tests\Test::$temp["activateCount"]);
	}
	
	public function testActivation_ActivateMomentIsSpecConstruct_ActivatesAllPluginsWithCorrectClasses()
	{
		\spectrum\tests\Test::$temp["plugin1"]["pluginInstanceOnActivate"] = null;
		\spectrum\tests\Test::$temp["plugin2"]["pluginInstanceOnActivate"] = null;
		\spectrum\tests\Test::$temp["plugin3"]["pluginInstanceOnActivate"] = null;
		
		$pluginClassName1 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "specConstruct"; }
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
				static public function getActivateMoment(){ return "specConstruct"; }
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
				static public function getActivateMoment(){ return "specConstruct"; }
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
		new Spec();
		
		$this->assertInstanceOf($pluginClassName1, \spectrum\tests\Test::$temp["plugin1"]["pluginInstanceOnActivate"]);
		$this->assertInstanceOf($pluginClassName2, \spectrum\tests\Test::$temp["plugin2"]["pluginInstanceOnActivate"]);
		$this->assertInstanceOf($pluginClassName3, \spectrum\tests\Test::$temp["plugin3"]["pluginInstanceOnActivate"]);
		
		$this->assertNotSame(\spectrum\tests\Test::$temp["plugin1"]["pluginInstanceOnActivate"], \spectrum\tests\Test::$temp["plugin2"]["pluginInstanceOnActivate"]);
		$this->assertNotSame(\spectrum\tests\Test::$temp["plugin2"]["pluginInstanceOnActivate"], \spectrum\tests\Test::$temp["plugin3"]["pluginInstanceOnActivate"]);
		$this->assertNotSame(\spectrum\tests\Test::$temp["plugin3"]["pluginInstanceOnActivate"], \spectrum\tests\Test::$temp["plugin1"]["pluginInstanceOnActivate"]);
	}
	
	public function testActivation_ActivateMomentIsSpecConstruct_ReturnsProperPluginInstanceOnPluginAccess()
	{
		\spectrum\tests\Test::$temp["pluginInstanceOnActivate"] = null;
		
		$pluginClassName = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "specConstruct"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec)
				{
					\spectrum\tests\Test::$temp["pluginInstanceOnActivate"] = $this;
				}
				
				public function getOwnerSpec(){}
			}
		');
		
		config::registerSpecPlugin($pluginClassName);
		$spec = new Spec();
		$this->assertSame($spec->aaa, \spectrum\tests\Test::$temp["pluginInstanceOnActivate"]);
	}
	
	public function testActivation_ActivateMomentIsSpecConstruct_DoesNotReactivatePluginOnPluginAccess()
	{
		\spectrum\tests\Test::$temp["activateCount"] = 0;
		
		$pluginClassName = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "specConstruct"; }
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
	
	public function testActivation_ActivateMomentIsSpecConstruct_DoesNotReactivatePluginOnPluginEventDispatching()
	{
		\spectrum\tests\Test::$temp["activateCount"] = 0;
		\spectrum\tests\Test::$temp["eventDispatchCount"] = 0;
		
		$pluginClassName = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "specConstruct"; }
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

	public function testEventDispatch_CallsSpecifiedMethodWithPassedArguments()
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
	
	public function testEventDispatch_CallsSpecifiedMethodInOrderFromLowerNegativeToHigherPositiveNumber()
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
}