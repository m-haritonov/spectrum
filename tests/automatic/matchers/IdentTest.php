<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\matchers;

require_once __DIR__ . '/../../init.php';
require_once __DIR__ . '/../../../spectrum/matchers/ident.php';

class IdentTest extends \spectrum\tests\automatic\Test {
	public function test() {
		$this->assertSame(true, \spectrum\matchers\ident(new \spectrum\core\details\MatcherCall(), 'aaa', 'aaa'));
		$this->assertSame(true, \spectrum\matchers\ident(new \spectrum\core\details\MatcherCall(), 1, 1));

		$this->assertSame(false, \spectrum\matchers\ident(new \spectrum\core\details\MatcherCall(), 'aaa', 'bbb'));
		$this->assertSame(false, \spectrum\matchers\ident(new \spectrum\core\details\MatcherCall(), 111, '111'));
		$this->assertSame(false, \spectrum\matchers\ident(new \spectrum\core\details\MatcherCall(), 0, false));
	}
}