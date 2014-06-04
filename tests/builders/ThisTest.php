<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\builders;

require_once __DIR__ . '/../init.php';

class ThisTest extends \spectrum\tests\Test
{
	public function testCallsAtRunningState_ReturnsContextDataOfCurrentRunningSpec()
	{
		$specs = $this->createSpecsByVisualPattern('
			  __0__
			 /  |  \
			1   2   3
			.    \ /
			      4
		');
		
		\spectrum\_internal\getRootSpec()->bindChildSpec($specs[0]);
		
		$contextDataObjects = array();
		$returnValues = array();
		foreach ($specs as $spec)
		{
			$spec->test->setFunction(function() use(&$contextDataObjects, &$returnValues, $spec){
				$contextDataObjects[] = $spec->test->getContextData();
				$returnValues[] = \spectrum\builders\this();
			});
		}
		
		\spectrum\_internal\getRootSpec()->run();
		$this->assertSame($contextDataObjects, $returnValues);
	}
	
	public function testCallsAtBuildingState_ThrowsException()
	{
		$this->assertThrowsException('\spectrum\Exception', 'Builder "this" should be call only at running state', function(){
			\spectrum\builders\this();
		});
	}
}