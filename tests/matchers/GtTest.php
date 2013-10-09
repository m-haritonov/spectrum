<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\matchers;

require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../../spectrum/matchers/gt.php';

class GtTest extends \spectrum\tests\Test
{
	public function test()
	{
		$this->assertSame(true, \spectrum\matchers\gt(20, 10));

		$this->assertSame(false, \spectrum\matchers\gt(20, 20));
		$this->assertSame(false, \spectrum\matchers\gt(10, 20));
	}
}