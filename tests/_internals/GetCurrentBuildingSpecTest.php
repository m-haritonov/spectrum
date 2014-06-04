<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\_internals;

use spectrum\core\Spec;

require_once __DIR__ . '/../init.php';

class GetCurrentBuildingSpecTest extends \spectrum\tests\Test
{
	public function testCallsAtBuildingState_ReturnsBuildingSpec()
	{
		$spec = new Spec();
		\spectrum\_internals\setCurrentBuildingSpec($spec);
		$this->assertSame($spec, \spectrum\_internals\getCurrentBuildingSpec());
		
		$spec = new Spec();
		\spectrum\_internals\setCurrentBuildingSpec($spec);
		$this->assertSame($spec, \spectrum\_internals\getCurrentBuildingSpec());
	}
	
	public function testCallsAtBuildingState_BuildingSpecIsNotSet_ReturnsRootSpec()
	{
		$rootSpec = \spectrum\_internals\getRootSpec();
		$this->assertInstanceOf('\spectrum\core\SpecInterface', $rootSpec);
		$this->assertSame($rootSpec, \spectrum\_internals\getCurrentBuildingSpec());
		
		\spectrum\_internals\setCurrentBuildingSpec(new Spec());
		\spectrum\_internals\setCurrentBuildingSpec(null);
		$this->assertSame($rootSpec, \spectrum\_internals\getCurrentBuildingSpec());
	}
}