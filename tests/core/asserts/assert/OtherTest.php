<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\asserts\assert;
use spectrum\core\asserts\Assert;

require_once dirname(__FILE__) . '/../../../init.php';

class OtherTest extends Test
{
	public function testConstructor_ShouldBeAcceptActualValue()
	{
		$assert = new Assert('foo');
		$this->assertEquals('foo', $assert->getActualValue());
	}

/**/

	public function testNot_ShouldBeReturnCurrentAssertObject()
	{
		$assert = new Assert('');
		$this->assertSame($assert, $assert->not);
		$this->assertSame($assert, $assert->not);
		$this->assertSame($assert, $assert->not);
	}

	public function testNot_ShouldNotBeEnabledByDefault()
	{
		$assert = new Assert('');
		$this->assertFalse($assert->getNot());
	}

	public function testNot_ShouldBeInvertCurrentNot()
	{
		$assert = new Assert('');
		$assert->not;
		$this->assertTrue($assert->getNot());
		$assert->not;
		$this->assertFalse($assert->getNot());
		$assert->not;
		$this->assertTrue($assert->getNot());
	}
}