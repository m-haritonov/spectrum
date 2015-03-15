<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core\builders;

use spectrum\core\config;
use spectrum\core\models\Spec;

require_once __DIR__ . '/../../../init.php';

class MatcherTest extends \spectrum\tests\automatic\Test {
	public function testCallsAtBuildingState_AddsMatcherFunctionToCurrentBuildingSpec() {
		$spec = new Spec();
		\spectrum\core\_private\setCurrentBuildingSpec($spec);
		
		$function = function(){};
		\spectrum\core\builders\matcher('aaa', $function);

		$this->assertSame($function, $spec->getMatchers()->get('aaa'));
	}

	public function testCallsAtRunningState_ThrowsException() {
		\spectrum\core\config::registerEventListener('onEndingSpecExecuteBefore', function() use(&$exception) {
			try {
				\spectrum\core\builders\matcher("aaa", function(){});
			} catch (\Exception $e) {
				$exception = $e;
			}
		});
		
		\spectrum\core\_private\getRootSpec()->run();
		
		$this->assertInstanceOf('\spectrum\core\Exception', $exception);
		$this->assertSame('Function "matcher" should be call only at building state', $exception->getMessage());
	}
}