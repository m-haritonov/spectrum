<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\constructionCommands\commands;

use spectrum\config;
use spectrum\constructionCommands\callBroker;
use spectrum\core\Spec;

require_once __DIR__ . '/../../init.php';

class ThisTest extends \spectrum\tests\Test
{
	public function testCallsAtRunningState_ReturnsContextDataOfCurrentRunningSpec()
	{
		\spectrum\tests\Test::$temp["contextDataObjects"] = array();
		\spectrum\tests\Test::$temp["returnValues"] = array();
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["contextDataObjects"][] = $this->getOwnerSpec()->contexts->getContextData();
			\spectrum\tests\Test::$temp["returnValues"][] = \spectrum\constructionCommands\callBroker::this();
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

		$this->assertSame(\spectrum\tests\Test::$temp["contextDataObjects"], \spectrum\tests\Test::$temp["returnValues"]);
	}
	
	public function testCallsAtDeclaringState_ThrowsException()
	{
		$this->assertThrowsException('\spectrum\constructionCommands\Exception', 'Construction command "this" should be call only at running state', function(){
			\spectrum\constructionCommands\callBroker::this();
		});
	}
}