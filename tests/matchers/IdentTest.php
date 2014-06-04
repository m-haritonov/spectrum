<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\matchers;

require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../../spectrum/matchers/ident.php';

class IdentTest extends \spectrum\tests\Test
{
	public function test()
	{
		$this->assertSame(true, \spectrum\matchers\ident('aaa', 'aaa'));
		$this->assertSame(true, \spectrum\matchers\ident(1, 1));

		$this->assertSame(false, \spectrum\matchers\ident('aaa', 'bbb'));
		$this->assertSame(false, \spectrum\matchers\ident(111, '111'));
		$this->assertSame(false, \spectrum\matchers\ident(0, false));
	}
}