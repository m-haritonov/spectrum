<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\_internal;

use spectrum\core\Spec;

require_once __DIR__ . '/../init.php';

class GetTestSpecsTest extends \spectrum\tests\Test
{
	public function testCallsAtBuildingState_ReturnsArrayWithExclusionSpecs()
	{
		$spec1 = new Spec();
		\spectrum\_internal\addTestSpec($spec1);
		$this->assertTrue(in_array($spec1, \spectrum\_internal\getTestSpecs()));
		
		$spec2 = new Spec();
		\spectrum\_internal\addTestSpec($spec2);
		$this->assertTrue(in_array($spec1, \spectrum\_internal\getTestSpecs()));
		$this->assertTrue(in_array($spec2, \spectrum\_internal\getTestSpecs()));
		
		$spec3 = new Spec();
		\spectrum\_internal\addTestSpec($spec3);
		$this->assertTrue(in_array($spec1, \spectrum\_internal\getTestSpecs()));
		$this->assertTrue(in_array($spec2, \spectrum\_internal\getTestSpecs()));
		$this->assertTrue(in_array($spec3, \spectrum\_internal\getTestSpecs()));
	}
}