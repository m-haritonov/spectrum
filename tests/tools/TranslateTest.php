<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\tools;

require_once __DIR__ . '/../init.php';

class TranslateTest extends \spectrum\tests\Test
{
	public function testReturnsStringWithReplacementValues()
	{
		$this->assertSame('text "some text 1" is "some text 2"', \spectrum\tools\translate('text "%aaa%" is "%bbb%"', array(
			'%aaa%' => 'some text 1',
			'%bbb%' => 'some text 2',
		)));
	}
}