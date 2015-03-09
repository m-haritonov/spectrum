<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core\builders;

require_once __DIR__ . '/../../../init.php';

class DataTest extends \spectrum\tests\automatic\Test {
	public function testCallsAtRunningState_ReturnsDataOfCurrentRunningSpec() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByVisualPattern('
			  __0__
			 /  |  \
			1   2   3
			.    \ /
			      4
		');
		
		\spectrum\_private\getRootSpec()->bindChildSpec($specs[0]);
		
		$dataObjects = array();
		$returnValues = array();
		foreach ($specs as $spec) {
			$spec->getExecutor()->setFunction(function() use(&$dataObjects, &$returnValues, $spec) {
				$dataObjects[] = $spec->getData();
				$returnValues[] = \spectrum\core\builders\data();
			});
		}
		
		\spectrum\_private\getRootSpec()->run();
		$this->assertSame($dataObjects, $returnValues);
	}
	
	public function testCallsAtBuildingState_ThrowsException() {
		$this->assertThrowsException('\spectrum\core\Exception', 'Builder "data" should be call only at running state', function(){
			\spectrum\core\builders\data();
		});
	}
}