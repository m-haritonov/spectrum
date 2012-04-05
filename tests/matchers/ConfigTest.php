<?php
/*
 * Spectrum
 *
 * Copyright (c) 2011 Mikhail Kharitonov <mvkharitonov@gmail.com>
 * All rights reserved.
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 */

namespace spectrum\matchers;
require_once dirname(__FILE__) . '/../init.php';

class ConfigTest extends Test
{
	public function testGetManagerClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\spectrum\matchers\Manager', Config::getManagerClass());
	}

/**/

	public function testSetManagerClass_ShouldBeSetNewClass()
	{
		Config::setManagerClass('\spectrum\matchers\testEnv\emptyStubs\Manager');
		$this->assertEquals('\spectrum\matchers\testEnv\emptyStubs\Manager', Config::getManagerClass());
	}

	public function testSetManagerClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getManagerClass();

		$this->assertThrowException('\spectrum\matchers\Exception', 'not exists', function(){
			Config::setManagerClass('\spectrum\matchers\testEnv\emptyStubs\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, Config::getManagerClass());
	}

	public function testSetManagerClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getManagerClass();

		$this->assertThrowException('\spectrum\matchers\Exception', 'should be implement interface', function(){
			Config::setManagerClass('\stdClass');
		});

		$this->assertEquals($oldClass, Config::getManagerClass());
	}

	public function testSetManagerClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getManagerClass();
		Config::lock();

		$this->assertThrowException('\spectrum\matchers\Exception', 'matchers\Config is locked', function(){
			Config::setManagerClass('\spectrum\matchers\testEnv\emptyStubs\Manager');
		});

		$this->assertEquals($oldClass, Config::getManagerClass());
	}
}