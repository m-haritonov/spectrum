<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core\_private;

require_once __DIR__ . '/../../../init.php';

class GetCurrentDataTest extends \spectrum\tests\automatic\Test {
	public function testCallsAtRunningState_ReturnsDataOfCurrentRunningSpec() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByVisualPattern('
			  __0__
			 /  |  \
			1   2   3
			.    \ /
			      4
		');
		
		\spectrum\core\_private\getRootSpec()->bindChildSpec($specs[0]);
		
		$dataObjects = array();
		$returnValues = array();
		foreach ($specs as $spec) {
			$spec->getExecutor()->setFunction(function() use(&$dataObjects, &$returnValues, $spec) {
				$dataObjects[] = $spec->getData();
				$returnValues[] = \spectrum\core\_private\getCurrentData();
			});
		}
		
		\spectrum\core\_private\getRootSpec()->run();
		$this->assertSame($dataObjects, $returnValues);
	}
}