<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\constructionCommands\commands\internal;

use spectrum\constructionCommands\callBroker;

require_once __DIR__ . '/../../../init.php';

class GetRunningEndingSpecTest extends \spectrum\tests\Test
{
	public function testCallsAtRunningState_RootSpecHasNoChildren_ReturnsRootSpec()
	{
		\spectrum\tests\Test::$temp["returnValue"] = null;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["returnValue"] = \spectrum\constructionCommands\callBroker::internal_getRunningEndingSpec();
		', 'onEndingSpecExecute');
		
		$rootSpec = callBroker::internal_getRootSpec();
		$rootSpec->run();
		
		$this->assertSame($rootSpec, \spectrum\tests\Test::$temp["returnValue"]);
	}
	
	public function testCallsAtRunningState_RootSpecHasChildren_ReturnsEndingRunningSpec()
	{
		\spectrum\tests\Test::$temp["returnValues"] = array();
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["returnValues"][] = \spectrum\constructionCommands\callBroker::internal_getRunningEndingSpec();
		', 'onEndingSpecExecute');
		
		$specs = $this->createSpecsTree('
			Spec
			->Spec
			->Spec
			->->Spec
			->->Spec
		');
		
		$rootSpec = callBroker::internal_getRootSpec();
		$rootSpec->bindChildSpec($specs[0]);
		$rootSpec->run();
		
		$this->assertSame(array($specs[1], $specs[3], $specs[4]), \spectrum\tests\Test::$temp["returnValues"]);
	}

	public function testCallsAtDeclaringState_ReturnsNull()
	{
		$this->assertSame(null, callBroker::internal_getRunningEndingSpec());
	}
}