<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core\_private;

require_once __DIR__ . '/../../../init.php';

class TranslateTest extends \spectrum\tests\automatic\Test {
	public function testReturnsStringWithReplacementValues() {
		$this->assertSame('text "some text 1" is "some text 2"', \spectrum\core\_private\translate('text "%aaa%" is "%bbb%"', array(
			'%aaa%' => 'some text 1',
			'%bbb%' => 'some text 2',
		)));
	}
}