<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\_private;

use spectrum\core\config;

require_once __DIR__ . '/../../init.php';

class FormatTextForOutputTest extends \spectrum\tests\automatic\Test {
	public function test() {
		config::setOutputIndention('  ');
		config::setOutputNewline("\r\n");
		
		$this->assertSame("    aaa\r\n" . "      bbb\r\n" . "  ccc\r\n", \spectrum\_private\formatTextForOutput("\t\taaa\n" . "\t\t\tbbb\n" . "\tccc\n"));
		$this->assertSame("    aaa", \spectrum\_private\formatTextForOutput("\t\taaa"));
		$this->assertSame("aaa\r\n" . "  bbb\r\n" . "    ccc", \spectrum\_private\formatTextForOutput("\t\taaa\n" . "\t\t\tbbb\n" . "\t\t\t\tccc", 2));
	}
}