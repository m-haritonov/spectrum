<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core\matchers;

require_once __DIR__ . '/../../../init.php';
require_once __DIR__ . '/../../../../spectrum/core/matchers/ident.php';

class IdentTest extends \spectrum\tests\automatic\Test {
	public function test() {
		$this->assertSame(true, \spectrum\core\matchers\ident(new \spectrum\core\models\details\MatcherCall(), 'aaa', 'aaa'));
		$this->assertSame(true, \spectrum\core\matchers\ident(new \spectrum\core\models\details\MatcherCall(), 1, 1));

		$this->assertSame(false, \spectrum\core\matchers\ident(new \spectrum\core\models\details\MatcherCall(), 'aaa', 'bbb'));
		$this->assertSame(false, \spectrum\core\matchers\ident(new \spectrum\core\models\details\MatcherCall(), 111, '111'));
		$this->assertSame(false, \spectrum\core\matchers\ident(new \spectrum\core\models\details\MatcherCall(), 0, false));
	}
}