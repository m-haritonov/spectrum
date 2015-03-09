<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core\_private;

require_once __DIR__ . '/../../../init.php';

class ConvertLatinCharsToLowerCaseTest extends \spectrum\tests\automatic\Test {
	public function testConvertsLatinCharsToLowerCase() {
		$this->assertSame('aabbzzzz', \spectrum\core\_private\convertLatinCharsToLowerCase('AaBbZZzz'));
	}
	
	public function testDoesNotConvertNotLatinChars() {
		// "\xDB\xF4" is string in "windows-1251" charset
		// "\xD0\xAB\xD1\x84" is string in "UTF-8" charset
		$this->assertSame("\xDB\xF4" . 'aabbzz' . "\xD0\xAB\xD1\x84", \spectrum\core\_private\convertLatinCharsToLowerCase("\xDB\xF4" . 'AaBbZz' . "\xD0\xAB\xD1\x84"));
	}
}