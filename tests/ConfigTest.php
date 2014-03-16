<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests;
use spectrum\config;

require_once __DIR__ . '/init.php';

class ConfigTest extends Test
{
	public function setUp()
	{
		parent::setUp();
		config::unregisterSpecPlugins();
	}

/**/

	public function testSetInputCharset_SetsNewValue()
	{
		config::setInputCharset('windows-1251');
		$this->assertSame('windows-1251', config::getInputCharset());
		
		config::setInputCharset('utf-8');
		$this->assertSame('utf-8', config::getInputCharset());
	}

	public function testSetInputCharset_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue()
	{
		config::setInputCharset('utf-8');
		config::lock();

		$this->assertThrowsException('\spectrum\Exception', '\spectrum\config is locked', function(){
			config::setInputCharset('windows-1251');
		});

		$this->assertSame('utf-8', config::getInputCharset());
	}
		
/**/
	
	public function testGetInputCharset_ReturnsUtf8ByDefault()
	{
		$this->assertSame('utf-8', config::getInputCharset());
	}
	
	public function testGetInputCharset_ConfigIsLocked_DoesNotThrowException()
	{
		config::lock();
		config::getInputCharset();
	}

/**/

	public function testSetOutputCharset_SetsNewValue()
	{
		config::setOutputCharset('windows-1251');
		$this->assertSame('windows-1251', config::getOutputCharset());
		
		config::setOutputCharset('utf-8');
		$this->assertSame('utf-8', config::getOutputCharset());
	}

	public function testSetOutputCharset_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue()
	{
		config::setOutputCharset('utf-8');
		config::lock();

		$this->assertThrowsException('\spectrum\Exception', '\spectrum\config is locked', function(){
			config::setOutputCharset('windows-1251');
		});

		$this->assertSame('utf-8', config::getOutputCharset());
	}
	
/**/

	public function testGetOutputCharset_ReturnsUtf8ByDefault()
	{
		$this->assertSame('utf-8', config::getOutputCharset());
	}
	
	public function testGetOutputCharset_ConfigIsLocked_DoesNotThrowException()
	{
		config::lock();
		config::getOutputCharset();
	}
	
/**/

	public function testSetOutputFormat_SetsNewValue()
	{
		config::setOutputFormat('text');
		$this->assertSame('text', config::getOutputFormat());
		
		config::setOutputFormat('html');
		$this->assertSame('html', config::getOutputFormat());
	}
	
	public function testSetOutputFormat_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue()
	{
		config::setOutputFormat('html');
		config::lock();

		$this->assertThrowsException('\spectrum\Exception', '\spectrum\config is locked', function(){
			config::setOutputFormat('text');
		});

		$this->assertSame('html', config::getOutputFormat());
	}
	
/**/
	
	public function testGetOutputFormat_ReturnsHtmlByDefault()
	{
		$this->assertSame('html', config::getOutputFormat());
	}
	
	public function testGetOutputFormat_ConfigIsLocked_DoesNotThrowException()
	{
		config::lock();
		config::getOutputFormat();
	}

/**/

	public function testSetOutputIndention_SetsNewValue()
	{
		config::setOutputIndention('    ');
		$this->assertSame('    ', config::getOutputIndention());
		
		config::setOutputIndention("\t");
		$this->assertSame("\t", config::getOutputIndention());
		
		config::setOutputIndention(" \t ");
		$this->assertSame(" \t ", config::getOutputIndention());
	}
	
	public function testSetOutputIndention_IncorrectCharIsPassed_ThrowsExceptionAndDoesNotChangeValue()
	{
		config::setOutputIndention("\t");

		$this->assertThrowsException('\spectrum\Exception', 'Incorrect char is passed to "\spectrum\config::setOutputIndention" method (only "\t" and " " chars are allowed)', function(){
			config::setOutputIndention('z');
		});

		$this->assertSame("\t", config::getOutputIndention());
	}

	public function testSetOutputIndention_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue()
	{
		config::setOutputIndention("\t");
		config::lock();

		$this->assertThrowsException('\spectrum\Exception', '\spectrum\config is locked', function(){
			config::setOutputIndention('    ');
		});

		$this->assertSame("\t", config::getOutputIndention());
	}

/**/
	
	public function testGetOutputIndention_ReturnsTabByDefault()
	{
		$this->assertSame("\t", config::getOutputIndention());
	}
	
	public function testGetOutputIndention_ConfigIsLocked_DoesNotThrowException()
	{
		config::lock();
		config::getOutputIndention();
	}

/**/

	public function testSetOutputNewline_SetsNewValue()
	{
		config::setOutputNewline("\n");
		$this->assertSame("\n", config::getOutputNewline());
		
		config::setOutputNewline("\r\n\r\n");
		$this->assertSame("\r\n\r\n", config::getOutputNewline());
		
		config::setOutputNewline("\r\n");
		$this->assertSame("\r\n", config::getOutputNewline());
	}
	
	public function testSetOutputNewline_IncorrectCharIsPassed_ThrowsExceptionAndDoesNotChangeValue()
	{
		config::setOutputNewline("\n");

		$this->assertThrowsException('\spectrum\Exception', 'Incorrect char is passed to "\spectrum\config::setOutputNewline" method (only "\r" and "\n" chars are allowed)', function(){
			config::setOutputNewline('z');
		});

		$this->assertSame("\n", config::getOutputNewline());
	}

	public function testSetOutputNewline_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue()
	{
		config::setOutputNewline("\r\n");
		config::lock();

		$this->assertThrowsException('\spectrum\Exception', '\spectrum\config is locked', function(){
			config::setOutputNewline("\n");
		});

		$this->assertSame("\r\n", config::getOutputNewline());
	}

/**/

	public function testGetOutputNewline_ReturnsLfByDefault()
	{
		$this->assertSame("\n", config::getOutputNewline());
	}
	
	public function testGetOutputNewline_ConfigIsLocked_DoesNotThrowException()
	{
		config::lock();
		config::getOutputNewline();
	}
	
/**/

	public function testSetAllowErrorHandlingModify_SetsNewValue()
	{
		config::setAllowErrorHandlingModify(false);
		$this->assertFalse(config::getAllowErrorHandlingModify());
	}

	public function testSetAllowErrorHandlingModify_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue()
	{
		config::setAllowErrorHandlingModify(true);
		config::lock();

		$this->assertThrowsException('\spectrum\Exception', '\spectrum\config is locked', function(){
			config::setAllowErrorHandlingModify(false);
		});

		$this->assertTrue(config::getAllowErrorHandlingModify());
	}
	
/**/
	
	public function testGetAllowErrorHandlingModify_ReturnsTrueByDefault()
	{
		$this->assertTrue(config::getAllowErrorHandlingModify());
	}
	
	public function testGetAllowErrorHandlingModify_ConfigIsLocked_DoesNotThrowException()
	{
		config::lock();
		config::getAllowErrorHandlingModify();
	}
	
/**/

	public function testSetClassReplacement_ClassHasInterface_NewClassImplementsInterface_SetsNewClass()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\AssertInterface
			{
				public function __construct(\spectrum\core\SpecInterface $ownerSpec, $testedValue){}
				public function __call($name, array $matcherArguments = array()){}
				public function __get($name){}
			}
		');
		
		config::setClassReplacement('\spectrum\core\Assert', $className);
		$this->assertSame($className, config::getClassReplacement('\spectrum\core\Assert'));
	}
	
	public function testSetClassReplacement_ClassHasInterface_NewClassDoesNotImplementInterface_ThrowsExceptionAndDoesNotChangeValue()
	{
		$className = $this->createClass('class ... {}');
		$this->assertThrowsException('\spectrum\Exception', 'Class "' . $className . '" does not implement "\spectrum\core\AssertInterface"', function() use($className){
			config::setClassReplacement('\spectrum\core\Assert', $className);
		});

		$this->assertSame('\spectrum\core\Assert', config::getClassReplacement('\spectrum\core\Assert'));
	}

	public function testSetClassReplacement_ClassHasNoInterface_NewClassDoesNotImplementInterface_SetsNewClass()
	{
		config::setClassReplacement('\spectrum\core\plugins\reports\drivers\html\html', '\aaa');
		$this->assertSame('\aaa', config::getClassReplacement('\spectrum\core\plugins\reports\drivers\html\html'));
	}
	
	public function testSetClassReplacement_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue()
	{
		config::setClassReplacement('\spectrum\core\plugins\reports\drivers\html\html', '\aaa');
		config::lock();

		$this->assertThrowsException('\spectrum\Exception', '\spectrum\config is locked', function(){
			config::setClassReplacement('\spectrum\core\plugins\reports\drivers\html\html', '\bbb');
		});

		$this->assertSame('\aaa', config::getClassReplacement('\spectrum\core\plugins\reports\drivers\html\html'));
	}
	
/**/
	
	public function testGetClassReplacement_ReturnsSpectrumClassByDefault()
	{
		$this->assertSame('\spectrum\core\plugins\reports\drivers\html\html', config::getClassReplacement('\spectrum\core\plugins\reports\drivers\html\html'));
	}
	
	public function testGetClassReplacement_ConfigIsLocked_DoesNotThrowException()
	{
		config::lock();
		config::getClassReplacement('\spectrum\core\plugins\reports\drivers\html\html');
	}
	
/**/

	public function testSetFunctionReplacement_SetsNewClass()
	{
		config::setFunctionReplacement('\spectrum\_internal\translate', '\aaa');
		$this->assertSame('\aaa', config::getFunctionReplacement('\spectrum\_internal\translate'));
	}

	public function testSetFunctionReplacement_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue()
	{
		config::setFunctionReplacement('\spectrum\_internal\translate', '\aaa');
		config::lock();

		$this->assertThrowsException('\spectrum\Exception', '\spectrum\config is locked', function(){
			config::setFunctionReplacement('\spectrum\_internal\translate', '\bbb');
		});

		$this->assertSame('\aaa', config::getFunctionReplacement('\spectrum\_internal\translate'));
	}
	
/**/
	
	public function testGetFunctionReplacement_ReturnsSpectrumClassByDefault()
	{
		$this->assertSame('\spectrum\_internal\translate', config::getFunctionReplacement('\spectrum\_internal\translate'));
	}
	
	public function testGetFunctionReplacement_ConfigIsLocked_DoesNotThrowException()
	{
		config::lock();
		config::getFunctionReplacement('\spectrum\_internal\translate');
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
				static public function getAccessName(){ return "ccc"; }
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

		config::registerSpecPlugin(mb_strtoupper($className, 'us-ascii'));
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
			config::registerSpecPlugin(mb_strtoupper($className, 'us-ascii'));
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
	
	public function testUnregisterSpecPlugins_ResetsArrayIndexes()
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
		
		$this->assertSame(array($className1, $className2), config::getRegisteredSpecPlugins());
		config::unregisterSpecPlugins($className1);
		$this->assertSame(array($className2), config::getRegisteredSpecPlugins());
	}
	
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
		
		config::unregisterSpecPlugins(mb_strtoupper($className3, 'us-ascii'));
		$this->assertSame(array($className1, $className2), config::getRegisteredSpecPlugins());
		
		config::unregisterSpecPlugins(mb_strtoupper($className2, 'us-ascii'));
		$this->assertSame(array($className1), config::getRegisteredSpecPlugins());
		
		config::unregisterSpecPlugins(mb_strtoupper($className1, 'us-ascii'));
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
		$this->assertSame(array($className2), config::getRegisteredSpecPlugins());
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
		
		config::unregisterSpecPlugins(array(mb_strtoupper($className1, 'us-ascii'), mb_strtoupper($className3, 'us-ascii')));
		$this->assertSame(array($className2), config::getRegisteredSpecPlugins());
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
	
	public function testGetRegisteredSpecPlugins_ReturnsBasePluginsByDefault()
	{
		$this->restoreClassStaticProperties('\spectrum\config');
		
		$this->assertSame(array(
			'\spectrum\core\plugins\reports\Reports',
			'\spectrum\core\plugins\ContextModifiers',
			'\spectrum\core\plugins\ErrorHandling',
			'\spectrum\core\plugins\Matchers',
			'\spectrum\core\plugins\Messages',
			'\spectrum\core\plugins\Test',
		), config::getRegisteredSpecPlugins());
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
		
		$this->assertSame(true, config::hasRegisteredSpecPlugin(mb_strtoupper($className, 'us-ascii')));
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
	
/**/
	
	public function testIsLocked_ConfigIsNotLocked_ReturnsFalse()
	{
		$this->assertSame(false, config::isLocked());
	}
	
	public function testIsLocked_ConfigIsLocked_ReturnsTrue()
	{
		config::lock();
		$this->assertSame(true, config::isLocked());
	}
}