<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic;

use spectrum\core\config;
use spectrum\core\Spec;

require_once __DIR__ . '/../init.php';

class BeforeTest extends \spectrum\tests\automatic\Test {
	public function testCallsAtBuildingState_AddsContextFunctionWithBeforeTypeToCurrentBuildingSpec() {
		$spec = new Spec();
		\spectrum\_private\setCurrentBuildingSpec($spec);
		
		$function1 = function(){};
		$function2 = function(){};
		\spectrum\before($function1);
		\spectrum\before($function2);

		$this->assertSame(array(
			array('function' => $function1, 'type' => 'before'),
			array('function' => $function2, 'type' => 'before'),
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
		
		$this->assertSame('some text', \spectrum\before(function(){}));
	}
	
	public function testCallsAtRunningState_ThrowsException() {
		\spectrum\core\config::registerEventListener('onEndingSpecExecuteBefore', function() use(&$exception) {
			try {
				\spectrum\before(function(){});
			} catch (\Exception $e) {
				$exception = $e;
			}
		});
		
		\spectrum\_private\getRootSpec()->run();
		
		$this->assertInstanceOf('\spectrum\core\Exception', $exception);
		$this->assertSame('Builder "before" should be call only at building state', $exception->getMessage());
	}
}