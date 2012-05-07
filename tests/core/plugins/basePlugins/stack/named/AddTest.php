<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins\stack\named;
use spectrum\core\plugins\basePlugins\stack\Named;

require_once __DIR__ . '/../../../../../init.php';

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


