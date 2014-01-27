<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\builders\internal;

use spectrum\core\Spec;

require_once __DIR__ . '/../../init.php';

class GetBuildingSpecTest extends \spectrum\tests\Test
{
	public function testCallsAtBuildingState_ReturnsBuildingSpec()
	{
		$spec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($spec);
		$this->assertSame($spec, \spectrum\builders\internal\getBuildingSpec());
		
		$spec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($spec);
		$this->assertSame($spec, \spectrum\builders\internal\getBuildingSpec());
	}
	
	public function testCallsAtBuildingState_BuildingSpecIsNotSet_ReturnsRootSpec()
	{
		$rootSpec = \spectrum\builders\getRootSpec();
		$this->assertInstanceOf('\spectrum\core\SpecInterface', $rootSpec);
		$this->assertSame($rootSpec, \spectrum\builders\internal\getBuildingSpec());
		
		\spectrum\builders\internal\setBuildingSpec(new Spec());
		\spectrum\builders\internal\setBuildingSpec(null);
		$this->assertSame($rootSpec, \spectrum\builders\internal\getBuildingSpec());
	}
}