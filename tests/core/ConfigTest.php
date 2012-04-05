<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core;
require_once dirname(__FILE__) . '/../init.php';

class ConfigTest extends Test
{
	public function testGetAssertClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\spectrum\core\asserts\Assert', Config::getAssertClass());
	}

/**/

	public function testSetAssertClass_ShouldBeSetNewClass()
	{
		Config::setAssertClass('\spectrum\core\testEnv\emptyStubs\asserts\Assert');
		$this->assertEquals('\spectrum\core\testEnv\emptyStubs\asserts\Assert', Config::getAssertClass());
	}

	public function testSetAssertClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getAssertClass();

		$this->assertThrowException('\spectrum\core\Exception', 'not exists', function(){
			Config::setAssertClass('\spectrum\core\testEnv\emptyStubs\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, Config::getAssertClass());
	}

	public function testSetAssertClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getAssertClass();

		$this->assertThrowException('\spectrum\core\Exception', 'should be implement interface', function(){
			Config::setAssertClass('\stdClass');
		});

		$this->assertEquals($oldClass, Config::getAssertClass());
	}

	public function testSetAssertClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getAssertClass();
		Config::lock();

		$this->assertThrowException('\spectrum\core\Exception', 'Config is locked', function(){
			Config::setAssertClass('\spectrum\core\testEnv\emptyStubs\asserts\Assert');
		});

		$this->assertEquals($oldClass, Config::getAssertClass());
	}

/**/

	public function testGetMatcherCallDetailsClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\spectrum\core\asserts\MatcherCallDetails', Config::getMatcherCallDetailsClass());
	}

/**/

	public function testSetMatcherCallDetailsClass_ShouldBeSetNewClass()
	{
		Config::setMatcherCallDetailsClass('\spectrum\core\testEnv\emptyStubs\asserts\MatcherCallDetails');
		$this->assertEquals('\spectrum\core\testEnv\emptyStubs\asserts\MatcherCallDetails', Config::getMatcherCallDetailsClass());
	}

	public function testSetMatcherCallDetailsClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getMatcherCallDetailsClass();

		$this->assertThrowException('\spectrum\core\Exception', 'not exists', function(){
			Config::setMatcherCallDetailsClass('\spectrum\core\testEnv\emptyStubs\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, Config::getMatcherCallDetailsClass());
	}

	public function testSetMatcherCallDetailsClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getMatcherCallDetailsClass();

		$this->assertThrowException('\spectrum\core\Exception', 'should be implement interface', function(){
			Config::setMatcherCallDetailsClass('\stdClass');
		});

		$this->assertEquals($oldClass, Config::getMatcherCallDetailsClass());
	}

	public function testSetMatcherCallDetailsClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getMatcherCallDetailsClass();
		Config::lock();

		$this->assertThrowException('\spectrum\core\Exception', 'Config is locked', function(){
			Config::setMatcherCallDetailsClass('\spectrum\core\testEnv\emptyStubs\asserts\MatcherCallDetails');
		});

		$this->assertEquals($oldClass, Config::getMatcherCallDetailsClass());
	}

/**/

	public function testGetPluginsManagerClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\spectrum\core\plugins\Manager', Config::getPluginsManagerClass());
	}

/**/

	public function testSetPluginsManagerClass_ShouldBeSetNewClass()
	{
		Config::setPluginsManagerClass('\spectrum\core\testEnv\emptyStubs\plugins\Manager');
		$this->assertEquals('\spectrum\core\testEnv\emptyStubs\plugins\Manager', Config::getPluginsManagerClass());
	}

	public function testSetPluginsManagerClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getPluginsManagerClass();

		$this->assertThrowException('\spectrum\core\Exception', 'not exists', function(){
			Config::setPluginsManagerClass('\spectrum\core\testEnv\emptyStubs\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, Config::getPluginsManagerClass());
	}

	public function testSetPluginsManagerClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getPluginsManagerClass();

		$this->assertThrowException('\spectrum\core\Exception', 'should be implement interface', function(){
			Config::setPluginsManagerClass('\stdClass');
		});

		$this->assertEquals($oldClass, Config::getPluginsManagerClass());
	}

	public function testSetPluginsManagerClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getPluginsManagerClass();
		Config::lock();

		$this->assertThrowException('\spectrum\core\Exception', 'Config is locked', function(){
			Config::setPluginsManagerClass('\spectrum\core\testEnv\emptyStubs\plugins\Manager');
		});

		$this->assertEquals($oldClass, Config::getPluginsManagerClass());
	}
/**/

	public function testGetRegistryClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\spectrum\core\Registry', Config::getRegistryClass());
	}

/**/

	public function testSetRegistryClass_ShouldBeSetNewClass()
	{
		Config::setRegistryClass('\spectrum\core\testEnv\emptyStubs\Registry');
		$this->assertEquals('\spectrum\core\testEnv\emptyStubs\Registry', Config::getRegistryClass());
	}

	public function testSetRegistryClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getRegistryClass();

		$this->assertThrowException('\spectrum\core\Exception', 'not exists', function(){
			Config::setRegistryClass('\spectrum\core\testEnv\emptyStubs\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, Config::getRegistryClass());
	}

	public function testSetRegistryClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getRegistryClass();

		$this->assertThrowException('\spectrum\core\Exception', 'should be implement interface', function(){
			Config::setRegistryClass('\stdClass');
		});

		$this->assertEquals($oldClass, Config::getRegistryClass());
	}

	public function testSetRegistryClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getRegistryClass();
		Config::lock();

		$this->assertThrowException('\spectrum\core\Exception', 'Config is locked', function(){
			Config::setRegistryClass('\spectrum\core\testEnv\emptyStubs\Registry');
		});

		$this->assertEquals($oldClass, Config::getRegistryClass());
	}
	
/**/

	public function testGetRunResultsBufferClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\spectrum\core\RunResultsBuffer', Config::getRunResultsBufferClass());
	}

/**/

	public function testSetRunResultsBufferClass_ShouldBeSetNewClass()
	{
		Config::setRunResultsBufferClass('\spectrum\core\testEnv\emptyStubs\RunResultsBuffer');
		$this->assertEquals('\spectrum\core\testEnv\emptyStubs\RunResultsBuffer', Config::getRunResultsBufferClass());
	}

	public function testSetRunResultsBufferClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getRunResultsBufferClass();

		$this->assertThrowException('\spectrum\core\Exception', 'not exists', function(){
			Config::setRunResultsBufferClass('\spectrum\core\testEnv\emptyStubs\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, Config::getRunResultsBufferClass());
	}

	public function testSetRunResultsBufferClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getRunResultsBufferClass();

		$this->assertThrowException('\spectrum\core\Exception', 'should be implement interface', function(){
			Config::setRunResultsBufferClass('\stdClass');
		});

		$this->assertEquals($oldClass, Config::getRunResultsBufferClass());
	}

	public function testSetRunResultsBufferClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getRunResultsBufferClass();
		Config::lock();

		$this->assertThrowException('\spectrum\core\Exception', 'Config is locked', function(){
			Config::setRunResultsBufferClass('\spectrum\core\testEnv\emptyStubs\RunResultsBuffer');
		});

		$this->assertEquals($oldClass, Config::getRunResultsBufferClass());
	}

/**/

	public function testGetSpecContainerContextClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\spectrum\core\SpecContainerContext', Config::getSpecContainerContextClass());
	}

/**/

	public function testSetSpecContainerContextClass_ShouldBeSetNewClass()
	{
		Config::setSpecContainerContextClass('\spectrum\core\testEnv\emptyStubs\SpecContainerContext');
		$this->assertEquals('\spectrum\core\testEnv\emptyStubs\SpecContainerContext', Config::getSpecContainerContextClass());
	}

	public function testSetSpecContainerContextClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecContainerContextClass();

		$this->assertThrowException('\spectrum\core\Exception', 'not exists', function(){
			Config::setSpecContainerContextClass('\spectrum\core\testEnv\emptyStubs\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, Config::getSpecContainerContextClass());
	}

	public function testSetSpecContainerContextClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecContainerContextClass();

		$this->assertThrowException('\spectrum\core\Exception', 'should be implement interface', function(){
			Config::setSpecContainerContextClass('\stdClass');
		});

		$this->assertEquals($oldClass, Config::getSpecContainerContextClass());
	}

	public function testSetSpecContainerContextClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecContainerContextClass();
		Config::lock();

		$this->assertThrowException('\spectrum\core\Exception', 'Config is locked', function(){
			Config::setSpecContainerContextClass('\spectrum\core\testEnv\emptyStubs\SpecContainerContext');
		});

		$this->assertEquals($oldClass, Config::getSpecContainerContextClass());
	}

/**/

	public function testGetSpecContainerArgumentsProviderClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\spectrum\core\SpecContainerArgumentsProvider', Config::getSpecContainerArgumentsProviderClass());
	}

/**/

	public function testSetSpecContainerArgumentsProviderClass_ShouldBeSetNewClass()
	{
		Config::setSpecContainerArgumentsProviderClass('\spectrum\core\testEnv\emptyStubs\SpecContainerArgumentsProvider');
		$this->assertEquals('\spectrum\core\testEnv\emptyStubs\SpecContainerArgumentsProvider', Config::getSpecContainerArgumentsProviderClass());
	}

	public function testSetSpecContainerArgumentsProviderClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecContainerArgumentsProviderClass();

		$this->assertThrowException('\spectrum\core\Exception', 'not exists', function(){
			Config::setSpecContainerArgumentsProviderClass('\spectrum\core\testEnv\emptyStubs\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, Config::getSpecContainerArgumentsProviderClass());
	}

	public function testSetSpecContainerArgumentsProviderClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecContainerArgumentsProviderClass();

		$this->assertThrowException('\spectrum\core\Exception', 'should be implement interface', function(){
			Config::setSpecContainerArgumentsProviderClass('\stdClass');
		});

		$this->assertEquals($oldClass, Config::getSpecContainerArgumentsProviderClass());
	}

	public function testSetSpecContainerArgumentsProviderClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecContainerArgumentsProviderClass();
		Config::lock();

		$this->assertThrowException('\spectrum\core\Exception', 'Config is locked', function(){
			Config::setSpecContainerArgumentsProviderClass('\spectrum\core\testEnv\emptyStubs\SpecContainerArgumentsProvider');
		});

		$this->assertEquals($oldClass, Config::getSpecContainerArgumentsProviderClass());
	}

/**/

	public function testGetSpecContainerDescribeClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\spectrum\core\SpecContainerDescribe', Config::getSpecContainerDescribeClass());
	}

/**/

	public function testSetSpecContainerDescribeClass_ShouldBeSetNewClass()
	{
		Config::setSpecContainerDescribeClass('\spectrum\core\testEnv\emptyStubs\SpecContainerDescribe');
		$this->assertEquals('\spectrum\core\testEnv\emptyStubs\SpecContainerDescribe', Config::getSpecContainerDescribeClass());
	}

	public function testSetSpecContainerDescribeClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecContainerDescribeClass();

		$this->assertThrowException('\spectrum\core\Exception', 'not exists', function(){
			Config::setSpecContainerDescribeClass('\spectrum\core\testEnv\emptyStubs\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, Config::getSpecContainerDescribeClass());
	}

	public function testSetSpecContainerDescribeClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecContainerDescribeClass();

		$this->assertThrowException('\spectrum\core\Exception', 'should be implement interface', function(){
			Config::setSpecContainerDescribeClass('\stdClass');
		});

		$this->assertEquals($oldClass, Config::getSpecContainerDescribeClass());
	}

	public function testSetSpecContainerDescribeClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecContainerDescribeClass();
		Config::lock();

		$this->assertThrowException('\spectrum\core\Exception', 'Config is locked', function(){
			Config::setSpecContainerDescribeClass('\spectrum\core\testEnv\emptyStubs\SpecContainerDescribe');
		});

		$this->assertEquals($oldClass, Config::getSpecContainerDescribeClass());
	}
	
	
/**/

	public function testGetSpecContainerPatternClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\spectrum\core\SpecContainerPattern', Config::getSpecContainerPatternClass());
	}

/**/

	public function testSetSpecContainerPatternClass_ShouldBeSetNewClass()
	{
		Config::setSpecContainerPatternClass('\spectrum\core\testEnv\emptyStubs\SpecContainerPattern');
		$this->assertEquals('\spectrum\core\testEnv\emptyStubs\SpecContainerPattern', Config::getSpecContainerPatternClass());
	}

	public function testSetSpecContainerPatternClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecContainerPatternClass();

		$this->assertThrowException('\spectrum\core\Exception', 'not exists', function(){
			Config::setSpecContainerPatternClass('\spectrum\core\testEnv\emptyStubs\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, Config::getSpecContainerPatternClass());
	}

	public function testSetSpecContainerPatternClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecContainerPatternClass();

		$this->assertThrowException('\spectrum\core\Exception', 'should be implement interface', function(){
			Config::setSpecContainerPatternClass('\stdClass');
		});

		$this->assertEquals($oldClass, Config::getSpecContainerPatternClass());
	}

	public function testSetSpecContainerPatternClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecContainerPatternClass();
		Config::lock();

		$this->assertThrowException('\spectrum\core\Exception', 'Config is locked', function(){
			Config::setSpecContainerPatternClass('\spectrum\core\testEnv\emptyStubs\SpecContainerPattern');
		});

		$this->assertEquals($oldClass, Config::getSpecContainerPatternClass());
	}

/**/

	public function testGetSpecItemItClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\spectrum\core\SpecItemIt', Config::getSpecItemItClass());
	}

/**/

	public function testSetSpecItemItClass_ShouldBeSetNewClass()
	{
		Config::setSpecItemItClass('\spectrum\core\testEnv\emptyStubs\SpecItemIt');
		$this->assertEquals('\spectrum\core\testEnv\emptyStubs\SpecItemIt', Config::getSpecItemItClass());
	}

	public function testSetSpecItemItClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecItemItClass();

		$this->assertThrowException('\spectrum\core\Exception', 'not exists', function(){
			Config::setSpecItemItClass('\spectrum\core\testEnv\emptyStubs\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, Config::getSpecItemItClass());
	}

	public function testSetSpecItemItClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecItemItClass();

		$this->assertThrowException('\spectrum\core\Exception', 'should be implement interface', function(){
			Config::setSpecItemItClass('\stdClass');
		});

		$this->assertEquals($oldClass, Config::getSpecItemItClass());
	}

	public function testSetSpecItemItClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecItemItClass();
		Config::lock();

		$this->assertThrowException('\spectrum\core\Exception', 'Config is locked', function(){
			Config::setSpecItemItClass('\spectrum\core\testEnv\emptyStubs\SpecItemIt');
		});

		$this->assertEquals($oldClass, Config::getSpecItemItClass());
	}

/**/

	public function testGetWorldClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\spectrum\core\World', Config::getWorldClass());
	}

/**/

	public function testSetWorldClass_ShouldBeSetNewClass()
	{
		Config::setWorldClass('\spectrum\core\testEnv\emptyStubs\World');
		$this->assertEquals('\spectrum\core\testEnv\emptyStubs\World', Config::getWorldClass());
	}

	public function testSetWorldClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getWorldClass();

		$this->assertThrowException('\spectrum\core\Exception', 'not exists', function(){
			Config::setWorldClass('\spectrum\core\testEnv\emptyStubs\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, Config::getWorldClass());
	}

	public function testSetWorldClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getSpecItemItClass();

		$this->assertThrowException('\spectrum\core\Exception', 'should be implement interface', function(){
			Config::setSpecItemItClass('\stdClass');
		});

		$this->assertEquals($oldClass, Config::getSpecItemItClass());
	}

	public function testSetWorldClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getWorldClass();
		Config::lock();

		$this->assertThrowException('\spectrum\core\Exception', 'core\Config is locked', function(){
			Config::setWorldClass('\spectrum\core\testEnv\emptyStubs\World');
		});

		$this->assertEquals($oldClass, Config::getWorldClass());
	}

/**/

	public function testGetAllowPluginsRegistration_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(Config::getAllowPluginsRegistration());
	}

/**/

	public function testSetAllowPluginsRegistration_ShouldBeSetNewValue()
	{
		Config::setAllowPluginsRegistration(false);
		$this->assertFalse(Config::getAllowPluginsRegistration());
	}

	public function testSetAllowPluginsRegistration_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		Config::lock();

		$this->assertThrowException('\spectrum\core\Exception', 'core\Config is locked', function(){
			Config::setAllowPluginsRegistration(false);
		});

		$this->assertTrue(Config::getAllowPluginsRegistration());
	}

/**/

	public function testGetAllowPluginsOverride_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(Config::getAllowPluginsOverride());
	}

/**/

	public function testSetAllowPluginsOverride_ShouldBeSetNewValue()
	{
		Config::setAllowPluginsOverride(false);
		$this->assertFalse(Config::getAllowPluginsOverride());
	}

	public function testSetAllowPluginsOverride_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		Config::lock();

		$this->assertThrowException('\spectrum\core\Exception', 'core\Config is locked', function(){
			Config::setAllowPluginsOverride(false);
		});

		$this->assertTrue(Config::getAllowPluginsOverride());
	}

/**/

	public function testGetAllowMatchersAdd_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(Config::getAllowMatchersAdd());
	}

/**/

	public function testSetAllowMatchersAdd_ShouldBeSetNewValue()
	{
		Config::setAllowMatchersAdd(false);
		$this->assertFalse(Config::getAllowMatchersAdd());
	}

	public function testSetAllowMatchersAdd_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		Config::lock();

		$this->assertThrowException('\spectrum\core\Exception', 'core\Config is locked', function(){
			Config::setAllowMatchersAdd(false);
		});

		$this->assertTrue(Config::getAllowMatchersAdd());
	}

/**/

	public function testGetAllowMatchersOverride_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(Config::getAllowMatchersOverride());
	}

/**/

	public function testSetAllowMatchersOverride_ShouldBeSetNewValue()
	{
		Config::setAllowMatchersOverride(false);
		$this->assertFalse(Config::getAllowMatchersOverride());
	}

	public function testSetAllowMatchersOverride_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		Config::lock();

		$this->assertThrowException('\spectrum\core\Exception', 'core\Config is locked', function(){
			Config::setAllowMatchersOverride(false);
		});

		$this->assertTrue(Config::getAllowMatchersOverride());
	}

/**/

	public function testGetAllowErrorHandlingModify_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(Config::getAllowErrorHandlingModify());
	}

/**/

	public function testSetAllowErrorHandlingModify_ShouldBeSetNewValue()
	{
		Config::setAllowErrorHandlingModify(false);
		$this->assertFalse(Config::getAllowErrorHandlingModify());
	}

	public function testSetAllowErrorHandlingModify_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		Config::lock();

		$this->assertThrowException('\spectrum\core\Exception', 'core\Config is locked', function(){
			Config::setAllowErrorHandlingModify(false);
		});

		$this->assertTrue(Config::getAllowErrorHandlingModify());
	}

/**/

	public function testGetAllowInputEncodingModify_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(Config::getAllowInputEncodingModify());
	}

/**/

	public function testSetAllowInputEncodingModify_ShouldBeSetNewValue()
	{
		Config::setAllowInputEncodingModify(false);
		$this->assertFalse(Config::getAllowInputEncodingModify());
	}

	public function testSetAllowInputEncodingModify_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		Config::lock();

		$this->assertThrowException('\spectrum\core\Exception', 'core\Config is locked', function(){
			Config::setAllowInputEncodingModify(false);
		});

		$this->assertTrue(Config::getAllowInputEncodingModify());
	}

/**/

	public function testGetAllowOutputEncodingModify_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(Config::getAllowOutputEncodingModify());
	}

/**/

	public function testSetAllowOutputEncodingModify_ShouldBeSetNewValue()
	{
		Config::setAllowOutputEncodingModify(false);
		$this->assertFalse(Config::getAllowOutputEncodingModify());
	}

	public function testSetAllowOutputEncodingModify_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		Config::lock();

		$this->assertThrowException('\spectrum\core\Exception', 'core\Config is locked', function(){
			Config::setAllowOutputEncodingModify(false);
		});

		$this->assertTrue(Config::getAllowOutputEncodingModify());
	}
	

/**/

	public function testGetAllowSpecsModifyWhenRunning_ShouldBeReturnFalseByDefault()
	{
		$this->assertFalse(Config::getAllowSpecsModifyWhenRunning());
	}

/**/

	public function testSetAllowSpecsModifyWhenRunning_ShouldBeSetNewValue()
	{
		Config::setAllowSpecsModifyWhenRunning(true);
		$this->assertTrue(Config::getAllowSpecsModifyWhenRunning());
	}

	public function testSetAllowSpecsModifyWhenRunning_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		Config::lock();

		$this->assertThrowException('\spectrum\core\Exception', 'core\Config is locked', function(){
			Config::setAllowSpecsModifyWhenRunning(true);
		});

		$this->assertFalse(Config::getAllowSpecsModifyWhenRunning());
	}
}