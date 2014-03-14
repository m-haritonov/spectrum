<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\_internal;

use spectrum\core\Spec;

require_once __DIR__ . '/../init.php';

class GetBuildingSpecTest extends \spectrum\tests\Test
{
	public function testCallsAtBuildingState_ReturnsBuildingSpec()
	{
		$spec = new Spec();
		\spectrum\_internal\setBuildingSpec($spec);
		$this->assertSame($spec, \spectrum\_internal\getBuildingSpec());
		
		$spec = new Spec();
		\spectrum\_internal\setBuildingSpec($spec);
		$this->assertSame($spec, \spectrum\_internal\getBuildingSpec());
	}
	
	public function testCallsAtBuildingState_BuildingSpecIsNotSet_ReturnsRootSpec()
	{
		$rootSpec = \spectrum\_internal\getRootSpec();
		$this->assertInstanceOf('\spectrum\core\SpecInterface', $rootSpec);
		$this->assertSame($rootSpec, \spectrum\_internal\getBuildingSpec());
		
		\spectrum\_internal\setBuildingSpec(new Spec());
		\spectrum\_internal\setBuildingSpec(null);
		$this->assertSame($rootSpec, \spectrum\_internal\getBuildingSpec());
	}
}