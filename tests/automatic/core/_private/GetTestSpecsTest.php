<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core\_private;

use spectrum\core\models\Spec;

require_once __DIR__ . '/../../../init.php';

class GetTestSpecsTest extends \spectrum\tests\automatic\Test {
	public function testCallsAtBuildingState_ReturnsArrayWithExclusionSpecs() {
		$spec1 = new Spec();
		\spectrum\core\_private\addTestSpec($spec1);
		$this->assertTrue(in_array($spec1, \spectrum\core\_private\getTestSpecs()));
		
		$spec2 = new Spec();
		\spectrum\core\_private\addTestSpec($spec2);
		$this->assertTrue(in_array($spec1, \spectrum\core\_private\getTestSpecs()));
		$this->assertTrue(in_array($spec2, \spectrum\core\_private\getTestSpecs()));
		
		$spec3 = new Spec();
		\spectrum\core\_private\addTestSpec($spec3);
		$this->assertTrue(in_array($spec1, \spectrum\core\_private\getTestSpecs()));
		$this->assertTrue(in_array($spec2, \spectrum\core\_private\getTestSpecs()));
		$this->assertTrue(in_array($spec3, \spectrum\core\_private\getTestSpecs()));
	}
}