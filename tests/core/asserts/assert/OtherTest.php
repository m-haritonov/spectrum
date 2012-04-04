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

namespace spectrum\core\asserts\assert;
use spectrum\core\asserts\Assert;

require_once dirname(__FILE__) . '/../../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
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
		$this->assertFalse($assert->isNot());
	}

	public function testNot_ShouldBeInvertCurrentNot()
	{
		$assert = new Assert('');
		$assert->not;
		$this->assertTrue($assert->isNot());
		$assert->not;
		$this->assertFalse($assert->isNot());
		$assert->not;
		$this->assertTrue($assert->isNot());
	}
}