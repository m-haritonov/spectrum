<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\builders;

use spectrum\config;
use spectrum\core\SpecInterface;

require_once __DIR__ . '/../../init.php';

class FailTest extends \spectrum\tests\automatic\Test {
	public function testCallsAtRunningState_GetsUserFailDetailsClassFromConfig() {
		$userFailDetailsClassName = \spectrum\tests\_testware\tools::createClass('class ... extends \spectrum\core\details\UserFail {}');
		config::setClassReplacement('\spectrum\core\details\UserFail', $userFailDetailsClassName);

		\spectrum\config::registerEventListener('onEndingSpecExecuteBefore', function(SpecInterface $spec) use(&$resultBuffer) {
			$resultBuffer = $spec->getResultBuffer();
			\spectrum\builders\fail("some fail message");
		});
		
		\spectrum\_private\getRootSpec()->run();
		
		$results = $resultBuffer->getResults();
		$this->assertInstanceOf($userFailDetailsClassName, $results[0]['details']);
		$this->assertSame('some fail message', $results[0]['details']->getMessage());
	}
	
	public function testCallsAtRunningState_AddsFalseResultWithUserFailDetailsAndPassedMessageToResultBufferOfCurrentRunningSpec() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec(ending1)
			->Spec(parent1)
			->Spec(parent2)
			->->Spec(ending2)
		', array('parent1' => 'ending2'));
		
		$resultBuffers = array();
		\spectrum\config::registerEventListener('onEndingSpecExecuteBefore', function(SpecInterface $spec) use(&$resultBuffers, $specs) {
			$resultBuffers[] = $spec->getResultBuffer();
			
			$selfSpecKey = array_search($spec, $specs, true);
			$parentSpecKey = array_search($spec->getRunningParentSpec(), $specs, true);
			\spectrum\builders\fail("some fail message for spec " . $selfSpecKey . " of spec " . $parentSpecKey);
		});
		
		\spectrum\_private\getRootSpec()->bindChildSpec($specs[0]);
		\spectrum\_private\getRootSpec()->run();

		$this->assertSame(3, count($resultBuffers));
		
		$results = $resultBuffers[0]->getResults();
		$this->assertSame(1, count($results));
		$this->assertSame(false, $results[0]['result']);
		$this->assertInstanceOf('\spectrum\core\details\UserFail', $results[0]['details']);
		$this->assertSame('some fail message for spec ending1 of spec 0', $results[0]['details']->getMessage());
		
		$results = $resultBuffers[1]->getResults();
		$this->assertSame(1, count($results));
		$this->assertSame(false, $results[0]['result']);
		$this->assertInstanceOf('\spectrum\core\details\UserFail', $results[0]['details']);
		$this->assertSame('some fail message for spec ending2 of spec parent1', $results[0]['details']->getMessage());
		
		$results = $resultBuffers[2]->getResults();
		$this->assertSame(1, count($results));
		$this->assertSame(false, $results[0]['result']);
		$this->assertInstanceOf('\spectrum\core\details\UserFail', $results[0]['details']);
		$this->assertSame('some fail message for spec ending2 of spec parent2', $results[0]['details']->getMessage());
	}
	
	public function testCallsAtRunningState_MessageIsNotSet_AddsFalseResultWithUserFailDetailsAndEmptyMessageToResultBufferOfCurrentRunningSpec() {
		$resultBuffers = array();
		\spectrum\config::registerEventListener('onEndingSpecExecuteBefore', function(SpecInterface $spec) use(&$resultBuffers) {
			$resultBuffers[] = $spec->getResultBuffer();
			\spectrum\builders\fail();
		});
		
		\spectrum\_private\getRootSpec()->run();

		$this->assertSame(1, count($resultBuffers));
		
		$results = $resultBuffers[0]->getResults();
		$this->assertSame(1, count($results));
		$this->assertSame(false, $results[0]['result']);
		$this->assertInstanceOf('\spectrum\core\details\UserFail', $results[0]['details']);
		$this->assertSame(null, $results[0]['details']->getMessage());
	}
	
	public function testCallsAtBuildingState_ThrowsException() {
		$this->assertThrowsException('\spectrum\Exception', 'Builder "fail" should be call only at running state', function(){
			\spectrum\builders\fail("aaa");
		});
	}
}