<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\_internal;

use spectrum\core\Spec;

require_once __DIR__ . '/../init.php';

class GetCurrentBuildingSpecTest extends \spectrum\tests\Test
{
	public function testCallsAtBuildingState_ReturnsBuildingSpec()
	{
		$spec = new Spec();
		\spectrum\_internal\setCurrentBuildingSpec($spec);
		$this->assertSame($spec, \spectrum\_internal\getCurrentBuildingSpec());
		
		$spec = new Spec();
		\spectrum\_internal\setCurrentBuildingSpec($spec);
		$this->assertSame($spec, \spectrum\_internal\getCurrentBuildingSpec());
	}
	
	public function testCallsAtBuildingState_BuildingSpecIsNotSet_ReturnsRootSpec()
	{
		$rootSpec = \spectrum\_internal\getRootSpec();
		$this->assertInstanceOf('\spectrum\core\SpecInterface', $rootSpec);
		$this->assertSame($rootSpec, \spectrum\_internal\getCurrentBuildingSpec());
		
		\spectrum\_internal\setCurrentBuildingSpec(new Spec());
		\spectrum\_internal\setCurrentBuildingSpec(null);
		$this->assertSame($rootSpec, \spectrum\_internal\getCurrentBuildingSpec());
	}
}