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

namespace net\mkharitonov\spectrum\constructionCommands;
require_once dirname(__FILE__) . '/../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class ConfigTest extends \net\mkharitonov\spectrum\Test
{
	public function testGetManagerClass_ShouldBeReturnSpectrumClassByDefault()
	{
		$this->assertEquals('\net\mkharitonov\spectrum\constructionCommands\Manager', Config::getManagerClass());
	}

/**/

	public function testSetManagerClass_ShouldBeSetNewClass()
	{
		Config::setManagerClass('\net\mkharitonov\spectrum\constructionCommands\testEnv\emptyStubs\Manager');
		$this->assertEquals('\net\mkharitonov\spectrum\constructionCommands\testEnv\emptyStubs\Manager', Config::getManagerClass());
	}

	public function testSetManagerClass_ClassNotExists_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getManagerClass();

		$this->assertThrowException('\net\mkharitonov\spectrum\constructionCommands\Exception', 'not exists', function(){
			Config::setManagerClass('\net\mkharitonov\spectrum\constructionCommands\testEnv\emptyStubs\NotExistsClassFooBarBaz');
		});

		$this->assertEquals($oldClass, Config::getManagerClass());
	}

	public function testSetManagerClass_ClassNotImplementSpectrumInterface_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getManagerClass();

		$this->assertThrowException('\net\mkharitonov\spectrum\constructionCommands\Exception', 'should be implement interface', function(){
			Config::setManagerClass('\stdClass');
		});

		$this->assertEquals($oldClass, Config::getManagerClass());
	}

	public function testSetManagerClass_ConfigLocked_ShouldBeThrowExceptionAndNotChangeValue()
	{
		$oldClass = Config::getManagerClass();
		Config::lock();

		$this->assertThrowException('\net\mkharitonov\spectrum\constructionCommands\Exception', 'constructionCommands\Config is locked', function(){
			Config::setManagerClass('\net\mkharitonov\spectrum\constructionCommands\testEnv\emptyStubs\Manager');
		});

		$this->assertEquals($oldClass, Config::getManagerClass());
	}
}