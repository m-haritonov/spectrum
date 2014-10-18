<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\matchers;

require_once __DIR__ . '/../../init.php';
require_once __DIR__ . '/../../../spectrum/matchers/eq.php';

class EqTest extends \spectrum\tests\automatic\Test {
	public function test() {
		$this->assertSame(true, \spectrum\matchers\eq(new \spectrum\core\details\MatcherCall(), 'aaa', 'aaa'));
		$this->assertSame(true, \spectrum\matchers\eq(new \spectrum\core\details\MatcherCall(), 111, '111'));
		$this->assertSame(true, \spectrum\matchers\eq(new \spectrum\core\details\MatcherCall(), 0, false));
		$this->assertSame(true, \spectrum\matchers\eq(new \spectrum\core\details\MatcherCall(), 1, true));
		$this->assertSame(true, \spectrum\matchers\eq(new \spectrum\core\details\MatcherCall(), 'aaa', true));
		
		// Be careful (http://www.php.net/manual/en/language.operators.comparison.php)
		$this->assertSame(true, \spectrum\matchers\eq(new \spectrum\core\details\MatcherCall(), '123', '       123'));
		$this->assertSame(true, \spectrum\matchers\eq(new \spectrum\core\details\MatcherCall(), '1e3', '1000'));
		$this->assertSame(true, \spectrum\matchers\eq(new \spectrum\core\details\MatcherCall(), '+74951112233', '74951112233'));
		$this->assertSame(true, \spectrum\matchers\eq(new \spectrum\core\details\MatcherCall(), '00000020', '0000000000000000020'));
		$this->assertSame(true, \spectrum\matchers\eq(new \spectrum\core\details\MatcherCall(), '0X1D', '29E0'));
		$this->assertSame(true, \spectrum\matchers\eq(new \spectrum\core\details\MatcherCall(), '0xafebac', '11529132'));
		$this->assertSame(true, \spectrum\matchers\eq(new \spectrum\core\details\MatcherCall(), '0xafebac', '0XAFEBAC'));
		$this->assertSame(true, \spectrum\matchers\eq(new \spectrum\core\details\MatcherCall(), '0xeb', '+235e-0'));
		$this->assertSame(true, \spectrum\matchers\eq(new \spectrum\core\details\MatcherCall(), '0.235', '+.235'));
		$this->assertSame(true, \spectrum\matchers\eq(new \spectrum\core\details\MatcherCall(), '0.2e-10', '2.0E-11'));
		
		$this->assertSame(false, \spectrum\matchers\eq(new \spectrum\core\details\MatcherCall(), 'aaa', 'bbb'));
	}
}