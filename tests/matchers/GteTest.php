<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\matchers;

require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../../spectrum/matchers/gte.php';

class GteTest extends \spectrum\tests\Test {
	public function test() {
		$this->assertSame(true, \spectrum\matchers\gte(20, 10));
		$this->assertSame(true, \spectrum\matchers\gte(20, 20));
		
		$this->assertSame(false, \spectrum\matchers\gte(10, 20));
	}
}