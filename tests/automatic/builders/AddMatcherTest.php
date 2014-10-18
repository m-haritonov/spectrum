<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\builders;

use spectrum\config;
use spectrum\core\Spec;

require_once __DIR__ . '/../../init.php';

class AddMatcherTest extends \spectrum\tests\automatic\Test {
	public function testCallsAtBuildingState_AddsMatcherFunctionToCurrentBuildingSpec() {
		$spec = new Spec();
		\spectrum\_internals\setCurrentBuildingSpec($spec);
		
		$function = function(){};
		\spectrum\builders\addMatcher('aaa', $function);

		$this->assertSame($function, $spec->matchers->get('aaa'));
	}

	public function testCallsAtBuildingState_ReturnsReturnValueOfMatcherAddFunction() {
		config::unregisterSpecPlugins('\spectrum\core\plugins\Matchers');
		config::registerSpecPlugin($this->createClass('
			class ... extends \spectrum\core\plugins\Matchers {
				public function add($name, $function) {
					return "some text";
				}
			}
		'));
		
		$this->assertSame('some text', \spectrum\builders\addMatcher('aaa', function(){}));
	}
	
	public function testCallsAtRunningState_ThrowsException() {
		\spectrum\tests\automatic\Test::$temp["exception"] = null;
		
		$this->registerPluginWithCodeInEvent('
			try {
				\spectrum\builders\addMatcher("aaa", function(){});
			} catch (\Exception $e) {
				\spectrum\tests\automatic\Test::$temp["exception"] = $e;
			}
		', 'onEndingSpecExecute');
		
		\spectrum\_internals\getRootSpec()->run();
		
		$this->assertInstanceOf('\spectrum\Exception', \spectrum\tests\automatic\Test::$temp["exception"]);
		$this->assertSame('Builder "addMatcher" should be call only at building state', \spectrum\tests\automatic\Test::$temp["exception"]->getMessage());
	}
}