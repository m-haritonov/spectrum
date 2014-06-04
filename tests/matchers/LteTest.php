<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\matchers;

require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../../spectrum/matchers/lte.php';

class LteTest extends \spectrum\tests\Test
{
	public function test()
	{
		$this->assertSame(true, \spectrum\matchers\lte(10, 20));
		$this->assertSame(true, \spectrum\matchers\lte(10, 10));
		
		$this->assertSame(false, \spectrum\matchers\lte(20, 10));
	}
}