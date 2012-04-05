<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core;
require_once dirname(__FILE__) . '/../init.php';

class WorldTest extends Test
{
	private $world;

	public function setUp()
	{
		parent::setUp();
		$this->world = new World();
	}

	public  function testShouldBePossibleToSetGetPublicProperty()
	{
		$this->world->foo = 'fooVal';
		$this->world->bar = 'barVal';
		$this->world->baz = 'bazVal';

		$this->assertEquals('fooVal', $this->world->foo);
		$this->assertEquals('barVal', $this->world->bar);
		$this->assertEquals('bazVal', $this->world->baz);
	}

	public  function testShouldBePossibleToSetGetPublicPropertyThroughArrayAccess()
	{
		$this->world['foo-foo'] = 'fooVal';
		$this->world['bar-bar'] = 'barVal';

		$this->assertEquals('fooVal', $this->world['foo-foo']);
		$this->assertEquals('barVal', $this->world['bar-bar']);
	}

	public  function testShouldBePossibleToCheckPublicPropertyExists()
	{
		$this->assertFalse(isset($this->world->foo));
		$this->world->foo = 'fooVal';
		$this->assertTrue(isset($this->world->foo));
	}

 	public  function testShouldBePossibleToUnsetPublicProperty()
	{
		$this->world->foo = 'fooVal';
		unset($this->world->foo);
		$this->assertFalse(property_exists($this->world, 'foo'));
	}

 	public  function testShouldBePossibleToGetPublicPropertiesCount()
	{
		$this->world->foo = 'fooVal';
		$this->world->bar = 'fooVal';

		$this->assertEquals(2, count($this->world));
	}

	public  function testShouldBePossibleToForeachTraverse()
	{
		$this->world->foo = 'fooVal';
		$this->world->bar = 'barVal';
		$this->world->baz = 'bazVal';

		$vars = array();
		foreach ($this->world as $key => $val)
		{
			$vars[$key] = $val;
		}

		$this->assertEquals(array(
			'foo' => 'fooVal',
			'bar' => 'barVal',
			'baz' => 'bazVal',
		), $vars);
	}
}