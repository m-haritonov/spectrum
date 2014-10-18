<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\matchers;

require_once __DIR__ . '/../../init.php';
require_once __DIR__ . '/../../../spectrum/matchers/gte.php';

class GteTest extends \spectrum\tests\automatic\Test {
	public function test() {
		$this->assertSame(true, \spectrum\matchers\gte(new \spectrum\core\details\MatcherCall(), 20, 10));
		$this->assertSame(true, \spectrum\matchers\gte(new \spectrum\core\details\MatcherCall(), 20, 20));
		
		$this->assertSame(false, \spectrum\matchers\gte(new \spectrum\core\details\MatcherCall(), 10, 20));
	}
}