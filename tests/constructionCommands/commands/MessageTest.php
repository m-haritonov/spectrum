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

class MessageTest extends \spectrum\tests\Test
{
	public function testCallsAtRunningState_AddsPassedMessageToMessagesPluginInstanceOfCurrentRunningSpec()
	{
		\spectrum\tests\Test::$temp["messages"] = array();
		
		$this->registerPluginWithCodeInEvent('
			$selfSpecKey = array_search($this->getOwnerSpec(), \spectrum\tests\Test::$temp["specs"]);
			$parentSpecKey = array_search($this->getOwnerSpec()->getRunningParentSpec(), \spectrum\tests\Test::$temp["specs"]);
			\spectrum\constructionCommands\callBroker::message("some message for spec " . $selfSpecKey . " of spec " . $parentSpecKey);
			
			\spectrum\tests\Test::$temp["messages"][] = $this->getOwnerSpec()->messages->getAll();
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

		$this->assertSame(3, count(\spectrum\tests\Test::$temp["messages"]));
		
		$this->assertSame(array('some message for spec ending1 of spec 0'), \spectrum\tests\Test::$temp["messages"][0]);
		$this->assertSame(array('some message for spec ending2 of spec parent1'), \spectrum\tests\Test::$temp["messages"][1]);
		$this->assertSame(array('some message for spec ending2 of spec parent2'), \spectrum\tests\Test::$temp["messages"][2]);
	}
	
	public function testCallsAtDeclaringState_ThrowsException()
	{
		$this->assertThrowsException('\spectrum\constructionCommands\Exception', 'Construction command "message" should be call only at running state', function(){
			\spectrum\constructionCommands\callBroker::message("aaa");
		});
	}
}