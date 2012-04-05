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

namespace spectrum\core\plugins\basePlugins\stack\named;
use spectrum\core\plugins\basePlugins\stack\Named;

require_once dirname(__FILE__) . '/../../../../../init.php';

class AddTest extends Test
{
	public function testShouldBeAddValuesWithName()
	{
		$plugin = new Named(new \spectrum\core\SpecContainerDescribe(), 'foo');

		$plugin->add('fooName', 'fooValue');
		$this->assertSame(array('fooName' => 'fooValue'), $plugin->getAll());

		$plugin->add('barName', 'barValue');
		$this->assertSame(array(
			'fooName' => 'fooValue',
			'barName' => 'barValue',
		), $plugin->getAll());
	}

	public function testShouldBeReplaceExistsValue()
	{
		$plugin = new Named(new \spectrum\core\SpecContainerDescribe(), 'foo');

		$plugin->add('fooName', 'fooValue');
		$plugin->add('fooName', 'barValue');
		
		$this->assertSame(array('fooName' => 'barValue'), $plugin->getAll());
	}

	public function testShouldBeAddValuesToEnd()
	{
		$plugin = new Named(new \spectrum\core\SpecContainerDescribe(), 'foo');

		$plugin->add(0, 'foo');
		$this->assertSame(array('foo'), $plugin->getAll());

		$plugin->add(1, 'bar');
		$this->assertSame(array('foo', 'bar'), $plugin->getAll());

		$plugin->add(2, 'baz');
		$this->assertSame(array('foo', 'bar', 'baz'), $plugin->getAll());
	}

	public function testShouldBeReturnAddedValue()
	{
		$plugin = new Named(new \spectrum\core\SpecContainerDescribe(), 'foo');
		$this->assertEquals('bar', $plugin->add('name', 'bar'));
	}
}


