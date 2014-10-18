<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\matchers;

require_once __DIR__ . '/../../init.php';
require_once __DIR__ . '/../../../spectrum/matchers/lt.php';

class LtTest extends \spectrum\tests\automatic\Test {
	public function test() {
		$this->assertSame(true, \spectrum\matchers\lt(new \spectrum\core\details\MatcherCall(), 10, 20));

		$this->assertSame(false, \spectrum\matchers\lt(new \spectrum\core\details\MatcherCall(), 10, 10));
		$this->assertSame(false, \spectrum\matchers\lt(new \spectrum\core\details\MatcherCall(), 20, 10));
	}
}