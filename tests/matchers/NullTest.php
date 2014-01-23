<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\matchers;

require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../../spectrum/matchers/null.php';

class NullTest extends \spectrum\tests\Test
{
	public function test()
	{
		$this->assertSame(true, \spectrum\matchers\null(null));
		
		$this->assertSame(false, \spectrum\matchers\null(0));
		$this->assertSame(false, \spectrum\matchers\null(''));
		$this->assertSame(false, \spectrum\matchers\null(true));
		$this->assertSame(false, \spectrum\matchers\null(false));
		$this->assertSame(false, \spectrum\matchers\null('aaa'));
		$this->assertSame(false, \spectrum\matchers\null(1));
		$this->assertSame(false, \spectrum\matchers\null(-1));
	}
}