<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\matchers;

require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../../spectrum/matchers/lt.php';

class LtTest extends \spectrum\tests\Test
{
	public function test()
	{
		$this->assertSame(true, \spectrum\matchers\lt(10, 20));

		$this->assertSame(false, \spectrum\matchers\lt(10, 10));
		$this->assertSame(false, \spectrum\matchers\lt(20, 10));
	}
}