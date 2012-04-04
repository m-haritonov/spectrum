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

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class ManagerTest extends Test
{
	public function testAddBaseMatchersToSpec_ShouldBeAddAllBaseMatchersToSpec()
	{
		$spec = new \spectrum\core\SpecContainerDescribe();
		Manager::addBaseMatchersToSpec($spec);

		$this->assertEquals('\spectrum\matchers\base\null', $spec->matchers->get('null'));
		$this->assertEquals('\spectrum\matchers\base\true', $spec->matchers->get('true'));
		$this->assertEquals('\spectrum\matchers\base\false', $spec->matchers->get('false'));
		$this->assertEquals('\spectrum\matchers\base\eq', $spec->matchers->get('eq'));
		$this->assertEquals('\spectrum\matchers\base\ident', $spec->matchers->get('ident'));
		$this->assertEquals('\spectrum\matchers\base\lt', $spec->matchers->get('lt'));
		$this->assertEquals('\spectrum\matchers\base\lte', $spec->matchers->get('lte'));
		$this->assertEquals('\spectrum\matchers\base\gt', $spec->matchers->get('gt'));
		$this->assertEquals('\spectrum\matchers\base\gte', $spec->matchers->get('gte'));
		$this->assertEquals('\spectrum\matchers\base\throwException', $spec->matchers->get('throwException'));

		$this->assertEquals(10, count($spec->matchers->getAll()));
	}

	public function testAddBaseMatchersToSpec_ShouldBeIncludeMatcherFile()
	{
		$spec = new \spectrum\core\SpecContainerDescribe();
		Manager::addBaseMatchersToSpec($spec);

		$this->assertTrue(function_exists('\spectrum\matchers\base\null'));
		$this->assertTrue(function_exists('\spectrum\matchers\base\true'));
		$this->assertTrue(function_exists('\spectrum\matchers\base\false'));
		$this->assertTrue(function_exists('\spectrum\matchers\base\eq'));
		$this->assertTrue(function_exists('\spectrum\matchers\base\ident'));
		$this->assertTrue(function_exists('\spectrum\matchers\base\lt'));
		$this->assertTrue(function_exists('\spectrum\matchers\base\lte'));
		$this->assertTrue(function_exists('\spectrum\matchers\base\gt'));
		$this->assertTrue(function_exists('\spectrum\matchers\base\gte'));
		$this->assertTrue(function_exists('\spectrum\matchers\base\throwException'));
	}
}