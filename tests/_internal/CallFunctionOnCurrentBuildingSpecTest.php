<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\_internal;

use spectrum\core\Spec;

require_once __DIR__ . '/../init.php';

class CallFunctionOnCurrentBuildingSpecTest extends \spectrum\tests\Test
{
	public function testCallsAtBuildingState_CallsPassedFunctionOnPassedBuildingSpec()
	{
		$spec = new Spec();
		$buildingSpec = null;
		\spectrum\_internal\callFunctionOnCurrentBuildingSpec(function() use(&$buildingSpec){
			$buildingSpec = \spectrum\_internal\getCurrentBuildingSpec();
		}, $spec);
		
		$this->assertSame($spec, $buildingSpec);
	}
	
	public function testCallsAtBuildingState_RestoresBuildingSpecAfterCall()
	{
		$spec = new Spec();
		\spectrum\_internal\setCurrentBuildingSpec($spec);
		\spectrum\_internal\callFunctionOnCurrentBuildingSpec(function(){}, new Spec());
		
		$this->assertSame($spec, \spectrum\_internal\getCurrentBuildingSpec());
	}
	
	public function testCallsAtBuildingState_DoesNotPassArgumentsToCalleeFunction()
	{
		$passedArguments = null;
		\spectrum\_internal\callFunctionOnCurrentBuildingSpec(function() use(&$passedArguments){
			$passedArguments = func_get_args();
		}, new Spec());
		
		$this->assertSame(array(), $passedArguments);
	}

	public function testCallsAtBuildingState_ReturnsReturnValueOfPassedFunction()
	{
		$this->assertSame('aaa', \spectrum\_internal\callFunctionOnCurrentBuildingSpec(function(){
			return 'aaa';
		}, new Spec()));
	}
}