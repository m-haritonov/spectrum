<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands;
require_once dirname(__FILE__) . '/../init.php';

class ConfigTest extends \spectrum\Test
{
	public function testGetManagerClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\spectrum\constructionCommands\Manager', Config::getManagerClass());
	}

/**/

	public function testSetManagerClass_ShouldBeSetNewClass()
	{
		Config::setManagerClass('\spectrum\constructionCommands\testEnv\emptyStubs\Manager');
		$this->assertEquals('\spectrum\constructionCommands\testEnv\emptyStubs\Manager', Config::getManagerClass());
	}

	public function testSetManagerClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getManagerClass();

		$this->assertThrowException('\spectrum\constructionCommands\Exception', 'not exists', function(){
			Config::setManagerClass('\spectrum\constructionCommands\testEnv\emptyStubs\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, Config::getManagerClass());
	}

	public function testSetManagerClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getManagerClass();

		$this->assertThrowException('\spectrum\constructionCommands\Exception', 'should be implement interface', function(){
			Config::setManagerClass('\stdClass');
		});

		$this->assertEquals($oldClass, Config::getManagerClass());
	}

	public function testSetManagerClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getManagerClass();
		Config::lock();

		$this->assertThrowException('\spectrum\constructionCommands\Exception', 'constructionCommands\Config is locked', function(){
			Config::setManagerClass('\spectrum\constructionCommands\testEnv\emptyStubs\Manager');
		});

		$this->assertEquals($oldClass, Config::getManagerClass());
	}
	
/**/

	public function testGetAllowConstructionCommandsRegistration_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(Config::getAllowConstructionCommandsRegistration());
	}

/**/

	public function testSetAllowConstructionCommandsRegistration_ShouldBeSetNewValue()
	{
		Config::setAllowConstructionCommandsRegistration(false);
		$this->assertFalse(Config::getAllowConstructionCommandsRegistration());
	}

	public function testSetAllowConstructionCommandsRegistration_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		Config::lock();

		$this->assertThrowException('\spectrum\constructionCommands\Exception', 'constructionCommands\Config is locked', function(){
			Config::setAllowConstructionCommandsRegistration(false);
		});

		$this->assertEquals(true, Config::getAllowConstructionCommandsRegistration());
	}
	
/**/

	public function testGetAllowConstructionCommandsOverride_ShouldBeReturnTrueByDefault()
	{
		$this->assertTrue(Config::getAllowConstructionCommandsOverride());
	}

/**/

	public function testSetAllowConstructionCommandsOverride_ShouldBeSetNewValue()
	{
		Config::setAllowConstructionCommandsOverride(false);
		$this->assertFalse(Config::getAllowConstructionCommandsOverride());
	}

	public function testSetAllowConstructionCommandsOverride_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		Config::lock();

		$this->assertThrowException('\spectrum\constructionCommands\Exception', 'constructionCommands\Config is locked', function(){
			Config::setAllowConstructionCommandsOverride(false);
		});

		$this->assertEquals(true, Config::getAllowConstructionCommandsOverride());
	}
}