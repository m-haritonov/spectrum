<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\builders;

require_once __DIR__ . '/../../init.php';

class MessageTest extends \spectrum\tests\automatic\Test {
	public function testCallsAtRunningState_AddsPassedMessageToMessagesPluginInstanceOfCurrentRunningSpec() {
		\spectrum\tests\automatic\Test::$temp["messages"] = array();
		
		$this->registerPluginWithCodeInEvent('
			$selfSpecKey = array_search($this->getOwnerSpec(), \spectrum\tests\automatic\Test::$temp["specs"], true);
			$parentSpecKey = array_search($this->getOwnerSpec()->getRunningParentSpec(), \spectrum\tests\automatic\Test::$temp["specs"], true);
			\spectrum\builders\message("some message for spec " . $selfSpecKey . " of spec " . $parentSpecKey);
			
			\spectrum\tests\automatic\Test::$temp["messages"][] = $this->getOwnerSpec()->messages->getAll();
		', 'onEndingSpecExecute');
		
		\spectrum\tests\automatic\Test::$temp["specs"] = $this->createSpecsByListPattern('
			Spec
			->Spec(ending1)
			->Spec(parent1)
			->Spec(parent2)
			->->Spec(ending2)
		', array('parent1' => 'ending2'));
		
		\spectrum\_internals\getRootSpec()->bindChildSpec(\spectrum\tests\automatic\Test::$temp["specs"][0]);
		\spectrum\_internals\getRootSpec()->run();

		$this->assertSame(3, count(\spectrum\tests\automatic\Test::$temp["messages"]));
		
		$this->assertSame(array('some message for spec ending1 of spec 0'), \spectrum\tests\automatic\Test::$temp["messages"][0]);
		$this->assertSame(array('some message for spec ending2 of spec parent1'), \spectrum\tests\automatic\Test::$temp["messages"][1]);
		$this->assertSame(array('some message for spec ending2 of spec parent2'), \spectrum\tests\automatic\Test::$temp["messages"][2]);
	}
	
	public function testCallsAtBuildingState_ThrowsException() {
		$this->assertThrowsException('\spectrum\Exception', 'Builder "message" should be call only at running state', function(){
			\spectrum\builders\message("aaa");
		});
	}
}