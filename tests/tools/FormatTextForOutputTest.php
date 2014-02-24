<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\tools;

use spectrum\config;

require_once __DIR__ . '/../init.php';

class FormatTextForOutputTest extends \spectrum\tests\Test
{
	public function test()
	{
		config::setOutputIndention('  ');
		config::setOutputNewline("\r\n");
		
		$this->assertSame("    aaa\r\n" . "      bbb\r\n" . "  ccc\r\n", \spectrum\tools\formatTextForOutput("\t\taaa\n" . "\t\t\tbbb\n" . "\tccc\n"));
		$this->assertSame("    aaa", \spectrum\tools\formatTextForOutput("\t\taaa"));
		$this->assertSame("aaa\r\n" . "  bbb\r\n" . "    ccc", \spectrum\tools\formatTextForOutput("\t\taaa\n" . "\t\t\tbbb\n" . "\t\t\t\tccc", 2));
	}
}