<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\_internal;

require_once __DIR__ . '/../init.php';

class GetRunningEndingSpecTest extends \spectrum\tests\Test
{
	public function testCallsAtRunningState_RootSpecHasNoChildren_ReturnsRootSpec()
	{
		\spectrum\tests\Test::$temp["returnValue"] = null;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["returnValue"] = \spectrum\_internal\getRunningEndingSpec();
		', 'onEndingSpecExecute');
		
		$rootSpec = \spectrum\_internal\getRootSpec();
		$rootSpec->run();
		
		$this->assertSame($rootSpec, \spectrum\tests\Test::$temp["returnValue"]);
	}
	
	public function testCallsAtRunningState_RootSpecHasChildren_ReturnsEndingRunningSpec()
	{
		\spectrum\tests\Test::$temp["returnValues"] = array();
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["returnValues"][] = \spectrum\_internal\getRunningEndingSpec();
		', 'onEndingSpecExecute');
		
		$specs = $this->createSpecsByListPattern('
			Spec
			->Spec
			->Spec
			->->Spec
			->->Spec
		');
		
		$rootSpec = \spectrum\_internal\getRootSpec();
		$rootSpec->bindChildSpec($specs[0]);
		$rootSpec->run();
		
		$this->assertSame(array($specs[1], $specs[3], $specs[4]), \spectrum\tests\Test::$temp["returnValues"]);
	}

	public function testCallsAtBuildingState_ReturnsNull()
	{
		$this->assertSame(null, \spectrum\_internal\getRunningEndingSpec());
	}
}