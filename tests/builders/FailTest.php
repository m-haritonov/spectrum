<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\builders;

use spectrum\config;

require_once __DIR__ . '/../init.php';

class FailTest extends \spectrum\tests\Test
{
	public function testCallsAtRunningState_GetsUserFailDetailsClassFromConfig()
	{
		$userFailDetailsClassName = $this->createClass('class ... extends \spectrum\core\details\UserFail {}');
		config::setUserFailDetailsClass($userFailDetailsClassName);

		\spectrum\tests\Test::$temp["resultBuffer"] = null;
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["resultBuffer"] = $this->getOwnerSpec()->getResultBuffer();
			\spectrum\builders\fail("some fail message");
		', 'onEndingSpecExecute');
		
		\spectrum\_internal\getRootSpec()->run();
		
		$results = \spectrum\tests\Test::$temp["resultBuffer"]->getResults();
		$this->assertInstanceOf($userFailDetailsClassName, $results[0]['details']);
		$this->assertSame('some fail message', $results[0]['details']->getMessage());
	}
	
	public function testCallsAtRunningState_AddsFalseResultWithUserFailDetailsAndPassedMessageToResultBufferOfCurrentRunningSpec()
	{
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["resultBuffers"][] = $this->getOwnerSpec()->getResultBuffer();
			
			$selfSpecKey = array_search($this->getOwnerSpec(), \spectrum\tests\Test::$temp["specs"], true);
			$parentSpecKey = array_search($this->getOwnerSpec()->getRunningParentSpec(), \spectrum\tests\Test::$temp["specs"], true);
			\spectrum\builders\fail("some fail message for spec " . $selfSpecKey . " of spec " . $parentSpecKey);
		', 'onEndingSpecExecute');
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsByListPattern('
			Spec
			->Spec(ending1)
			->Spec(parent1)
			->Spec(parent2)
			->->Spec(ending2)
		', array('parent1' => 'ending2'));
		
		\spectrum\_internal\getRootSpec()->bindChildSpec(\spectrum\tests\Test::$temp["specs"][0]);
		\spectrum\_internal\getRootSpec()->run();

		$this->assertSame(3, count(\spectrum\tests\Test::$temp["resultBuffers"]));
		
		$results = \spectrum\tests\Test::$temp["resultBuffers"][0]->getResults();
		$this->assertSame(1, count($results));
		$this->assertSame(false, $results[0]['result']);
		$this->assertInstanceOf('\spectrum\core\details\UserFail', $results[0]['details']);
		$this->assertSame('some fail message for spec ending1 of spec 0', $results[0]['details']->getMessage());
		
		$results = \spectrum\tests\Test::$temp["resultBuffers"][1]->getResults();
		$this->assertSame(1, count($results));
		$this->assertSame(false, $results[0]['result']);
		$this->assertInstanceOf('\spectrum\core\details\UserFail', $results[0]['details']);
		$this->assertSame('some fail message for spec ending2 of spec parent1', $results[0]['details']->getMessage());
		
		$results = \spectrum\tests\Test::$temp["resultBuffers"][2]->getResults();
		$this->assertSame(1, count($results));
		$this->assertSame(false, $results[0]['result']);
		$this->assertInstanceOf('\spectrum\core\details\UserFail', $results[0]['details']);
		$this->assertSame('some fail message for spec ending2 of spec parent2', $results[0]['details']->getMessage());
	}
	
	public function testCallsAtRunningState_MessageIsNotSet_AddsFalseResultWithUserFailDetailsAndEmptyMessageToResultBufferOfCurrentRunningSpec()
	{
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["resultBuffers"][] = $this->getOwnerSpec()->getResultBuffer();
			\spectrum\builders\fail();
		', 'onEndingSpecExecute');
		
		\spectrum\_internal\getRootSpec()->run();

		$this->assertSame(1, count(\spectrum\tests\Test::$temp["resultBuffers"]));
		
		$results = \spectrum\tests\Test::$temp["resultBuffers"][0]->getResults();
		$this->assertSame(1, count($results));
		$this->assertSame(false, $results[0]['result']);
		$this->assertInstanceOf('\spectrum\core\details\UserFail', $results[0]['details']);
		$this->assertSame(null, $results[0]['details']->getMessage());
	}
	
	public function testCallsAtBuildingState_ThrowsException()
	{
		$this->assertThrowsException('\spectrum\builders\Exception', 'Builder "fail" should be call only at running state', function(){
			\spectrum\builders\fail("aaa");
		});
	}
}