<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core\builders;

use spectrum\core\models\SpecInterface;

require_once __DIR__ . '/../../../init.php';

class MessageTest extends \spectrum\tests\automatic\Test {
	public function testCallsAtRunningState_AddsPassedMessageToMessagesInstanceOfCurrentRunningSpec() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec(ending1)
			->Spec(parent1)
			->Spec(parent2)
			->->Spec(ending2)
		', array('parent1' => 'ending2'));
		
		$messages = array();
		\spectrum\core\config::registerEventListener('onEndingSpecExecuteBefore', function(SpecInterface $spec) use($specs, &$messages) {
			$selfSpecKey = array_search($spec, $specs, true);
			$parentSpecKey = array_search($spec->getRunningParentSpec(), $specs, true);
			\spectrum\core\builders\message("some message for spec " . $selfSpecKey . " of spec " . $parentSpecKey);
			
			$messages[] = $spec->getMessages()->getAll();
		});
		
		\spectrum\core\_private\getRootSpec()->bindChildSpec($specs[0]);
		\spectrum\core\_private\getRootSpec()->run();

		$this->assertSame(3, count($messages));
		
		$this->assertSame(array('some message for spec ending1 of spec 0'), $messages[0]);
		$this->assertSame(array('some message for spec ending2 of spec parent1'), $messages[1]);
		$this->assertSame(array('some message for spec ending2 of spec parent2'), $messages[2]);
	}
	
	public function testCallsAtBuildingState_ThrowsException() {
		$this->assertThrowsException('\spectrum\core\Exception', 'Function "message" should be call only at running state', function(){
			\spectrum\core\builders\message("aaa");
		});
	}
}