<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\_private;

use spectrum\core\Spec;

require_once __DIR__ . '/../../init.php';

class AddTestSpecTest extends \spectrum\tests\automatic\Test {
	public function testCallsAtBuildingState_SavesSpecsToStaticVariable() {
		$spec1 = new Spec();
		$spec2 = new Spec();
		$spec3 = new Spec();
		
		$reflection = new \ReflectionFunction('spectrum\_private\addTestSpec');
		
		\spectrum\_private\addTestSpec($spec1);
		$staticVariables = $reflection->getStaticVariables();
		$this->assertTrue(in_array($spec1, $staticVariables['specs']));
		
		\spectrum\_private\addTestSpec($spec2);
		$staticVariables = $reflection->getStaticVariables();
		$this->assertTrue(in_array($spec1, $staticVariables['specs']));
		$this->assertTrue(in_array($spec2, $staticVariables['specs']));
		
		\spectrum\_private\addTestSpec($spec3);
		$staticVariables = $reflection->getStaticVariables();
		$this->assertTrue(in_array($spec1, $staticVariables['specs']));
		$this->assertTrue(in_array($spec2, $staticVariables['specs']));
		$this->assertTrue(in_array($spec3, $staticVariables['specs']));
	}
}