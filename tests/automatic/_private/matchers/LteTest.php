<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\_private\matchers;

require_once __DIR__ . '/../../../init.php';
require_once __DIR__ . '/../../../../spectrum/_private/matchers/lte.php';

class LteTest extends \spectrum\tests\automatic\Test {
	public function test() {
		$this->assertSame(true, \spectrum\_private\matchers\lte(new \spectrum\core\details\MatcherCall(), 10, 20));
		$this->assertSame(true, \spectrum\_private\matchers\lte(new \spectrum\core\details\MatcherCall(), 10, 10));
		
		$this->assertSame(false, \spectrum\_private\matchers\lte(new \spectrum\core\details\MatcherCall(), 20, 10));
	}
}