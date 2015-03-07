<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\_private;

use spectrum\core\Spec;

require_once __DIR__ . '/../../init.php';

class GetCurrentBuildingSpecTest extends \spectrum\tests\automatic\Test {
	public function testCallsAtBuildingState_ReturnsBuildingSpec() {
		$spec = new Spec();
		\spectrum\_private\setCurrentBuildingSpec($spec);
		$this->assertSame($spec, \spectrum\_private\getCurrentBuildingSpec());
		
		$spec = new Spec();
		\spectrum\_private\setCurrentBuildingSpec($spec);
		$this->assertSame($spec, \spectrum\_private\getCurrentBuildingSpec());
	}
	
	public function testCallsAtBuildingState_BuildingSpecIsNotSet_ReturnsRootSpec() {
		$rootSpec = \spectrum\_private\getRootSpec();
		$this->assertInstanceOf('\spectrum\core\SpecInterface', $rootSpec);
		$this->assertSame($rootSpec, \spectrum\_private\getCurrentBuildingSpec());
		
		\spectrum\_private\setCurrentBuildingSpec(new Spec());
		\spectrum\_private\setCurrentBuildingSpec(null);
		$this->assertSame($rootSpec, \spectrum\_private\getCurrentBuildingSpec());
	}
}