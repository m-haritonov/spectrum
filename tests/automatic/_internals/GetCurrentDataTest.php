<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\_internals;

require_once __DIR__ . '/../../init.php';

class GetCurrentDataTest extends \spectrum\tests\automatic\Test {
	public function testCallsAtRunningState_ReturnsDataOfCurrentRunningSpec() {
		$specs = $this->createSpecsByVisualPattern('
			  __0__
			 /  |  \
			1   2   3
			.    \ /
			      4
		');
		
		\spectrum\_internals\getRootSpec()->bindChildSpec($specs[0]);
		
		$dataObjects = array();
		$returnValues = array();
		foreach ($specs as $spec) {
			$spec->test->setFunction(function() use(&$dataObjects, &$returnValues, $spec) {
				$dataObjects[] = $spec->test->getData();
				$returnValues[] = \spectrum\_internals\getCurrentData();
			});
		}
		
		\spectrum\_internals\getRootSpec()->run();
		$this->assertSame($dataObjects, $returnValues);
	}
}