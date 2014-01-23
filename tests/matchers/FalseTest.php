<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\matchers;

require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../../spectrum/matchers/false.php';

class FalseTest extends \spectrum\tests\Test
{
	public function test()
	{
		$this->assertSame(true, \spectrum\matchers\false(false));
		
		$this->assertSame(false, \spectrum\matchers\false(0));
		$this->assertSame(false, \spectrum\matchers\false(''));
		$this->assertSame(false, \spectrum\matchers\false(null));
		$this->assertSame(false, \spectrum\matchers\false(true));
		$this->assertSame(false, \spectrum\matchers\false('aaa'));
		$this->assertSame(false, \spectrum\matchers\false(1));
		$this->assertSame(false, \spectrum\matchers\false(-1));
	}
}