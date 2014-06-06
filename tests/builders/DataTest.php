<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\builders;

require_once __DIR__ . '/../init.php';

class DataTest extends \spectrum\tests\Test
{
	public function testCallsAtRunningState_ReturnsDataOfCurrentRunningSpec()
	{
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
		foreach ($specs as $spec)
		{
			$spec->test->setFunction(function() use(&$dataObjects, &$returnValues, $spec){
				$dataObjects[] = $spec->test->getData();
				$returnValues[] = \spectrum\builders\data();
			});
		}
		
		\spectrum\_internals\getRootSpec()->run();
		$this->assertSame($dataObjects, $returnValues);
	}
	
	public function testCallsAtBuildingState_ThrowsException()
	{
		$this->assertThrowsException('\spectrum\Exception', 'Builder "data" should be call only at running state', function(){
			\spectrum\builders\data();
		});
	}
}