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

	public function testSetClassReplacement_SetsNewClass()
	{
		config::setClassReplacement('\spectrum\core\plugins\basePlugins\reports\drivers\html\html', '\aaa');
		$this->assertSame('\aaa', config::getClassReplacement('\spectrum\core\plugins\basePlugins\reports\drivers\html\html'));
	}

	public function testSetClassReplacement_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue()
	{
		config::setClassReplacement('\spectrum\core\plugins\basePlugins\reports\drivers\html\html', '\aaa');
		config::lock();

		$this->assertThrowsException('\spectrum\Exception', '\spectrum\config is locked', function(){
			config::setClassReplacement('\spectrum\core\plugins\basePlugins\reports\drivers\html\html', '\bbb');
		});

		$this->assertSame('\aaa', config::getClassReplacement('\spectrum\core\plugins\basePlugins\reports\drivers\html\html'));
	}
	
/**/
	
	public function testGetClassReplacement_ReturnsSpectrumClassByDefault()
	{
		$this->assertSame('\spectrum\core\plugins\basePlugins\reports\drivers\html\html', config::getClassReplacement('\spectrum\core\plugins\basePlugins\reports\drivers\html\html'));
	}
	
	public function testGetClassReplacement_ConfigIsLocked_DoesNotThrowException()
	{
		config::lock();
		config::getClassReplacement('\spectrum\core\plugins\basePlugins\reports\drivers\html\html');
	}
	
/**/

	public function testSetFunctionReplacement_SetsNewClass()
	{
		config::setFunctionReplacement('\spectrum\tools\translate', '\aaa');
		$this->assertSame('\aaa', config::getFunctionReplacement('\spectrum\tools\translate'));
	}

	public function testSetFunctionReplacement_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue()
	{
		config::setFunctionReplacement('\spectrum\tools\translate', '\aaa');
		config::lock();

		$this->assertThrowsException('\spectrum\Exception', '\spectrum\config is locked', function(){
			config::setFunctionReplacement('\spectrum\tools\translate', '\bbb');
		});

		$this->assertSame('\aaa', config::getFunctionReplacement('\spectrum\tools\translate'));
	}
	
/**/
	
	public function testGetFunctionReplacement_ReturnsSpectrumClassByDefault()
	{
		$this->assertSame('\spectrum\tools\translate', config::getFunctionReplacement('\spectrum\tools\translate'));
	}
	
	public function testGetFunctionReplacement_ConfigIsLocked_DoesNotThrowException()
	{
		config::lock();
		config::getFunctionReplacement('\spectrum\tools\translate');
	}
	
/**/

	public function testSetAssertClass_SetsNewClass()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\AssertInterface
			{
				public function __construct(\spectrum\core\SpecInterface $ownerSpec, $testedValue){}
				public function __call($name, array $matcherArguments = array()){}
				public function __get($name){}
			}
		');
		
		config::setAssertClass($className);
		$this->assertSame($className, config::getAssertClass());
	}

	public function testSetAssertClass_ClassNotExists_ThrowsExceptionAndDoesNotChangeValue()
	{
		$oldClass = config::getAssertClass();

		$this->assertThrowsException('\spectrum\Exception', 'Class "\spectrum\tests\_testware\NotExistsClass" not exists', function(){
			config::setAssertClass('\spectrum\tests\_testware\NotExistsClass');
		});

		$this->assertSame($oldClass, config::getAssertClass());
	}

	public function testSetAssertClass_ClassNotImplementSpectrumInterface_ThrowsExceptionAndDoesNotChangeValue()
	{
		$oldClass = config::getAssertClass();

		$this->assertThrowsException('\spectrum\Exception', 'Class "\stdClass" should be implement interface "\spectrum\core\AssertInterface"', function(){
			config::setAssertClass('\stdClass');
		});

		$this->assertSame($oldClass, config::getAssertClass());
	}

	public function testSetAssertClass_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\AssertInterface
			{
				public function __construct(\spectrum\core\SpecInterface $ownerSpec, $testedValue){}
				public function __call($name, array $matcherArguments = array()){}
				public function __get($name){}
			}
		');
		
		$oldClass = config::getAssertClass();
		config::lock();

		$this->assertThrowsException('\spectrum\Exception', '\spectrum\config is locked', function() use($className){
			config::setAssertClass($className);
		});

		$this->assertSame($oldClass, config::getAssertClass());
	}
	
/**/
	
	public function testGetAssertClass_ReturnsSpectrumClassByDefault()
	{
		$this->assertSame('\spectrum\core\Assert', config::getAssertClass());
	}
	
	public function testGetAssertClass_ConfigIsLocked_DoesNotThrowException()
	{
		config::lock();
		config::getAssertClass();
	}

/**/

	public function testSetMatcherCallDetailsClass_SetsNewClass()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\details\MatcherCallInterface
			{
				public function setTestedValue($testedValue){}
				public function getTestedValue(){}
				
				public function setNot($not){}
				public function getNot(){}
				
				public function setResult($result){}
				public function getResult(){}
				
				public function setMatcherName($matcherName){}
				public function getMatcherName(){}
				
				public function setMatcherArguments(array $matcherArguments){}
				public function getMatcherArguments(){}
				
				public function setMatcherReturnValue($matcherReturnValue){}
				public function getMatcherReturnValue(){}
				
				public function setMatcherException(\Exception $exception = null){}
				public function getMatcherException(){}
				
				public function setFile($path){}
				public function getFile(){}
				
				public function setLine($number){}
				public function getLine(){}
			}
		');
		
		config::setMatcherCallDetailsClass($className);
		$this->assertSame($className, config::getMatcherCallDetailsClass());
	}

	public function testSetMatcherCallDetailsClass_ClassNotExists_ThrowsExceptionAndDoesNotChangeValue()
	{
		$oldClass = config::getMatcherCallDetailsClass();

		$this->assertThrowsException('\spectrum\Exception', 'Class "\spectrum\tests\_testware\NotExistsClass" not exists', function(){
			config::setMatcherCallDetailsClass('\spectrum\tests\_testware\NotExistsClass');
		});

		$this->assertSame($oldClass, config::getMatcherCallDetailsClass());
	}

	public function testSetMatcherCallDetailsClass_ClassNotImplementSpectrumInterface_ThrowsExceptionAndDoesNotChangeValue()
	{
		$oldClass = config::getMatcherCallDetailsClass();

		$this->assertThrowsException('\spectrum\Exception', 'Class "\stdClass" should be implement interface "\spectrum\core\details\MatcherCallInterface"', function(){
			config::setMatcherCallDetailsClass('\stdClass');
		});

		$this->assertSame($oldClass, config::getMatcherCallDetailsClass());
	}

	public function testSetMatcherCallDetailsClass_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\details\MatcherCallInterface
			{
				public function setTestedValue($testedValue){}
				public function getTestedValue(){}
				
				public function setNot($not){}
				public function getNot(){}
				
				public function setResult($result){}
				public function getResult(){}
				
				public function setMatcherName($matcherName){}
				public function getMatcherName(){}
				
				public function setMatcherArguments(array $matcherArguments){}
				public function getMatcherArguments(){}
				
				public function setMatcherReturnValue($matcherReturnValue){}
				public function getMatcherReturnValue(){}
				
				public function setMatcherException(\Exception $exception = null){}
				public function getMatcherException(){}
				
				public function setFile($path){}
				public function getFile(){}
				
				public function setLine($number){}
				public function getLine(){}
			}
		');
		
		$oldClass = config::getMatcherCallDetailsClass();
		config::lock();

		$this->assertThrowsException('\spectrum\Exception', '\spectrum\config is locked', function() use($className){
			config::setMatcherCallDetailsClass($className);
		});

		$this->assertSame($oldClass, config::getMatcherCallDetailsClass());
	}
	
/**/

	public function testGetMatcherCallDetailsClass_ReturnsSpectrumClassByDefault()
	{
		$this->assertSame('\spectrum\core\details\MatcherCall', config::getMatcherCallDetailsClass());
	}
	
	public function testGetMatcherCallDetailsClass_ConfigIsLocked_DoesNotThrowException()
	{
		config::lock();
		config::getMatcherCallDetailsClass();
	}

/**/

	public function testSetPhpErrorDetailsClass_SetsNewClass()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\details\PhpErrorInterface
			{
				public function __construct($errorLevel, $errorMessage, $file, $line){}
				public function getErrorLevel(){}
				public function getErrorMessage(){}
				public function getFile(){}
				public function getLine(){}
			}
		');
		
		config::setPhpErrorDetailsClass($className);
		$this->assertSame($className, config::getPhpErrorDetailsClass());
	}

	public function testSetPhpErrorDetailsClass_ClassNotExists_ThrowsExceptionAndDoesNotChangeValue()
	{
		$oldClass = config::getPhpErrorDetailsClass();

		$this->assertThrowsException('\spectrum\Exception', 'Class "\spectrum\tests\_testware\NotExistsClass" not exists', function(){
			config::setPhpErrorDetailsClass('\spectrum\tests\_testware\NotExistsClass');
		});

		$this->assertSame($oldClass, config::getPhpErrorDetailsClass());
	}

	public function testSetPhpErrorDetailsClass_ClassNotImplementSpectrumInterface_ThrowsExceptionAndDoesNotChangeValue()
	{
		$oldClass = config::getPhpErrorDetailsClass();

		$this->assertThrowsException('\spectrum\Exception', 'Class "\stdClass" should be implement interface "\spectrum\core\details\PhpErrorInterface"', function(){
			config::setPhpErrorDetailsClass('\stdClass');
		});

		$this->assertSame($oldClass, config::getPhpErrorDetailsClass());
	}

	public function testSetPhpErrorDetailsClass_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\details\PhpErrorInterface
			{
				public function __construct($errorLevel, $errorMessage, $file, $line){}
				public function getErrorLevel(){}
				public function getErrorMessage(){}
				public function getFile(){}
				public function getLine(){}
			}
		');
		
		$oldClass = config::getPhpErrorDetailsClass();
		config::lock();

		$this->assertThrowsException('\spectrum\Exception', '\spectrum\config is locked', function() use($className){
			config::setPhpErrorDetailsClass($className);
		});

		$this->assertSame($oldClass, config::getPhpErrorDetailsClass());
	}
	
/**/
	
	public function testGetPhpErrorDetailsClass_ReturnsSpectrumClassByDefault()
	{
		$this->assertSame('\spectrum\core\details\PhpError', config::getPhpErrorDetailsClass());
	}
	
	public function testGetPhpErrorDetailsClass_ConfigIsLocked_DoesNotThrowException()
	{
		config::lock();
		config::getPhpErrorDetailsClass();
	}

/**/

	public function testSetUserFailDetailsClass_SetsNewClass()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\details\UserFailInterface
			{
				public function __construct($message){}
				public function getMessage(){}
			}
		');
		
		config::setUserFailDetailsClass($className);
		$this->assertSame($className, config::getUserFailDetailsClass());
	}

	public function testSetUserFailDetailsClass_ClassNotExists_ThrowsExceptionAndDoesNotChangeValue()
	{
		$oldClass = config::getUserFailDetailsClass();

		$this->assertThrowsException('\spectrum\Exception', 'Class "\spectrum\tests\_testware\NotExistsClass" not exists', function(){
			config::setUserFailDetailsClass('\spectrum\tests\_testware\NotExistsClass');
		});

		$this->assertSame($oldClass, config::getUserFailDetailsClass());
	}

	public function testSetUserFailDetailsClass_ClassNotImplementSpectrumInterface_ThrowsExceptionAndDoesNotChangeValue()
	{
		$oldClass = config::getUserFailDetailsClass();

		$this->assertThrowsException('\spectrum\Exception', 'Class "\stdClass" should be implement interface "\spectrum\core\details\UserFailInterface"', function(){
			config::setUserFailDetailsClass('\stdClass');
		});

		$this->assertSame($oldClass, config::getUserFailDetailsClass());
	}

	public function testSetUserFailDetailsClass_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\details\UserFailInterface
			{
				public function __construct($message){}
				public function getMessage(){}
			}
		');
		
		$oldClass = config::getUserFailDetailsClass();
		config::lock();

		$this->assertThrowsException('\spectrum\Exception', '\spectrum\config is locked', function() use($className){
			config::setUserFailDetailsClass($className);
		});

		$this->assertSame($oldClass, config::getUserFailDetailsClass());
	}
	
/**/
	
	public function testGetUserFailDetailsClass_ReturnsSpectrumClassByDefault()
	{
		$this->assertSame('\spectrum\core\details\UserFail', config::getUserFailDetailsClass());
	}
	
	public function testGetUserFailDetailsClass_ConfigIsLocked_DoesNotThrowException()
	{
		config::lock();
		config::getUserFailDetailsClass();
	}

/**/

	public function testSetSpecClass_SetsNewClass()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\SpecInterface
			{
				public function __get($pluginAccessName){}
				
				public function enable(){}
				public function disable(){}
				public function isEnabled(){}
			
				public function setName($name){}
				public function getName(){}
				public function isAnonymous(){}
				
				public function getParentSpecs(){}
				public function hasParentSpec(\spectrum\core\SpecInterface $spec){}
				public function bindParentSpec(\spectrum\core\SpecInterface $spec){}
				public function unbindParentSpec(\spectrum\core\SpecInterface $spec){}
				public function unbindAllParentSpecs(){}
			
				public function getChildSpecs(){}
				public function hasChildSpec(\spectrum\core\SpecInterface $spec){}
				public function bindChildSpec(\spectrum\core\SpecInterface $spec){}
				public function unbindChildSpec(\spectrum\core\SpecInterface $spec){}
				public function unbindAllChildSpecs(){}
				
				public function getRootSpecs(){}
				public function getEndingSpecs(){}
				public function getRunningParentSpec(){}
				public function getRunningAncestorSpecs(){}
				public function getRunningChildSpec(){}
				public function getRunningEndingSpec(){}
				public function getSpecsByRunId($id){}
			
				public function getResultBuffer(){}
				public function getRunId(){}
				public function isRunning(){}
				public function run(){}
			}
		');
		
		config::setSpecClass($className);
		$this->assertSame($className, config::getSpecClass());
	}

	public function testSetSpecClass_ClassNotExists_ThrowsExceptionAndDoesNotChangeValue()
	{
		$oldClass = config::getSpecClass();

		$this->assertThrowsException('\spectrum\Exception', 'Class "\spectrum\tests\_testware\NotExistsClass" not exists', function(){
			config::setSpecClass('\spectrum\tests\_testware\NotExistsClass');
		});

		$this->assertSame($oldClass, config::getSpecClass());
	}

	public function testSetSpecClass_ClassNotImplementSpectrumInterface_ThrowsExceptionAndDoesNotChangeValue()
	{
		$oldClass = config::getSpecClass();

		$this->assertThrowsException('\spectrum\Exception', 'Class "\stdClass" should be implement interface "\spectrum\core\SpecInterface"', function(){
			config::setSpecClass('\stdClass');
		});

		$this->assertSame($oldClass, config::getSpecClass());
	}

	public function testSetSpecClass_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\SpecInterface
			{
				public function __get($pluginAccessName){}
				
				public function enable(){}
				public function disable(){}
				public function isEnabled(){}
			
				public function setName($name){}
				public function getName(){}
				public function isAnonymous(){}
				
				public function getParentSpecs(){}
				public function hasParentSpec(\spectrum\core\SpecInterface $spec){}
				public function bindParentSpec(\spectrum\core\SpecInterface $spec){}
				public function unbindParentSpec(\spectrum\core\SpecInterface $spec){}
				public function unbindAllParentSpecs(){}
			
				public function getChildSpecs(){}
				public function hasChildSpec(\spectrum\core\SpecInterface $spec){}
				public function bindChildSpec(\spectrum\core\SpecInterface $spec){}
				public function unbindChildSpec(\spectrum\core\SpecInterface $spec){}
				public function unbindAllChildSpecs(){}
				
				public function getRootSpecs(){}
				public function getEndingSpecs(){}
				public function getRunningParentSpec(){}
				public function getRunningAncestorSpecs(){}
				public function getRunningChildSpec(){}
				public function getRunningEndingSpec(){}
				public function getSpecsByRunId($id){}
			
				public function getResultBuffer(){}
				public function getRunId(){}
				public function isRunning(){}
				public function run(){}
			}
		');
		
		$oldClass = config::getSpecClass();
		config::lock();

		$this->assertThrowsException('\spectrum\Exception', '\spectrum\config is locked', function() use($className){
			config::setSpecClass($className);
		});

		$this->assertSame($oldClass, config::getSpecClass());
	}
	
/**/

	public function testGetSpecClass_ReturnsSpectrumClassByDefault()
	{
		$this->assertSame('\spectrum\core\Spec', config::getSpecClass());
	}
	
	public function testGetSpecClass_ConfigIsLocked_DoesNotThrowException()
	{
		config::lock();
		config::getSpecClass();
	}

/**/

	public function testSetContextDataClass_SetsNewClass()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\plugins\basePlugins\contexts\DataInterface
			{
				public function count(){}
				public function offsetSet($key, $value){}
				public function offsetExists($key){}
				public function offsetUnset($key){}
				public function offsetGet($key){}
			}
		');
		
		config::setContextDataClass($className);
		$this->assertSame($className, config::getContextDataClass());
	}

	public function testSetContextDataClass_ClassNotExists_ThrowsExceptionAndDoesNotChangeValue()
	{
		$oldClass = config::getContextDataClass();

		$this->assertThrowsException('\spectrum\Exception', 'Class "\spectrum\tests\_testware\NotExistsClass" not exists', function(){
			config::setContextDataClass('\spectrum\tests\_testware\NotExistsClass');
		});

		$this->assertSame($oldClass, config::getContextDataClass());
	}

	public function testSetContextDataClass_ClassNotImplementSpectrumInterface_ThrowsExceptionAndDoesNotChangeValue()
	{
		$oldClass = config::getContextDataClass();

		$this->assertThrowsException('\spectrum\Exception', 'Class "\stdClass" should be implement interface "\spectrum\core\plugins\basePlugins\contexts\DataInterface"', function(){
			config::setContextDataClass('\stdClass');
		});

		$this->assertSame($oldClass, config::getContextDataClass());
	}

	public function testSetContextDataClass_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\plugins\basePlugins\contexts\DataInterface
			{
				public function count(){}
				public function offsetSet($key, $value){}
				public function offsetExists($key){}
				public function offsetUnset($key){}
				public function offsetGet($key){}
			}
		');
		
		$oldClass = config::getContextDataClass();
		config::lock();

		$this->assertThrowsException('\spectrum\Exception', '\spectrum\config is locked', function() use($className){
			config::setContextDataClass($className);
		});

		$this->assertSame($oldClass, config::getContextDataClass());
	}
	
/**/

	public function testGetContextDataClass_ReturnsSpectrumClassByDefault()
	{
		$this->assertSame('\spectrum\core\plugins\basePlugins\contexts\Data', config::getContextDataClass());
	}
	
	public function testGetContextDataClass_ConfigIsLocked_DoesNotThrowException()
	{
		config::lock();
		config::getContextDataClass();
	}

/**/

	public function testSetResultBufferClass_SetsNewClass()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\ResultBufferInterface
			{
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
				
				public function addResult($result, $details = null){}
				public function getResults(){}
				public function getTotalResult(){}
				
				public function lock(){}
				public function isLocked(){}
			}
		');
		
		config::setResultBufferClass($className);
		$this->assertSame($className, config::getResultBufferClass());
	}

	public function testSetResultBufferClass_ClassNotExists_ThrowsExceptionAndDoesNotChangeValue()
	{
		$oldClass = config::getResultBufferClass();

		$this->assertThrowsException('\spectrum\Exception', 'Class "\spectrum\tests\_testware\NotExistsClass" not exists', function(){
			config::setResultBufferClass('\spectrum\tests\_testware\NotExistsClass');
		});

		$this->assertSame($oldClass, config::getResultBufferClass());
	}

	public function testSetResultBufferClass_ClassNotImplementSpectrumInterface_ThrowsExceptionAndDoesNotChangeValue()
	{
		$oldClass = config::getResultBufferClass();

		$this->assertThrowsException('\spectrum\Exception', 'Class "\stdClass" should be implement interface "\spectrum\core\ResultBufferInterface"', function(){
			config::setResultBufferClass('\stdClass');
		});

		$this->assertSame($oldClass, config::getResultBufferClass());
	}

	public function testSetResultBufferClass_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\ResultBufferInterface
			{
				public function __construct(\spectrum\core\SpecInterface $ownerSpec){}
				public function getOwnerSpec(){}
				
				public function addResult($result, $details = null){}
				public function getResults(){}
				public function getTotalResult(){}
				
				public function lock(){}
				public function isLocked(){}
			}
		');
		
		$oldClass = config::getResultBufferClass();
		config::lock();

		$this->assertThrowsException('\spectrum\Exception', '\spectrum\config is locked', function() use($className){
			config::setResultBufferClass($className);
		});

		$this->assertSame($oldClass, config::getResultBufferClass());
	}
	
/**/
	
	public function testGetResultBufferClass_ReturnsSpectrumClassByDefault()
	{
		$this->assertSame('\spectrum\core\ResultBuffer', config::getResultBufferClass());
	}
	
	public function testGetResultBufferClass_ConfigIsLocked_DoesNotThrowException()
	{
		config::lock();
		config::getResultBufferClass();
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
			'\spectrum\core\plugins\basePlugins\contexts\Contexts',
			'\spectrum\core\plugins\basePlugins\ErrorHandling',
			'\spectrum\core\plugins\basePlugins\reports\Reports',
			'\spectrum\core\plugins\basePlugins\Matchers',
			'\spectrum\core\plugins\basePlugins\Messages',
			'\spectrum\core\plugins\basePlugins\Test',
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