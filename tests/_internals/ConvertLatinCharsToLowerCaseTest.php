<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\_internals;

require_once __DIR__ . '/../init.php';

class ConvertLatinCharsToLowerCaseTest extends \spectrum\tests\Test
{
	public function testConvertsLatinCharsToLowerCase()
	{
		$this->assertSame('aabbzzzz', \spectrum\_internals\convertLatinCharsToLowerCase('AaBbZZzz'));
	}
	
	public function testDoesNotConvertNotLatinChars()
	{
		// "\xDB\xF4" is string in "windows-1251" charset
		// "\xD0\xAB\xD1\x84" is string in "utf-8" charset
		$this->assertSame("\xDB\xF4" . 'aabbzz' . "\xD0\xAB\xD1\x84", \spectrum\_internals\convertLatinCharsToLowerCase("\xDB\xF4" . 'AaBbZz' . "\xD0\xAB\xD1\x84"));
	}
}