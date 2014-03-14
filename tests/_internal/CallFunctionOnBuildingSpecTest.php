<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\_internal;

use spectrum\core\Spec;

require_once __DIR__ . '/../init.php';

class CallFunctionOnBuildingSpecTest extends \spectrum\tests\Test
{
	public function testCallsAtBuildingState_CallsPassedFunctionOnPassedBuildingSpec()
	{
		$spec = new Spec();
		$buildingSpec = null;
		\spectrum\_internal\callFunctionOnBuildingSpec(function() use(&$buildingSpec){
			$buildingSpec = \spectrum\_internal\getBuildingSpec();
		}, $spec);
		
		$this->assertSame($spec, $buildingSpec);
	}
	
	public function testCallsAtBuildingState_RestoresBuildingSpecAfterCall()
	{
		$spec = new Spec();
		\spectrum\_internal\setBuildingSpec($spec);
		\spectrum\_internal\callFunctionOnBuildingSpec(function(){}, new Spec());
		
		$this->assertSame($spec, \spectrum\_internal\getBuildingSpec());
	}
	
	public function testCallsAtBuildingState_DoesNotPassArgumentsToCalleeFunction()
	{
		$passedArguments = null;
		\spectrum\_internal\callFunctionOnBuildingSpec(function() use(&$passedArguments){
			$passedArguments = func_get_args();
		}, new Spec());
		
		$this->assertSame(array(), $passedArguments);
	}

	public function testCallsAtBuildingState_ReturnsReturnValueOfPassedFunction()
	{
		$this->assertSame('aaa', \spectrum\_internal\callFunctionOnBuildingSpec(function(){
			return 'aaa';
		}, new Spec()));
	}
}