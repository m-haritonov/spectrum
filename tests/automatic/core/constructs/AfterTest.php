<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core\constructs;

use spectrum\core\config;
use spectrum\core\models\Spec;

require_once __DIR__ . '/../../../init.php';

class AfterTest extends \spectrum\tests\automatic\Test {
	public function testCallsAtBuildingState_AddsContextFunctionWithAfterTypeToCurrentBuildingSpec() {
		$spec = new Spec();
		\spectrum\core\_private\setCurrentBuildingSpec($spec);
		
		$function1 = function(){};
		$function2 = function(){};
		\spectrum\core\constructs\after($function1);
		\spectrum\core\constructs\after($function2);

		$this->assertSame(array(
			array('function' => $function1, 'type' => 'after'),
			array('function' => $function2, 'type' => 'after'),
		), $spec->getContextModifiers()->getAll());
	}

	public function testCallsAtRunningState_ThrowsException() {
		\spectrum\core\config::registerEventListener('onEndingSpecExecuteBefore', function() use(&$exception) {
			try {
				\spectrum\core\constructs\after(function(){});
			} catch (\Exception $e) {
				$exception = $e;
			}
		});
		
		\spectrum\core\_private\getRootSpec()->run();
		
		$this->assertInstanceOf('\spectrum\core\Exception', $exception);
		$this->assertSame('Function "after" should be call only at building state', $exception->getMessage());
	}
}