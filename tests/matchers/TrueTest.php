<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\matchers;

require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../../spectrum/matchers/true.php';

class TrueTest extends \spectrum\tests\Test
{
	public function test()
	{
		$this->assertSame(true, \spectrum\matchers\true(true));
		
		$this->assertSame(false, \spectrum\matchers\true(0));
		$this->assertSame(false, \spectrum\matchers\true(''));
		$this->assertSame(false, \spectrum\matchers\true(null));
		$this->assertSame(false, \spectrum\matchers\true(false));
		$this->assertSame(false, \spectrum\matchers\true('aaa'));
		$this->assertSame(false, \spectrum\matchers\true(1));
		$this->assertSame(false, \spectrum\matchers\true(-1));
	}
}