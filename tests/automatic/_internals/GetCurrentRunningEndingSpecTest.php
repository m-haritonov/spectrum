<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\_internals;

require_once __DIR__ . '/../../init.php';

class GetCurrentRunningEndingSpecTest extends \spectrum\tests\automatic\Test {
	public function testCallsAtRunningState_RootSpecHasNoChildren_ReturnsRootSpec() {
		\spectrum\config::registerEventListener('onEndingSpecExecuteBefore', function() use(&$returnValue) {
			$returnValue = \spectrum\_internals\getCurrentRunningEndingSpec();
		});
		
		$rootSpec = \spectrum\_internals\getRootSpec();
		$rootSpec->run();
		
		$this->assertSame($rootSpec, $returnValue);
	}
	
	public function testCallsAtRunningState_RootSpecHasChildren_ReturnsEndingRunningSpec() {
		$returnValues = array();
		\spectrum\config::registerEventListener('onEndingSpecExecuteBefore', function() use(&$returnValues) {
			$returnValues[] = \spectrum\_internals\getCurrentRunningEndingSpec();
		});
		
		$specs = $this->createSpecsByListPattern('
			Spec
			->Spec
			->Spec
			->->Spec
			->->Spec
		');
		
		$rootSpec = \spectrum\_internals\getRootSpec();
		$rootSpec->bindChildSpec($specs[0]);
		$rootSpec->run();
		
		$this->assertSame(array($specs[1], $specs[3], $specs[4]), $returnValues);
	}

	public function testCallsAtBuildingState_ReturnsNull() {
		$this->assertSame(null, \spectrum\_internals\getCurrentRunningEndingSpec());
	}
}