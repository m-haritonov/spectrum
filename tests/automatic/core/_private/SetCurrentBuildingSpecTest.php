<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core\_private;

use spectrum\core\Spec;

require_once __DIR__ . '/../../../init.php';

class SetCurrentBuildingSpecTest extends \spectrum\tests\automatic\Test {
	public function testCallsAtBuildingState_ArgumentIsNull_SetsNullToStaticVariable() {
		$reflection = new \ReflectionFunction('spectrum\core\_private\setCurrentBuildingSpec');
		
		$spec = new Spec();
		\spectrum\core\_private\setCurrentBuildingSpec($spec);
		$staticVariables = $reflection->getStaticVariables();
		$this->assertSame($spec, $staticVariables['data']->buildingSpec);
		
		\spectrum\core\_private\setCurrentBuildingSpec(null);
		$staticVariables = $reflection->getStaticVariables();
		$this->assertSame(null, $staticVariables['data']->buildingSpec);
	}
	
	public function testCallsAtBuildingState_ArgumentIsSpec_SetsSpecToStaticVariable() {
		$reflection = new \ReflectionFunction('spectrum\core\_private\setCurrentBuildingSpec');
		
		$spec = new Spec();
		\spectrum\core\_private\setCurrentBuildingSpec($spec);
		$staticVariables = $reflection->getStaticVariables();
		$this->assertSame($spec, $staticVariables['data']->buildingSpec);
		
		$spec = new Spec();
		\spectrum\core\_private\setCurrentBuildingSpec($spec);
		$staticVariables = $reflection->getStaticVariables();
		$this->assertSame($spec, $staticVariables['data']->buildingSpec);
		
		\spectrum\core\_private\setCurrentBuildingSpec(null);
		$staticVariables = $reflection->getStaticVariables();
		$this->assertSame(null, $staticVariables['data']->buildingSpec);
		
		$spec = new Spec();
		\spectrum\core\_private\setCurrentBuildingSpec($spec);
		$staticVariables = $reflection->getStaticVariables();
		$this->assertSame($spec, $staticVariables['data']->buildingSpec);
	}
}