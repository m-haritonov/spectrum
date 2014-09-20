<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\builders;

use spectrum\config;
use spectrum\core\Spec;

require_once __DIR__ . '/../init.php';

class AfterTest extends \spectrum\tests\Test {
	public function testCallsAtBuildingState_AddsContextFunctionWithAfterTypeToCurrentBuildingSpec() {
		$spec = new Spec();
		\spectrum\_internals\setCurrentBuildingSpec($spec);
		
		$function1 = function(){};
		$function2 = function(){};
		\spectrum\builders\after($function1);
		\spectrum\builders\after($function2);

		$this->assertSame(array(
			array('function' => $function1, 'type' => 'after'),
			array('function' => $function2, 'type' => 'after'),
		), $spec->contextModifiers->getAll());
	}

	public function testCallsAtBuildingState_ReturnsReturnValueOfContextAddFunction() {
		config::unregisterSpecPlugins('\spectrum\core\plugins\ContextModifiers');
		config::registerSpecPlugin($this->createClass('
			class ... extends \spectrum\core\plugins\ContextModifiers {
				public function add($function, $type = "before") {
					return "some text";
				}
			}
		'));
		
		$this->assertSame('some text', \spectrum\builders\after(function(){}));
	}
	
	public function testCallsAtRunningState_ThrowsException() {
		\spectrum\tests\Test::$temp["exception"] = null;
		
		$this->registerPluginWithCodeInEvent('
			try {
				\spectrum\builders\after(function(){});
			} catch (\Exception $e) {
				\spectrum\tests\Test::$temp["exception"] = $e;
			}
		', 'onEndingSpecExecute');
		
		\spectrum\_internals\getRootSpec()->run();
		
		$this->assertInstanceOf('\spectrum\Exception', \spectrum\tests\Test::$temp["exception"]);
		$this->assertSame('Builder "after" should be call only at building state', \spectrum\tests\Test::$temp["exception"]->getMessage());
	}
}