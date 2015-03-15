<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core\_private;

use spectrum\core\models\Spec;

require_once __DIR__ . '/../../../init.php';

class GetCurrentBuildingSpecTest extends \spectrum\tests\automatic\Test {
	public function testCallsAtBuildingState_ReturnsBuildingSpec() {
		$spec = new Spec();
		\spectrum\core\_private\setCurrentBuildingSpec($spec);
		$this->assertSame($spec, \spectrum\core\_private\getCurrentBuildingSpec());
		
		$spec = new Spec();
		\spectrum\core\_private\setCurrentBuildingSpec($spec);
		$this->assertSame($spec, \spectrum\core\_private\getCurrentBuildingSpec());
	}
	
	public function testCallsAtBuildingState_BuildingSpecIsNotSet_ReturnsRootSpec() {
		$rootSpec = \spectrum\core\_private\getRootSpec();
		$this->assertInstanceOf('\spectrum\core\models\SpecInterface', $rootSpec);
		$this->assertSame($rootSpec, \spectrum\core\_private\getCurrentBuildingSpec());
		
		\spectrum\core\_private\setCurrentBuildingSpec(new Spec());
		\spectrum\core\_private\setCurrentBuildingSpec(null);
		$this->assertSame($rootSpec, \spectrum\core\_private\getCurrentBuildingSpec());
	}
}