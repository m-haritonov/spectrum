<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core;
use spectrum\config;

require_once __DIR__ . '/../init.php';

class ConfigTest extends Test
{
	public function testGetAssertClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\spectrum\core\asserts\Assert', config::getAssertClass());
	}

/**/

	public function testSetAssertClass_ShouldBeSetNewClass()
	{
		config::setAssertClass('\spectrum\tests\testHelpers\emptyStubs\core\asserts\Assert');
		$this->assertEquals('\spectrum\tests\testHelpers\emptyStubs\core\asserts\Assert', config::getAssertClass());
	}

	public function testSetAssertClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = config::getAssertClass();

		$this->assertThrowsException('\spectrum\core\Exception', 'not exists', function(){
			config::setAssertClass('\spectrum\tests\testHelpers\emptyStubs\core\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, config::getAssertClass());
	}

	public function testSetAssertClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = config::getAssertClass();

		$this->assertThrowsException('\spectrum\core\Exception', 'should be implement interface', function(){
			config::setAssertClass('\stdClass');
		});

		$this->assertEquals($oldClass, config::getAssertClass());
	}

	public function testSetAssertClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = config::getAssertClass();
		config::lock();

		$this->assertThrowsException('\spectrum\core\Exception', 'Config is locked', function(){
			config::setAssertClass('\spectrum\tests\testHelpers\emptyStubs\core\asserts\Assert');
		});

		$this->assertEquals($oldClass, config::getAssertClass());
	}

/**/

	public function testGetAssertCallDetailsClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\spectrum\core\asserts\CallDetails', config::getAssertCallDetailsClass());
	}

/**/

	public function testSetAssertCallDetailsClass_ShouldBeSetNewClass()
	{
		config::setAssertCallDetailsClass('\spectrum\tests\testHelpers\emptyStubs\core\asserts\CallDetails');
		$this->assertEquals('\spectrum\tests\testHelpers\emptyStubs\core\asserts\CallDetails', config::getAssertCallDetailsClass());
	}

	public function testSetAssertCallDetailsClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = config::getAssertCallDetailsClass();

		$this->assertThrowsException('\spectrum\core\Exception', 'not exists', function(){
			config::setAssertCallDetailsClass('\spectrum\tests\testHelpers\emptyStubs\core\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, config::getAssertCallDetailsClass());
	}

	public function testSetAssertCallDetailsClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = config::getAssertCallDetailsClass();

		$this->assertThrowsException('\spectrum\core\Exception', 'should be implement interface', function(){
			config::setAssertCallDetailsClass('\stdClass');
		});

		$this->assertEquals($oldClass, config::getAssertCallDetailsClass());
	}

	public function testSetAssertCallDetailsClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = config::getAssertCallDetailsClass();
		config::lock();

		$this->assertThrowsException('\spectrum\core\Exception', 'Config is locked', function(){
			config::setAssertCallDetailsClass('\spectrum\tests\testHelpers\emptyStubs\core\asserts\CallDetails');
		});

		$this->assertEquals($oldClass, config::getAssertCallDetailsClass());
	}

/**/

	public function testGetResultBufferClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\spectrum\core\ResultBuffer', config::getResultBufferClass());
	}

/**/

	public function testSetResultBufferClass_ShouldBeSetNewClass()
	{
		config::setResultBufferClass('\spectrum\tests\testHelpers\emptyStubs\core\ResultBuffer');
		$this->assertEquals('\spectrum\tests\testHelpers\emptyStubs\core\ResultBuffer', config::getResultBufferClass());
	}

	public function testSetResultBufferClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = config::getResultBufferClass();

		$this->assertThrowsException('\spectrum\core\Exception', 'not exists', function(){
			config::setResultBufferClass('\spectrum\tests\testHelpers\emptyStubs\core\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, config::getResultBufferClass());
	}

	public function testSetResultBufferClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = config::getResultBufferClass();

		$this->assertThrowsException('\spectrum\core\Exception', 'should be implement interface', function(){
			config::setResultBufferClass('\stdClass');
		});

		$this->assertEquals($oldClass, config::getResultBufferClass());
	}

	public function testSetResultBufferClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = config::getResultBufferClass();
		config::lock();

		$this->assertThrowsException('\spectrum\core\Exception', 'Config is locked', function(){
			config::setResultBufferClass('\spectrum\tests\testHelpers\emptyStubs\core\ResultBuffer');
		});

		$this->assertEquals($oldClass, config::getResultBufferClass());
	}

/**/

	public function testGetSpecClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\spectrum\core\Spec', config::getSpecClass());
	}

/**/

	public function testSetSpecClass_ShouldBeSetNewClass()
	{
		config::setSpecClass('\spectrum\tests\testHelpers\emptyStubs\core\Spec');
		$this->assertEquals('\spectrum\tests\testHelpers\emptyStubs\core\Spec', config::getSpecClass());
	}

	public function testSetSpecClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = config::getSpecClass();

		$this->assertThrowsException('\spectrum\core\Exception', 'not exists', function(){
			config::setSpecClass('\spectrum\tests\testHelpers\emptyStubs\core\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, config::getSpecClass());
	}

	public function testSetSpecClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = config::getSpecClass();

		$this->assertThrowsException('\spectrum\core\Exception', 'should be implement interface', function(){
			config::setSpecClass('\stdClass');
		});

		$this->assertEquals($oldClass, config::getSpecClass());
	}

	public function testSetSpecClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = config::getSpecClass();
		config::lock();

		$this->assertThrowsException('\spectrum\core\Exception', 'Config is locked', function(){
			config::setSpecClass('\spectrum\tests\testHelpers\emptyStubs\core\Spec');
		});

		$this->assertEquals($oldClass, config::getSpecClass());
	}

/**/

	public function testGetContextDataClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\spectrum\core\Context', config::getContextDataClass());
	}

/**/

	public function testSetContextDataClass_ShouldBeSetNewClass()
	{
		config::setContextDataClass('\spectrum\tests\testHelpers\emptyStubs\core\Context');
		$this->assertEquals('\spectrum\tests\testHelpers\emptyStubs\core\Context', config::getContextDataClass());
	}

	public function testSetContextDataClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = config::getContextDataClass();

		$this->assertThrowsException('\spectrum\core\Exception', 'not exists', function(){
			config::setContextDataClass('\spectrum\tests\testHelpers\emptyStubs\core\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, config::getContextDataClass());
	}

	public function testSetContextDataClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = config::getContextDataClass();

		$this->assertThrowsException('\spectrum\core\Exception', 'should be implement interface', function(){
			config::setContextDataClass('\stdClass');
		});

		$this->assertEquals($oldClass, config::getContextDataClass());
	}

	public function testSetContextDataClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = config::getContextDataClass();
		config::lock();

		$this->assertThrowsException('\spectrum\core\Exception', 'Config is locked', function(){
			config::setContextDataClass('\spectrum\tests\testHelpers\emptyStubs\core\Context');
		});

		$this->assertEquals($oldClass, config::getContextDataClass());
	}

/**/

	public function testGetAllowPluginsRegistration_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(config::getAllowSpecPluginsRegistration());
	}

/**/

	public function testSetAllowPluginsRegistration_ShouldBeSetNewValue()
	{
		config::setAllowSpecPluginsRegistration(false);
		$this->assertFalse(config::getAllowSpecPluginsRegistration());
	}

	public function testSetAllowPluginsRegistration_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		config::lock();

		$this->assertThrowsException('\spectrum\core\Exception', 'Config is locked', function(){
			config::setAllowSpecPluginsRegistration(false);
		});

		$this->assertTrue(config::getAllowSpecPluginsRegistration());
	}

/**/

	public function testGetAllowPluginsOverride_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(config::getAllowSpecPluginsOverride());
	}

/**/

	public function testSetAllowPluginsOverride_ShouldBeSetNewValue()
	{
		config::setAllowSpecPluginsOverride(false);
		$this->assertFalse(config::getAllowSpecPluginsOverride());
	}

	public function testSetAllowPluginsOverride_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		config::lock();

		$this->assertThrowsException('\spectrum\core\Exception', 'Config is locked', function(){
			config::setAllowSpecPluginsOverride(false);
		});

		$this->assertTrue(config::getAllowSpecPluginsOverride());
	}

/**/

	public function testGetAllowMatchersAdd_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(config::getAllowMatchersAdd());
	}

/**/

	public function testSetAllowMatchersAdd_ShouldBeSetNewValue()
	{
		config::setAllowMatchersAdd(false);
		$this->assertFalse(config::getAllowMatchersAdd());
	}

	public function testSetAllowMatchersAdd_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		config::lock();

		$this->assertThrowsException('\spectrum\core\Exception', 'Config is locked', function(){
			config::setAllowMatchersAdd(false);
		});

		$this->assertTrue(config::getAllowMatchersAdd());
	}

/**/

	public function testGetAllowBaseMatchersOverride_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(config::getAllowBaseMatchersOverride());
	}

/**/

	public function testSetAllowBaseMatchersOverride_ShouldBeSetNewValue()
	{
		config::setAllowBaseMatchersOverride(false);
		$this->assertFalse(config::getAllowBaseMatchersOverride());
	}

	public function testSetAllowBaseMatchersOverride_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		config::lock();

		$this->assertThrowsException('\spectrum\core\Exception', 'Config is locked', function(){
			config::setAllowBaseMatchersOverride(false);
		});

		$this->assertTrue(config::getAllowBaseMatchersOverride());
	}

/**/

	public function testGetAllowErrorHandlingModify_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(config::getAllowErrorHandlingModify());
	}

/**/

	public function testSetAllowErrorHandlingModify_ShouldBeSetNewValue()
	{
		config::setAllowErrorHandlingModify(false);
		$this->assertFalse(config::getAllowErrorHandlingModify());
	}

	public function testSetAllowErrorHandlingModify_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		config::lock();

		$this->assertThrowsException('\spectrum\core\Exception', 'Config is locked', function(){
			config::setAllowErrorHandlingModify(false);
		});

		$this->assertTrue(config::getAllowErrorHandlingModify());
	}

/**/

	public function testGetAllowInputEncodingModify_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(config::getAllowInputEncodingModify());
	}

/**/

	public function testSetAllowInputEncodingModify_ShouldBeSetNewValue()
	{
		config::setAllowInputEncodingModify(false);
		$this->assertFalse(config::getAllowInputEncodingModify());
	}

	public function testSetAllowInputEncodingModify_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		config::lock();

		$this->assertThrowsException('\spectrum\core\Exception', 'Config is locked', function(){
			config::setAllowInputEncodingModify(false);
		});

		$this->assertTrue(config::getAllowInputEncodingModify());
	}

/**/

	public function testGetAllowOutputEncodingModify_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(config::getAllowOutputEncodingModify());
	}

/**/

	public function testSetAllowOutputEncodingModify_ShouldBeSetNewValue()
	{
		config::setAllowOutputEncodingModify(false);
		$this->assertFalse(config::getAllowOutputEncodingModify());
	}

	public function testSetAllowOutputEncodingModify_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		config::lock();

		$this->assertThrowsException('\spectrum\core\Exception', 'Config is locked', function(){
			config::setAllowOutputEncodingModify(false);
		});

		$this->assertTrue(config::getAllowOutputEncodingModify());
	}
	
/**/
	
	public function testGetAllowReportsSettingsModify_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(config::getAllowReportsSettingsModify());
	}

/**/

	public function testSetAllowReportsSettingsModify_ShouldBeSetNewValue()
	{
		config::setAllowReportsSettingsModify(false);
		$this->assertFalse(config::getAllowReportsSettingsModify());
	}

	public function testSetAllowReportsSettingsModify_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		config::lock();

		$this->assertThrowsException('\spectrum\core\Exception', 'Config is locked', function(){
			config::setAllowReportsSettingsModify(false);
		});

		$this->assertTrue(config::getAllowReportsSettingsModify());
	}	

/**/

	public function testGetAllowSpecsModifyWhenRunning_ShouldBeReturnFalseByDefault()
	{
		$this->assertFalse(config::getAllowSpecsModifyWhenRunning());
	}

/**/

	public function testSetAllowSpecsModifyWhenRunning_ShouldBeSetNewValue()
	{
		config::setAllowSpecsModifyWhenRunning(true);
		$this->assertTrue(config::getAllowSpecsModifyWhenRunning());
	}

	public function testSetAllowSpecsModifyWhenRunning_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		config::lock();

		$this->assertThrowsException('\spectrum\core\Exception', 'Config is locked', function(){
			config::setAllowSpecsModifyWhenRunning(true);
		});

		$this->assertFalse(config::getAllowSpecsModifyWhenRunning());
	}
}