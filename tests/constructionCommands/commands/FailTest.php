<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\constructionCommands\commands;

use spectrum\constructionCommands\callBroker;

require_once __DIR__ . '/../../init.php';

class FailTest extends \spectrum\tests\Test
{
	public function testCallsAtRunningState_AddsFalseResultWithFailExceptionAndPassedMessageToResultBufferOfCurrentRunningSpec()
	{
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["resultBuffers"][] = $this->getOwnerSpec()->getResultBuffer();
			
			$selfSpecKey = array_search($this->getOwnerSpec(), \spectrum\tests\Test::$temp["specs"]);
			$parentSpecKey = array_search($this->getOwnerSpec()->getRunningParentSpec(), \spectrum\tests\Test::$temp["specs"]);
			\spectrum\constructionCommands\callBroker::fail("some fail message for spec " . $selfSpecKey . " of spec " . $parentSpecKey);
		', 'onEndingSpecExecute');
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsTree('
			Spec
			->Spec(ending1)
			->Spec(parent1)
			->Spec(parent2)
			->->Spec(ending2)
		', array('parent1' => 'ending2'));
		
		callBroker::internal_getRootSpec()->bindChildSpec(\spectrum\tests\Test::$temp["specs"][0]);
		callBroker::internal_getRootSpec()->run();

		$this->assertSame(3, count(\spectrum\tests\Test::$temp["resultBuffers"]));
		
		$results = \spectrum\tests\Test::$temp["resultBuffers"][0]->getResults();
		$this->assertSame(1, count($results));
		$this->assertSame(false, $results[0]['result']);
		$this->assertInstanceOf('\spectrum\constructionCommands\FailException', $results[0]['details']);
		$this->assertSame('some fail message for spec ending1 of spec 0', $results[0]['details']->getMessage());
		
		$results = \spectrum\tests\Test::$temp["resultBuffers"][1]->getResults();
		$this->assertSame(1, count($results));
		$this->assertSame(false, $results[0]['result']);
		$this->assertInstanceOf('\spectrum\constructionCommands\FailException', $results[0]['details']);
		$this->assertSame('some fail message for spec ending2 of spec parent1', $results[0]['details']->getMessage());
		
		$results = \spectrum\tests\Test::$temp["resultBuffers"][2]->getResults();
		$this->assertSame(1, count($results));
		$this->assertSame(false, $results[0]['result']);
		$this->assertInstanceOf('\spectrum\constructionCommands\FailException', $results[0]['details']);
		$this->assertSame('some fail message for spec ending2 of spec parent2', $results[0]['details']->getMessage());
	}
	
	public function testCallsAtRunningState_MessageIsNotSet_AddsFalseResultWithFailExceptionAndEmptyMessageToResultBufferOfCurrentRunningSpec()
	{
		\spectrum\tests\Test::$temp["resultBuffers"] = array();
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["resultBuffers"][] = $this->getOwnerSpec()->getResultBuffer();
			\spectrum\constructionCommands\callBroker::fail();
		', 'onEndingSpecExecute');
		
		callBroker::internal_getRootSpec()->run();

		$this->assertSame(1, count(\spectrum\tests\Test::$temp["resultBuffers"]));
		
		$results = \spectrum\tests\Test::$temp["resultBuffers"][0]->getResults();
		$this->assertSame(1, count($results));
		$this->assertSame(false, $results[0]['result']);
		$this->assertInstanceOf('\spectrum\constructionCommands\FailException', $results[0]['details']);
		$this->assertSame('', $results[0]['details']->getMessage());
	}
	
	public function testCallsAtDeclaringState_ThrowsException()
	{
		$this->assertThrowsException('\spectrum\constructionCommands\Exception', 'Construction command "fail" should be call only at running state', function(){
			\spectrum\constructionCommands\callBroker::fail("aaa");
		});
	}
}