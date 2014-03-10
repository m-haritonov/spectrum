<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\builders;

require_once __DIR__ . '/../init.php';

class ThisTest extends \spectrum\tests\Test
{
	public function testCallsAtRunningState_ReturnsContextDataOfCurrentRunningSpec()
	{
		\spectrum\tests\Test::$temp["contextDataObjects"] = array();
		\spectrum\tests\Test::$temp["returnValues"] = array();
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["contextDataObjects"][] = $this->getOwnerSpec()->contexts->getContextData();
			\spectrum\tests\Test::$temp["returnValues"][] = \spectrum\builders\this();
		', 'onEndingSpecExecute');
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsByListPattern('
			Spec
			->Spec(ending1)
			->Spec(parent1)
			->Spec(parent2)
			->->Spec(ending2)
		', array('parent1' => 'ending2'));
		
		\spectrum\builders\getRootSpec()->bindChildSpec(\spectrum\tests\Test::$temp["specs"][0]);
		\spectrum\builders\getRootSpec()->run();

		$this->assertSame(\spectrum\tests\Test::$temp["contextDataObjects"], \spectrum\tests\Test::$temp["returnValues"]);
	}
	
	public function testCallsAtBuildingState_ThrowsException()
	{
		$this->assertThrowsException('\spectrum\builders\Exception', 'Builder "this" should be call only at running state', function(){
			\spectrum\builders\this();
		});
	}
}