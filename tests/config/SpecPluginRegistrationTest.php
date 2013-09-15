<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\config;
use spectrum\config;

require_once __DIR__ . '/../init.php';

class SpecPluginRegistrationTest extends \spectrum\tests\Test
{
	public function setUp()
	{
		parent::setUp();
		config::unregisterSpecPlugins();
	}

	public function testHasRegisteredBasePluginsByDefault()
	{
		$this->restoreStaticProperties('\spectrum\config');
		
		$this->assertSame(array(
			'\spectrum\core\plugins\basePlugins\reports\Reports',
			'\spectrum\core\plugins\basePlugins\contexts\Contexts',
			'\spectrum\core\plugins\basePlugins\errorHandling\ErrorHandling',
			'\spectrum\core\plugins\basePlugins\TestFunction',
			'\spectrum\core\plugins\basePlugins\Matchers',
			'\spectrum\core\plugins\basePlugins\Messages',
			'\spectrum\core\plugins\basePlugins\Output',
		), config::getRegisteredSpecPlugins());
	}

/**/
	public function testRegisterSpecPlugin_AddsPluginClassToRegisteredPlugins()
	{
		$className1 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		$className2 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "bbb"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		$className3 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "ссс"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');

		config::registerSpecPlugin($className1);
		$this->assertSame(array($className1), config::getRegisteredSpecPlugins());
		
		config::registerSpecPlugin($className2);
		$this->assertSame(array($className1, $className2), config::getRegisteredSpecPlugins());
		
		config::registerSpecPlugin($className3);
		$this->assertSame(array($className1, $className2, $className3), config::getRegisteredSpecPlugins());
	}
	
	public function testRegisterSpecPlugin_AddsPluginClassInOriginCase()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');

		config::registerSpecPlugin(mb_strtoupper($className));
		$this->assertSame(array($className), config::getRegisteredSpecPlugins());
	}
	
	public function testRegisterSpecPlugin_AcceptsUnlimitedCountOfPluginsWithEmptyAccessName()
	{
		$className1 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return null; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		$className2 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return null; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		$className3 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return ""; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		$className4 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return ""; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');

		config::registerSpecPlugin($className1);
		config::registerSpecPlugin($className2);
		config::registerSpecPlugin($className3);
		config::registerSpecPlugin($className4);
		
		$this->assertSame(array(
			$className1,
			$className2,
			$className3,
			$className4,
		), config::getRegisteredSpecPlugins());
	}
	
	public function testRegisterSpecPlugin_ConfigIsLocked_ThrowsExceptionAndDoesNotRegisterPlugin()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		$backupOfRegisteredPlugins = config::getRegisteredSpecPlugins();
		config::lock();
		
		$this->assertThrowsException('\spectrum\Exception', '\spectrum\config is locked', function() use($className){
			config::registerSpecPlugin($className);
		});

		$this->assertSame($backupOfRegisteredPlugins, config::getRegisteredSpecPlugins());
	}
	
	public function testRegisterSpecPlugin_PluginClassDoesNotImplementInterface_ThrowsExceptionAndDoesNotRegisterPlugin()
	{
		$backupOfRegisteredPlugins = config::getRegisteredSpecPlugins();

		$this->assertThrowsException('\spectrum\Exception', 'Plugin class "\stdClass" does not implement PluginInterface', function(){
			config::registerSpecPlugin('\stdClass');
		});

		$this->assertSame($backupOfRegisteredPlugins, config::getRegisteredSpecPlugins());
	}
	
	public function testRegisterSpecPlugin_PluginWithSameClassIsAlreadyRegistered_PluginClassInSameCase_ThrowsExceptionAndDoesNotRegisterPlugin()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		config::registerSpecPlugin($className);
		$backupOfRegisteredPlugins = config::getRegisteredSpecPlugins();
		
		$this->assertThrowsException('\spectrum\Exception', 'Plugin with class "' . $className . '" is already registered', function() use($className){
			config::registerSpecPlugin($className);
		});

		$this->assertSame($backupOfRegisteredPlugins, config::getRegisteredSpecPlugins());
	}
	
	public function testRegisterSpecPlugin_PluginWithSameClassIsAlreadyRegistered_PluginClassInDifferentCase_ThrowsExceptionAndDoesNotRegisterPlugin()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		config::registerSpecPlugin($className);
		$backupOfRegisteredPlugins = config::getRegisteredSpecPlugins();
		
		$this->assertThrowsException('\spectrum\Exception', 'Plugin with class "' . $className . '" is already registered', function() use($className){
			config::registerSpecPlugin(mb_strtoupper($className));
		});

		$this->assertSame($backupOfRegisteredPlugins, config::getRegisteredSpecPlugins());
	}
	
	public function testRegisterSpecPlugin_PluginWithSameAccessNameIsAlreadyRegistered_AccessNameInSameCase_ThrowsExceptionAndDoesNotRegisterPlugin()
	{
		$className1 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		$className2 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		config::registerSpecPlugin($className1);
		
		$backupOfRegisteredPlugins = config::getRegisteredSpecPlugins();
		
		$this->assertThrowsException('\spectrum\Exception', 'Plugin with accessName "aaa" is already registered (remove registered plugin before register new)', function() use($className2){
			config::registerSpecPlugin($className2);
		});

		$this->assertSame($backupOfRegisteredPlugins, config::getRegisteredSpecPlugins());
	}
	
	public function testRegisterSpecPlugin_PluginWithAllowedActivateMoment_RegistersPlugin()
	{
		$className1 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		$className2 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "bbb"; }
				static public function getActivateMoment(){ return "everyAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		config::registerSpecPlugin($className1);
		config::registerSpecPlugin($className2);
		
		$this->assertSame(array($className1, $className2), config::getRegisteredSpecPlugins());
	}
	
	public function testRegisterSpecPlugin_PluginWithWrongActivateMoment_ThrowsExceptionAndDoesNotRegisterPlugin()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "AAAAAAA"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		$backupOfRegisteredPlugins = config::getRegisteredSpecPlugins();
		
		$this->assertThrowsException('\spectrum\Exception', 'Wrong activate moment "AAAAAAA" in plugin with class "' . $className . '"', function() use($className){
			config::registerSpecPlugin($className);
		});

		$this->assertSame($backupOfRegisteredPlugins, config::getRegisteredSpecPlugins());
	}
	
	public function testRegisterSpecPlugin_PluginEventListenerWithoutEventValue_ThrowsExceptionAndDoesNotRegisterPlugin()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){ return array(array("event" => "", "method" => "onEndingSpecExecute", "order" => 100)); }
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		$backupOfRegisteredPlugins = config::getRegisteredSpecPlugins();
		
		$this->assertThrowsException('\spectrum\Exception', 'Event for event listener #1 does not set in plugin with class "' . $className . '"', function() use($className){
			config::registerSpecPlugin($className);
		});

		$this->assertSame($backupOfRegisteredPlugins, config::getRegisteredSpecPlugins());
	}
	
	public function testRegisterSpecPlugin_PluginEventListenerWithoutMethodValue_ThrowsExceptionAndDoesNotRegisterPlugin()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){ return array(array("event" => "onEndingSpecExecute", "method" => "", "order" => 100)); }
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		$backupOfRegisteredPlugins = config::getRegisteredSpecPlugins();
		
		$this->assertThrowsException('\spectrum\Exception', 'Method for event listener #1 does not set in plugin with class "' . $className . '"', function() use($className){
			config::registerSpecPlugin($className);
		});

		$this->assertSame($backupOfRegisteredPlugins, config::getRegisteredSpecPlugins());
	}
	
	public function testRegisterSpecPlugin_PluginEventListenerWithoutOrderValue_ThrowsExceptionAndDoesNotRegisterPlugin()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){ return array(array("event" => "onEndingSpecExecute", "method" => "onEndingSpecExecute", "order" => "")); }
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		$backupOfRegisteredPlugins = config::getRegisteredSpecPlugins();
		
		$this->assertThrowsException('\spectrum\Exception', 'Order for event listener #1 does not set in plugin with class "' . $className . '"', function() use($className){
			config::registerSpecPlugin($className);
		});

		$this->assertSame($backupOfRegisteredPlugins, config::getRegisteredSpecPlugins());
	}
	
/**/
	
	public function testUnregisterSpecPlugins_NoArguments_RemovesAllPluginClassesFromRegisteredPlugins()
	{
		$className1 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		$className2 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "bbb"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		$className3 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "ccc"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');

		config::registerSpecPlugin($className1);
		config::registerSpecPlugin($className2);
		config::registerSpecPlugin($className3);
		
		$this->assertSame(array($className1, $className2, $className3), config::getRegisteredSpecPlugins());
		
		config::unregisterSpecPlugins();
		$this->assertSame(array(), config::getRegisteredSpecPlugins());
	}
	
	public function testUnregisterSpecPlugins_StringWithClassAsFirstArgument_PluginClassInSameCase_RemovesPluginClassFromRegisteredPlugins()
	{
		$className1 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		$className2 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "bbb"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		$className3 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "ccc"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');

		config::registerSpecPlugin($className1);
		config::registerSpecPlugin($className2);
		config::registerSpecPlugin($className3);
		
		$this->assertSame(array($className1, $className2, $className3), config::getRegisteredSpecPlugins());
		
		config::unregisterSpecPlugins($className3);
		$this->assertSame(array($className1, $className2), config::getRegisteredSpecPlugins());
		
		config::unregisterSpecPlugins($className2);
		$this->assertSame(array($className1), config::getRegisteredSpecPlugins());
		
		config::unregisterSpecPlugins($className1);
		$this->assertSame(array(), config::getRegisteredSpecPlugins());
	}
	
	public function testUnregisterSpecPlugins_StringWithClassAsFirstArgument_PluginClassInDifferentCase_RemovesPluginClassFromRegisteredPlugins()
	{
		$className1 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		$className2 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "bbb"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		$className3 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "ccc"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');

		config::registerSpecPlugin($className1);
		config::registerSpecPlugin($className2);
		config::registerSpecPlugin($className3);
		
		$this->assertSame(array($className1, $className2, $className3), config::getRegisteredSpecPlugins());
		
		config::unregisterSpecPlugins(mb_strtoupper($className3));
		$this->assertSame(array($className1, $className2), config::getRegisteredSpecPlugins());
		
		config::unregisterSpecPlugins(mb_strtoupper($className2));
		$this->assertSame(array($className1), config::getRegisteredSpecPlugins());
		
		config::unregisterSpecPlugins(mb_strtoupper($className1));
		$this->assertSame(array(), config::getRegisteredSpecPlugins());
	}
	
	public function testUnregisterSpecPlugins_ArrayWithManyClassesAsFirstArgument_PluginClassInSameCase_RemovesPluginClassFromRegisteredPlugins()
	{
		$className1 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		$className2 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "bbb"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		$className3 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "ccc"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');

		config::registerSpecPlugin($className1);
		config::registerSpecPlugin($className2);
		config::registerSpecPlugin($className3);
		
		$this->assertSame(array($className1, $className2, $className3), config::getRegisteredSpecPlugins());
		
		config::unregisterSpecPlugins(array($className1, $className3));
		$this->assertSame(array(1 => $className2), config::getRegisteredSpecPlugins());
	}
	
	public function testUnregisterSpecPlugins_ArrayWithManyClassesAsFirstArgument_PluginClassInDifferentCase_RemovesPluginClassFromRegisteredPlugins()
	{
		$className1 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		$className2 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "bbb"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		$className3 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "ccc"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');

		config::registerSpecPlugin($className1);
		config::registerSpecPlugin($className2);
		config::registerSpecPlugin($className3);
		
		$this->assertSame(array($className1, $className2, $className3), config::getRegisteredSpecPlugins());
		
		config::unregisterSpecPlugins(array(mb_strtoupper($className1), mb_strtoupper($className3)));
		$this->assertSame(array(1 => $className2), config::getRegisteredSpecPlugins());
	}
	
	public function testUnregisterSpecPlugins_ConfigIsLocked_ThrowsExceptionAndDoesNotUnregisterPlugin()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');

		config::registerSpecPlugin($className);
		$backupOfRegisteredPlugins = config::getRegisteredSpecPlugins();
		
		config::lock();
		$this->assertThrowsException('\spectrum\Exception', '\spectrum\config is locked', function() use($className){
			config::unregisterSpecPlugins($className);
		});

		$this->assertSame($backupOfRegisteredPlugins, config::getRegisteredSpecPlugins());
	}
	
/**/
	
	public function testGetRegisteredSpecPlugins_ReturnsEmptyArrayByDefault()
	{
		$this->assertSame(array(), config::getRegisteredSpecPlugins());
	}
	
	public function testGetRegisteredSpecPlugins_ReturnsRegisteredPlugins()
	{
		$className1 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		$className2 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "bbb"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		$className3 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "ccc"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		config::registerSpecPlugin($className1);
		config::registerSpecPlugin($className2);
		config::registerSpecPlugin($className3);
		
		$this->assertSame(array($className1, $className2, $className3), config::getRegisteredSpecPlugins());
	}
	
	public function testGetRegisteredSpecPlugins_ConfigIsLocked_DoesNotThrowException()
	{
		config::lock();
		config::getRegisteredSpecPlugins();
	}

/**/

	public function testGetRegisteredSpecPluginClassByAccessName_AccessNameInSameCase_ReturnsProperClass()
	{
		$className1 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		$className2 = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "bbb"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		config::registerSpecPlugin($className1);
		config::registerSpecPlugin($className2);
		
		$this->assertSame($className1, config::getRegisteredSpecPluginClassByAccessName('aaa'));
		$this->assertSame($className2, config::getRegisteredSpecPluginClassByAccessName('bbb'));
	}
	
	public function testGetRegisteredSpecPluginClassByAccessName_ReturnsNullWhenNoMatches()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		config::registerSpecPlugin($className);
		
		$this->assertSame(null, config::getRegisteredSpecPluginClassByAccessName('zzz'));
	}
	
	public function testGetRegisteredSpecPluginClassByAccessName_ConfigIsLocked_DoesNotThrowException()
	{
		config::lock();
		config::getRegisteredSpecPluginClassByAccessName('zzz');
	}
	
/**/

	public function testHasRegisteredSpecPlugin_SoughtPluginClassIsRegistered_PluginClassInSameCase_ReturnsTrue()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		config::registerSpecPlugin($className);
		
		$this->assertSame(true, config::hasRegisteredSpecPlugin($className));
	}
	
	public function testHasRegisteredSpecPlugin_SoughtPluginClassIsRegistered_PluginClassInDifferentCase_ReturnsTrue()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		config::registerSpecPlugin($className);
		
		$this->assertSame(true, config::hasRegisteredSpecPlugin(mb_strtoupper($className)));
	}
	
	public function testHasRegisteredSpecPlugin_SoughtPluginClassIsNotRegistered_ReturnsFalse()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\plugins\PluginInterface
			{
				static public function getAccessName(){ return "aaa"; }
				static public function getActivateMoment(){ return "firstAccess"; }
				static public function getEventListeners(){}
				
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
			}
		');
		
		config::registerSpecPlugin($className);
		
		$this->assertSame(false, config::hasRegisteredSpecPlugin('\stdClass'));
	}
	
	public function testHasRegisteredSpecPlugin_ConfigIsLocked_DoesNotThrowException()
	{
		config::lock();
		config::hasRegisteredSpecPlugin('\stdClass');
	}
}