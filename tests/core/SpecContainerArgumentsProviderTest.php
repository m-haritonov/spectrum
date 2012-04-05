<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core;
require_once dirname(__FILE__) . '/../init.php';

class SpecContainerArgumentsProviderTest extends SpecTest
{
	protected $currentSpecClass = '\spectrum\core\SpecContainerArgumentsProvider';

	/**
	 * @var SpecContainerArgumentsProvider
	 */
	private $spec;
	protected function setUp()
	{
		parent::setUp();
		$this->spec = new SpecContainerArgumentsProvider();
	}

/**/

	public function testCreateSpecItemForEachArgumentsRow_ShouldBeAcceptOneItemArray()
	{
		$testCallback = function(){};
		$this->spec->createSpecItemForEachArgumentsRow($testCallback, array('foo'));

		$children = $this->spec->getSpecs();
		$this->assertSame(1, count($children));

		$child = $children[0];
		$this->assertTrue($child instanceof SpecItemIt);
		$this->assertNull($child->getName());
		$this->assertSame($testCallback, $child->getTestCallback());
		$this->assertSame(array('foo'), $child->getAdditionalArguments());
	}

	public function testCreateSpecItemForEachArgumentsRow_ShouldBeAcceptShouldBeAcceptOneArgumentRows()
	{
		$testCallback = function(){};
		$this->spec->createSpecItemForEachArgumentsRow($testCallback, array(
			'foo',
			'bar',
			'baz',
		));

		$children = $this->spec->getSpecs();
		$this->assertSame(3, count($children));

		$child = $children[0];
		$this->assertTrue($child instanceof SpecItemIt);
		$this->assertNull($child->getName());
		$this->assertSame($testCallback, $child->getTestCallback());
		$this->assertSame(array('foo'), $child->getAdditionalArguments());

		$child = $children[1];
		$this->assertTrue($child instanceof SpecItemIt);
		$this->assertNull($child->getName());
		$this->assertSame($testCallback, $child->getTestCallback());
		$this->assertSame(array('bar'), $child->getAdditionalArguments());

		$child = $children[2];
		$this->assertTrue($child instanceof SpecItemIt);
		$this->assertNull($child->getName());
		$this->assertSame($testCallback, $child->getTestCallback());
		$this->assertSame(array('baz'), $child->getAdditionalArguments());
	}

	public function testCreateSpecItemForEachArgumentsRow_ShouldBeAcceptManyArgumentsRows()
	{
		$testCallback = function(){};
		$this->spec->createSpecItemForEachArgumentsRow($testCallback, array(
			array('foo1', 'foo2'),
			array('bar1', 'bar2'),
			array('baz1', 'baz2', 'baz3'),
		));

		$children = $this->spec->getSpecs();
		$this->assertSame(3, count($children));

		$child = $children[0];
		$this->assertTrue($child instanceof SpecItemIt);
		$this->assertNull($child->getName());
		$this->assertSame($testCallback, $child->getTestCallback());
		$this->assertSame(array('foo1', 'foo2'), $child->getAdditionalArguments());

		$child = $children[1];
		$this->assertTrue($child instanceof SpecItemIt);
		$this->assertNull($child->getName());
		$this->assertSame($testCallback, $child->getTestCallback());
		$this->assertSame(array('bar1', 'bar2'), $child->getAdditionalArguments());

		$child = $children[2];
		$this->assertTrue($child instanceof SpecItemIt);
		$this->assertNull($child->getName());
		$this->assertSame($testCallback, $child->getTestCallback());
		$this->assertSame(array('baz1', 'baz2', 'baz3'), $child->getAdditionalArguments());
	}

	public function testCreateSpecItemForEachArgumentsRow_ShouldBeAcceptMixedArgumentsRows()
	{
		$testCallback = function(){};
		$this->spec->createSpecItemForEachArgumentsRow($testCallback, array(
			array('foo'),
			'bar',
			array('baz1', 'baz2'),
		));

		$children = $this->spec->getSpecs();
		$this->assertSame(3, count($children));

		$child = $children[0];
		$this->assertTrue($child instanceof SpecItemIt);
		$this->assertNull($child->getName());
		$this->assertSame($testCallback, $child->getTestCallback());
		$this->assertSame(array('foo'), $child->getAdditionalArguments());

		$child = $children[1];
		$this->assertTrue($child instanceof SpecItemIt);
		$this->assertNull($child->getName());
		$this->assertSame($testCallback, $child->getTestCallback());
		$this->assertSame(array('bar'), $child->getAdditionalArguments());

		$child = $children[2];
		$this->assertTrue($child instanceof SpecItemIt);
		$this->assertNull($child->getName());
		$this->assertSame($testCallback, $child->getTestCallback());
		$this->assertSame(array('baz1', 'baz2'), $child->getAdditionalArguments());
	}
}