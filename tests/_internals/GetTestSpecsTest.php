<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\_internals;

use spectrum\core\Spec;

require_once __DIR__ . '/../init.php';

class GetTestSpecsTest extends \spectrum\tests\Test {
	public function testCallsAtBuildingState_ReturnsArrayWithExclusionSpecs() {
		$spec1 = new Spec();
		\spectrum\_internals\addTestSpec($spec1);
		$this->assertTrue(in_array($spec1, \spectrum\_internals\getTestSpecs()));
		
		$spec2 = new Spec();
		\spectrum\_internals\addTestSpec($spec2);
		$this->assertTrue(in_array($spec1, \spectrum\_internals\getTestSpecs()));
		$this->assertTrue(in_array($spec2, \spectrum\_internals\getTestSpecs()));
		
		$spec3 = new Spec();
		\spectrum\_internals\addTestSpec($spec3);
		$this->assertTrue(in_array($spec1, \spectrum\_internals\getTestSpecs()));
		$this->assertTrue(in_array($spec2, \spectrum\_internals\getTestSpecs()));
		$this->assertTrue(in_array($spec3, \spectrum\_internals\getTestSpecs()));
	}
}