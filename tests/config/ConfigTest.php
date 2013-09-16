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

class ConfigTest extends \spectrum\tests\Test
{
	public function testGetConstructionCommandsCallBrokerClass_ReturnsSpectrumClassByDefault()
	{
		$this->assertSame('\spectrum\constructionCommands\callBroker', config::getConstructionCommandsCallBrokerClass());
	}
	
	public function testGetConstructionCommandsCallBrokerClass_ConfigIsLocked_DoesNotThrowException()
	{
		config::lock();
		config::getConstructionCommandsCallBrokerClass();
	}
	
/**/

	public function testSetConstructionCommandsCallBrokerClass_SetsNewClass()
	{
		$className = $this->createClass('
			final class ... implements \spectrum\constructionCommands\callBrokerInterface
			{
				private function __construct(){}
				static public function __callStatic($constructionCommandName, array $arguments = array()){}
			}
		');
		
		config::setConstructionCommandsCallBrokerClass($className);
		$this->assertSame($className, config::getConstructionCommandsCallBrokerClass());
	}

	public function testSetConstructionCommandsCallBrokerClass_ClassNotExists_ThrowsExceptionAndDoesNotChangeValue()
	{
		$oldClass = config::getConstructionCommandsCallBrokerClass();

		$this->assertThrowsException('\spectrum\Exception', 'not exists', function(){
			config::setConstructionCommandsCallBrokerClass('\spectrum\tests\testHelpers\NotExistsClass');
		});

		$this->assertSame($oldClass, config::getConstructionCommandsCallBrokerClass());
	}

	public function testSetConstructionCommandsCallBrokerClass_ClassNotImplementSpectrumInterface_ThrowsExceptionAndDoesNotChangeValue()
	{
		$oldClass = config::getConstructionCommandsCallBrokerClass();

		$this->assertThrowsException('\spectrum\Exception', 'should be implement interface', function(){
			config::setConstructionCommandsCallBrokerClass('\stdClass');
		});

		$this->assertSame($oldClass, config::getConstructionCommandsCallBrokerClass());
	}

	public function testSetConstructionCommandsCallBrokerClass_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue()
	{
		$className = $this->createClass('
			final class ... implements \spectrum\constructionCommands\callBrokerInterface
			{
				private function __construct(){}
				static public function __callStatic($constructionCommandName, array $arguments = array()){}
			}
		');
		
		$oldClass = config::getConstructionCommandsCallBrokerClass();
		config::lock();

		$this->assertThrowsException('\spectrum\Exception', '\spectrum\config is locked', function() use($className){
			config::setConstructionCommandsCallBrokerClass($className);
		});

		$this->assertSame($oldClass, config::getConstructionCommandsCallBrokerClass());
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

		$this->assertThrowsException('\spectrum\Exception', 'not exists', function(){
			config::setAssertClass('\spectrum\tests\testHelpers\NotExistsClass');
		});

		$this->assertSame($oldClass, config::getAssertClass());
	}

	public function testSetAssertClass_ClassNotImplementSpectrumInterface_ThrowsExceptionAndDoesNotChangeValue()
	{
		$oldClass = config::getAssertClass();

		$this->assertThrowsException('\spectrum\Exception', 'should be implement interface', function(){
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

	public function testGetMatcherCallDetailsClass_ReturnsSpectrumClassByDefault()
	{
		$this->assertSame('\spectrum\core\MatcherCallDetails', config::getMatcherCallDetailsClass());
	}
	
	public function testGetMatcherCallDetailsClass_ConfigIsLocked_DoesNotThrowException()
	{
		config::lock();
		config::getMatcherCallDetailsClass();
	}

/**/

	public function testSetMatcherCallDetailsClass_SetsNewClass()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\MatcherCallDetailsInterface
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
			}
		');
		
		config::setMatcherCallDetailsClass($className);
		$this->assertSame($className, config::getMatcherCallDetailsClass());
	}

	public function testSetMatcherCallDetailsClass_ClassNotExists_ThrowsExceptionAndDoesNotChangeValue()
	{
		$oldClass = config::getMatcherCallDetailsClass();

		$this->assertThrowsException('\spectrum\Exception', 'not exists', function(){
			config::setMatcherCallDetailsClass('\spectrum\tests\testHelpers\NotExistsClass');
		});

		$this->assertSame($oldClass, config::getMatcherCallDetailsClass());
	}

	public function testSetMatcherCallDetailsClass_ClassNotImplementSpectrumInterface_ThrowsExceptionAndDoesNotChangeValue()
	{
		$oldClass = config::getMatcherCallDetailsClass();

		$this->assertThrowsException('\spectrum\Exception', 'should be implement interface', function(){
			config::setMatcherCallDetailsClass('\stdClass');
		});

		$this->assertSame($oldClass, config::getMatcherCallDetailsClass());
	}

	public function testSetMatcherCallDetailsClass_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\MatcherCallDetailsInterface
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
				
				public function getSpecId(){}
				public function getSpecById($specId){}
			
				public function getParentSpecs(){}
				public function hasParentSpec(\spectrum\core\SpecInterface $spec){}
				public function bindParentSpec(\spectrum\core\SpecInterface $spec){}
				public function unbindParentSpec(\spectrum\core\SpecInterface $spec){}
				public function unbindAllParentSpecs(){}
			
				public function getChildSpecs(){}
				public function getChildSpecsByName($name){}
				public function getChildSpecByNumber($number){}
				public function hasChildSpec(\spectrum\core\SpecInterface $spec){}
				public function bindChildSpec(\spectrum\core\SpecInterface $spec){}
				public function unbindChildSpec(\spectrum\core\SpecInterface $spec){}
				public function unbindAllChildSpecs(){}
				
				public function getRootSpec(){}
				public function getRootSpecs(){}
				public function getRunningParentSpec(){}
				public function getRunningAncestorSpecs(){}
				public function getRunningChildSpec(){}
				public function getDeepestRunningSpec(){}
			
				public function getResultBuffer(){}
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

		$this->assertThrowsException('\spectrum\Exception', 'not exists', function(){
			config::setSpecClass('\spectrum\tests\testHelpers\NotExistsClass');
		});

		$this->assertSame($oldClass, config::getSpecClass());
	}

	public function testSetSpecClass_ClassNotImplementSpectrumInterface_ThrowsExceptionAndDoesNotChangeValue()
	{
		$oldClass = config::getSpecClass();

		$this->assertThrowsException('\spectrum\Exception', 'should be implement interface', function(){
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
				
				public function getSpecId(){}
				public function getSpecById($specId){}
			
				public function getParentSpecs(){}
				public function hasParentSpec(\spectrum\core\SpecInterface $spec){}
				public function bindParentSpec(\spectrum\core\SpecInterface $spec){}
				public function unbindParentSpec(\spectrum\core\SpecInterface $spec){}
				public function unbindAllParentSpecs(){}
			
				public function getChildSpecs(){}
				public function getChildSpecsByName($name){}
				public function getChildSpecByNumber($number){}
				public function hasChildSpec(\spectrum\core\SpecInterface $spec){}
				public function bindChildSpec(\spectrum\core\SpecInterface $spec){}
				public function unbindChildSpec(\spectrum\core\SpecInterface $spec){}
				public function unbindAllChildSpecs(){}
				
				public function getRootSpec(){}
				public function getRootSpecs(){}
				public function getRunningParentSpec(){}
				public function getRunningAncestorSpecs(){}
				public function getRunningChildSpec(){}
				public function getDeepestRunningSpec(){}
			
				public function getResultBuffer(){}
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

	public function testGetContextDataClass_ReturnsSpectrumClassByDefault()
	{
		$this->assertSame('\spectrum\core\plugins\basePlugins\contexts\ContextData', config::getContextDataClass());
	}
	
	public function testGetContextDataClass_ConfigIsLocked_DoesNotThrowException()
	{
		config::lock();
		config::getContextDataClass();
	}

/**/

	public function testSetContextDataClass_SetsNewClass()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\plugins\basePlugins\contexts\ContextDataInterface
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

		$this->assertThrowsException('\spectrum\Exception', 'not exists', function(){
			config::setContextDataClass('\spectrum\tests\testHelpers\NotExistsClass');
		});

		$this->assertSame($oldClass, config::getContextDataClass());
	}

	public function testSetContextDataClass_ClassNotImplementSpectrumInterface_ThrowsExceptionAndDoesNotChangeValue()
	{
		$oldClass = config::getContextDataClass();

		$this->assertThrowsException('\spectrum\Exception', 'should be implement interface', function(){
			config::setContextDataClass('\stdClass');
		});

		$this->assertSame($oldClass, config::getContextDataClass());
	}

	public function testSetContextDataClass_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue()
	{
		$className = $this->createClass('
			class ... implements \spectrum\core\plugins\basePlugins\contexts\ContextDataInterface
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

		$this->assertThrowsException('\spectrum\Exception', 'not exists', function(){
			config::setResultBufferClass('\spectrum\tests\testHelpers\NotExistsClass');
		});

		$this->assertSame($oldClass, config::getResultBufferClass());
	}

	public function testSetResultBufferClass_ClassNotImplementSpectrumInterface_ThrowsExceptionAndDoesNotChangeValue()
	{
		$oldClass = config::getResultBufferClass();

		$this->assertThrowsException('\spectrum\Exception', 'should be implement interface', function(){
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

	public function testGetAllowBaseMatchersOverride_ReturnsFalseByDefault()
	{
		$this->assertFalse(config::getAllowBaseMatchersOverride());
	}
	
	public function testGetAllowBaseMatchersOverride_ConfigIsLocked_DoesNotThrowException()
	{
		config::lock();
		config::getAllowBaseMatchersOverride();
	}

/**/

	public function testSetAllowBaseMatchersOverride_SetsNewValue()
	{
		config::setAllowBaseMatchersOverride(true);
		$this->assertTrue(config::getAllowBaseMatchersOverride());
	}

	public function testSetAllowBaseMatchersOverride_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue()
	{
		config::lock();

		$this->assertThrowsException('\spectrum\Exception', '\spectrum\config is locked', function(){
			config::setAllowBaseMatchersOverride(true);
		});

		$this->assertFalse(config::getAllowBaseMatchersOverride());
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

	public function testSetAllowErrorHandlingModify_SetsNewValue()
	{
		config::setAllowErrorHandlingModify(false);
		$this->assertFalse(config::getAllowErrorHandlingModify());
	}

	public function testSetAllowErrorHandlingModify_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue()
	{
		config::lock();

		$this->assertThrowsException('\spectrum\Exception', '\spectrum\config is locked', function(){
			config::setAllowErrorHandlingModify(false);
		});

		$this->assertTrue(config::getAllowErrorHandlingModify());
	}

/**/

	public function testGetAllowInputEncodingModify_ReturnsTrueByDefault()
	{
		$this->assertTrue(config::getAllowInputEncodingModify());
	}
	
	public function testGetAllowInputEncodingModify_ConfigIsLocked_DoesNotThrowException()
	{
		config::lock();
		config::getAllowInputEncodingModify();
	}

/**/

	public function testSetAllowInputEncodingModify_SetsNewValue()
	{
		config::setAllowInputEncodingModify(false);
		$this->assertFalse(config::getAllowInputEncodingModify());
	}

	public function testSetAllowInputEncodingModify_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue()
	{
		config::lock();

		$this->assertThrowsException('\spectrum\Exception', '\spectrum\config is locked', function(){
			config::setAllowInputEncodingModify(false);
		});

		$this->assertTrue(config::getAllowInputEncodingModify());
	}

/**/

	public function testGetAllowOutputEncodingModify_ReturnsTrueByDefault()
	{
		$this->assertTrue(config::getAllowOutputEncodingModify());
	}
	
	public function testGetAllowOutputEncodingModify_ConfigIsLocked_DoesNotThrowException()
	{
		config::lock();
		config::getAllowOutputEncodingModify();
	}

/**/

	public function testSetAllowOutputEncodingModify_SetsNewValue()
	{
		config::setAllowOutputEncodingModify(false);
		$this->assertFalse(config::getAllowOutputEncodingModify());
	}

	public function testSetAllowOutputEncodingModify_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue()
	{
		config::lock();

		$this->assertThrowsException('\spectrum\Exception', '\spectrum\config is locked', function(){
			config::setAllowOutputEncodingModify(false);
		});

		$this->assertTrue(config::getAllowOutputEncodingModify());
	}
	
/**/
	
	public function testGetAllowReportSettingsModify_ReturnsTrueByDefault()
	{
		$this->assertTrue(config::getAllowReportSettingsModify());
	}
	
	public function testGetAllowReportSettingsModify_ConfigIsLocked_DoesNotThrowException()
	{
		config::lock();
		config::getAllowReportSettingsModify();
	}

/**/

	public function testSetAllowReportSettingsModify_SetsNewValue()
	{
		config::setAllowReportSettingsModify(false);
		$this->assertFalse(config::getAllowReportSettingsModify());
	}

	public function testSetAllowReportSettingsModify_ConfigIsLocked_ThrowsExceptionAndDoesNotChangeValue()
	{
		config::lock();

		$this->assertThrowsException('\spectrum\Exception', '\spectrum\config is locked', function(){
			config::setAllowReportSettingsModify(false);
		});

		$this->assertTrue(config::getAllowReportSettingsModify());
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