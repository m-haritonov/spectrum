<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core\builders;

use spectrum\core\config;
use spectrum\core\Spec;

require_once __DIR__ . '/../../../init.php';

class AfterTest extends \spectrum\tests\automatic\Test {
	public function testCallsAtBuildingState_AddsContextFunctionWithAfterTypeToCurrentBuildingSpec() {
		$spec = new Spec();
		\spectrum\_private\setCurrentBuildingSpec($spec);
		
		$function1 = function(){};
		$function2 = function(){};
		\spectrum\core\builders\after($function1);
		\spectrum\core\builders\after($function2);

		$this->assertSame(array(
			array('function' => $function1, 'type' => 'after'),
			array('function' => $function2, 'type' => 'after'),
		), $spec->getContextModifiers()->getAll());
	}

	public function testCallsAtBuildingState_ReturnsReturnValueOfContextAddFunction() {
		config::setClassReplacement('\spectrum\core\ContextModifiers', \spectrum\tests\_testware\tools::createClass('
			class ... extends \spectrum\core\ContextModifiers {
				public function add($function, $type = "before") {
					return "some text";
				}
			}
		'));
		
		$this->assertSame('some text', \spectrum\core\builders\after(function(){}));
	}
	
	public function testCallsAtRunningState_ThrowsException() {
		\spectrum\core\config::registerEventListener('onEndingSpecExecuteBefore', function() use(&$exception) {
			try {
				\spectrum\core\builders\after(function(){});
			} catch (\Exception $e) {
				$exception = $e;
			}
		});
		
		\spectrum\_private\getRootSpec()->run();
		
		$this->assertInstanceOf('\spectrum\core\Exception', $exception);
		$this->assertSame('Builder "after" should be call only at building state', $exception->getMessage());
	}
}