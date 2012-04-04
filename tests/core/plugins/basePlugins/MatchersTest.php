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

namespace spectrum\core\plugins\basePlugins;
require_once dirname(__FILE__) . '/../../../init.php';

use spectrum\core\Config;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class MatchersTest extends Test
{
	public function testAdd_ShouldBeThrowExceptionIfNameIsReservedNameNot()
	{
		$this->assertThrowException('\spectrum\core\plugins\Exception', '"not" was reserved', function(){
			$spec = new \spectrum\core\SpecContainerDescribe();
			$spec->matchers->add('not', function(){});
		});
	}

	public function testAdd_ShouldBeThrowExceptionIfNameIsReservedNameIsNot()
	{
		$this->assertThrowException('\spectrum\core\plugins\Exception', '"isNot" was reserved', function(){
			$spec = new \spectrum\core\SpecContainerDescribe();
			$spec->matchers->add('isNot', function(){});
		});
	}

	public function testAdd_ShouldBeThrowExceptionIfNameIsReservedNameGetActualValue()
	{
		$this->assertThrowException('\spectrum\core\plugins\Exception', '"getActualValue" was reserved', function(){
			$spec = new \spectrum\core\SpecContainerDescribe();
			$spec->matchers->add('getActualValue', function(){});
		});
	}

	public function testAdd_ShouldBeThrowExceptionIfNameIsReservedNameBe()
	{
		$this->assertThrowException('\spectrum\core\plugins\Exception', '"be" was reserved', function(){
			$spec = new \spectrum\core\SpecContainerDescribe();
			$spec->matchers->add('be', function(){});
		});
	}

	public function testAdd_ShouldBeThrowExceptionIfNotAllowMatchersAdd()
	{
		Config::setAllowMatchersAdd(false);
		$this->assertThrowException('\spectrum\core\plugins\Exception', 'Matchers add deny', function(){
			$spec = new \spectrum\core\SpecContainerDescribe();
			$spec->matchers->add('foo', function(){});
		});
	}

	public function testAdd_ShouldBeThrowExceptionIfMatcherExistsAndAllowMatchersOverride()
	{
		Config::setAllowMatchersOverride(false);
		$spec = new \spectrum\core\SpecContainerDescribe();
		$spec->matchers->add('foo', function(){});
		$this->assertThrowException('\spectrum\core\plugins\Exception', 'Matchers override deny', function() use($spec){
			$spec->matchers->add('foo', function(){});
		});
	}

/**/

	public function testRemove_ShouldBeThrowExceptionIfNotAllowMatchersOverride()
	{
		Config::setAllowMatchersOverride(false);
		$this->assertThrowException('\spectrum\core\plugins\Exception', 'Matchers override deny', function(){
			$spec = new \spectrum\core\SpecContainerDescribe();
			$spec->matchers->remove('foo');
		});
	}

/**/

	public function testRemoveAll_ShouldBeThrowExceptionIfNotAllowMatchersOverride()
	{
		Config::setAllowMatchersOverride(false);
		$this->assertThrowException('\spectrum\core\plugins\Exception', 'Matchers override deny', function(){
			$spec = new \spectrum\core\SpecContainerDescribe();
			$spec->matchers->removeAll();
		});
	}
}