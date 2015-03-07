<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\_private;

use spectrum\config;

require_once __DIR__ . '/../../init.php';

class ConvertCharsetTest extends \spectrum\tests\automatic\Test {
	public function testInputCharsetIsNull_UsesInputCharsetValueFromConfig() {
		config::setInputCharset('utf-8');
		$this->assertSame("\x7A\xDB\xF4", \spectrum\_private\convertCharset("\x7A\xD0\xAB\xD1\x84", null, 'windows-1251'));
	}
	
	public function testInputCharsetIsPassed_UsesPassedValue() {
		config::setInputCharset('koi8-r');
		$this->assertSame("\x7A\xDB\xF4", \spectrum\_private\convertCharset("\x7A\xD0\xAB\xD1\x84", 'utf-8', 'windows-1251'));
	}
	
	public function testOutputCharsetIsNull_UsesOutputCharsetValueFromConfig() {
		config::setOutputCharset('windows-1251');
		$this->assertSame("\x7A\xDB\xF4", \spectrum\_private\convertCharset("\x7A\xD0\xAB\xD1\x84", 'utf-8', null));
	}
	
	public function testOutputCharsetIsPassed_UsesPassedValue() {
		config::setOutputCharset('koi8-r');
		$this->assertSame("\x7A\xDB\xF4", \spectrum\_private\convertCharset("\x7A\xD0\xAB\xD1\x84", 'utf-8', 'windows-1251'));
	}
}