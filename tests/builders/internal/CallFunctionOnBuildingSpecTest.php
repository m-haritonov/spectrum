<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\builders\internal;

use spectrum\core\Spec;

require_once __DIR__ . '/../../init.php';

class CallFunctionOnBuildingSpecTest extends \spectrum\tests\Test
{
	public function testCallsAtBuildingState_CallsPassedFunctionOnPassedBuildingSpec()
	{
		$spec = new Spec();
		$buildingSpec = null;
		\spectrum\builders\internal\callFunctionOnBuildingSpec(function() use(&$buildingSpec){
			$buildingSpec = \spectrum\builders\internal\getBuildingSpec();
		}, $spec);
		
		$this->assertSame($spec, $buildingSpec);
	}
	
	public function testCallsAtBuildingState_RestoresBuildingSpecAfterCall()
	{
		$spec = new Spec();
		\spectrum\builders\internal\setBuildingSpec($spec);
		\spectrum\builders\internal\callFunctionOnBuildingSpec(function(){}, new Spec());
		
		$this->assertSame($spec, \spectrum\builders\internal\getBuildingSpec());
	}
	
	public function testCallsAtBuildingState_DoesNotPassArgumentsToCalleeFunction()
	{
		$passedArguments = null;
		\spectrum\builders\internal\callFunctionOnBuildingSpec(function() use(&$passedArguments){
			$passedArguments = func_get_args();
		}, new Spec());
		
		$this->assertSame(array(), $passedArguments);
	}

	public function testCallsAtBuildingState_ReturnsReturnValueOfPassedFunction()
	{
		$this->assertSame('aaa', \spectrum\builders\internal\callFunctionOnBuildingSpec(function(){
			return 'aaa';
		}, new Spec()));
	}
}