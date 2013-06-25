<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\constructionCommands;
require_once __DIR__ . '/../init.php';

class ConfigTest extends \spectrum\tests\Test
{
	public function testGetManagerClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\spectrum\constructionCommands\manager', config::getManagerClass());
	}

/**/

	public function testSetManagerClass_ShouldBeSetNewClass()
	{
		config::setManagerClass('\spectrum\constructionCommands\testEnv\emptyStubs\manager');
		$this->assertEquals('\spectrum\constructionCommands\testEnv\emptyStubs\manager', config::getManagerClass());
	}

	public function testSetManagerClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = config::getManagerClass();

		$this->assertThrowException('\spectrum\constructionCommands\Exception', 'not exists', function(){
			config::setManagerClass('\spectrum\constructionCommands\testEnv\emptyStubs\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, config::getManagerClass());
	}

	public function testSetManagerClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = config::getManagerClass();

		$this->assertThrowException('\spectrum\constructionCommands\Exception', 'should be implement interface', function(){
			config::setManagerClass('\stdClass');
		});

		$this->assertEquals($oldClass, config::getManagerClass());
	}

	public function testSetManagerClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = config::getManagerClass();
		config::lock();

		$this->assertThrowException('\spectrum\constructionCommands\Exception', 'constructionCommands\Config is locked', function(){
			config::setManagerClass('\spectrum\constructionCommands\testEnv\emptyStubs\manager');
		});

		$this->assertEquals($oldClass, config::getManagerClass());
	}
	
/**/

	public function testGetAllowConstructionCommandsRegistration_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(config::getAllowConstructionCommandsRegistration());
	}

/**/

	public function testSetAllowConstructionCommandsRegistration_ShouldBeSetNewValue()
	{
		config::setAllowConstructionCommandsRegistration(false);
		$this->assertFalse(config::getAllowConstructionCommandsRegistration());
	}

	public function testSetAllowConstructionCommandsRegistration_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		config::lock();

		$this->assertThrowException('\spectrum\constructionCommands\Exception', 'constructionCommands\Config is locked', function(){
			config::setAllowConstructionCommandsRegistration(false);
		});

		$this->assertEquals(true, config::getAllowConstructionCommandsRegistration());
	}
	
/**/

	public function testGetAllowConstructionCommandsOverride_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(config::getAllowConstructionCommandsOverride());
	}

/**/

	public function testSetAllowConstructionCommandsOverride_ShouldBeSetNewValue()
	{
		config::setAllowConstructionCommandsOverride(false);
		$this->assertFalse(config::getAllowConstructionCommandsOverride());
	}

	public function testSetAllowConstructionCommandsOverride_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		config::lock();

		$this->assertThrowException('\spectrum\constructionCommands\Exception', 'constructionCommands\Config is locked', function(){
			config::setAllowConstructionCommandsOverride(false);
		});

		$this->assertEquals(true, config::getAllowConstructionCommandsOverride());
	}
}