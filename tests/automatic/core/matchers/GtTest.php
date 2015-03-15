<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core\matchers;

require_once __DIR__ . '/../../../init.php';
require_once __DIR__ . '/../../../../spectrum/core/matchers/gt.php';

class GtTest extends \spectrum\tests\automatic\Test {
	public function test() {
		$this->assertSame(true, \spectrum\core\matchers\gt(new \spectrum\core\models\details\MatcherCall(), 20, 10));

		$this->assertSame(false, \spectrum\core\matchers\gt(new \spectrum\core\models\details\MatcherCall(), 20, 20));
		$this->assertSame(false, \spectrum\core\matchers\gt(new \spectrum\core\models\details\MatcherCall(), 10, 20));
	}
}