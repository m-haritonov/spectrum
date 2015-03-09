<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core\builders;

use spectrum\core\config;
use spectrum\core\ResultsInterface;
use spectrum\core\SpecInterface;

require_once __DIR__ . '/../../../init.php';

class FailTest extends \spectrum\tests\automatic\Test {
	public function testCallsAtRunningState_GetsUserFailDetailsClassFromConfig() {
		$userFailDetailsClassName = \spectrum\tests\_testware\tools::createClass('class ... extends \spectrum\core\details\UserFail {}');
		config::setClassReplacement('\spectrum\core\details\UserFail', $userFailDetailsClassName);

		/** @var ResultsInterface $results */
		\spectrum\core\config::registerEventListener('onEndingSpecExecuteBefore', function(SpecInterface $spec) use(&$results) {
			$results = $spec->getResults();
			\spectrum\core\builders\fail("some fail message");
		});
		
		\spectrum\core\_private\getRootSpec()->run();
		
		$resultsContent = $results->getAll();
		$this->assertInstanceOf($userFailDetailsClassName, $resultsContent[0]->getDetails());
		$this->assertSame('some fail message', $resultsContent[0]->getDetails()->getMessage());
	}
	
	public function testCallsAtRunningState_AddsFalseResultWithUserFailDetailsAndPassedMessageToResultsOfCurrentRunningSpec() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec(ending1)
			->Spec(parent1)
			->Spec(parent2)
			->->Spec(ending2)
		', array('parent1' => 'ending2'));
		
		/** @var ResultsInterface[] $results */
		$results = array();
		\spectrum\core\config::registerEventListener('onEndingSpecExecuteBefore', function(SpecInterface $spec) use(&$results, $specs) {
			$results[] = $spec->getResults();
			
			$selfSpecKey = array_search($spec, $specs, true);
			$parentSpecKey = array_search($spec->getRunningParentSpec(), $specs, true);
			\spectrum\core\builders\fail("some fail message for spec " . $selfSpecKey . " of spec " . $parentSpecKey);
		});
		
		\spectrum\core\_private\getRootSpec()->bindChildSpec($specs[0]);
		\spectrum\core\_private\getRootSpec()->run();

		$this->assertSame(3, count($results));
		
		$resultsContent = $results[0]->getAll();
		$this->assertSame(1, count($resultsContent));
		$this->assertSame(false, $resultsContent[0]->getValue());
		$this->assertInstanceOf('\spectrum\core\details\UserFail', $resultsContent[0]->getDetails());
		$this->assertSame('some fail message for spec ending1 of spec 0', $resultsContent[0]->getDetails()->getMessage());
		
		$resultsContent = $results[1]->getAll();
		$this->assertSame(1, count($resultsContent));
		$this->assertSame(false, $resultsContent[0]->getValue());
		$this->assertInstanceOf('\spectrum\core\details\UserFail', $resultsContent[0]->getDetails());
		$this->assertSame('some fail message for spec ending2 of spec parent1', $resultsContent[0]->getDetails()->getMessage());
		
		$resultsContent = $results[2]->getAll();
		$this->assertSame(1, count($resultsContent));
		$this->assertSame(false, $resultsContent[0]->getValue());
		$this->assertInstanceOf('\spectrum\core\details\UserFail', $resultsContent[0]->getDetails());
		$this->assertSame('some fail message for spec ending2 of spec parent2', $resultsContent[0]->getDetails()->getMessage());
	}
	
	public function testCallsAtRunningState_MessageIsNotSet_AddsFalseResultWithUserFailDetailsAndEmptyMessageToResultsOfCurrentRunningSpec() {
		/** @var ResultsInterface[] $results */
		$results = array();
		\spectrum\core\config::registerEventListener('onEndingSpecExecuteBefore', function(SpecInterface $spec) use(&$results) {
			$results[] = $spec->getResults();
			\spectrum\core\builders\fail();
		});
		
		\spectrum\core\_private\getRootSpec()->run();

		$this->assertSame(1, count($results));
		
		$resultsContent = $results[0]->getAll();
		$this->assertSame(1, count($resultsContent));
		$this->assertSame(false, $resultsContent[0]->getValue());
		$this->assertInstanceOf('\spectrum\core\details\UserFail', $resultsContent[0]->getDetails());
		$this->assertSame(null, $resultsContent[0]->getDetails()->getMessage());
	}
	
	public function testCallsAtBuildingState_ThrowsException() {
		$this->assertThrowsException('\spectrum\core\Exception', 'Function "fail" should be call only at running state', function(){
			\spectrum\core\builders\fail("aaa");
		});
	}
}