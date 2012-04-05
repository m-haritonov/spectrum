<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins;
require_once dirname(__FILE__) . '/../../../init.php';

use spectrum\core\Config;

class MatchersTest extends Test
{
	public function testAdd_ShouldBeThrowExceptionIfNameIsReservedNameNot()
	{
		$this->assertThrowException('\spectrum\core\plugins\Exception', '"not" was reserved', function(){
			$spec = new \spectrum\core\SpecContainerDescribe();
			$spec->matchers->add('not', function(){});
		});
	}

	public function testAdd_ShouldBeThrowExceptionIfNameIsReservedNameBe()
	{
		$this->assertThrowException('\spectrum\core\plugins\Exception', '"be" was reserved', function(){
			$spec = new \spectrum\core\SpecContainerDescribe();
			$spec->matchers->add('be', function(){});
		});
	}

	public function testAdd_ShouldBeThrowExceptionIfPublicMethodWithSameNameAlreadyExistsInAssertClass()
	{
		$this->assertThrowException('\spectrum\core\plugins\Exception', 'Can\'t add matcher with name "getNot": public method with same name already exists', function(){
			$spec = new \spectrum\core\SpecContainerDescribe();
			$spec->matchers->add('getNot', function(){});
		});
	}

	public function testAdd_ShouldNotBeThrowExceptionIfProtectedMethodWithSameNameAlreadyExistsInAssertClass()
	{
		$spec = new \spectrum\core\SpecContainerDescribe();
		$spec->matchers->add('createMatcherCallDetails', function(){});
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