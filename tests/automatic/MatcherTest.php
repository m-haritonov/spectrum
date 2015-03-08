<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic;

use spectrum\config;
use spectrum\core\Spec;

require_once __DIR__ . '/../init.php';

class MatcherTest extends \spectrum\tests\automatic\Test {
	public function testCallsAtBuildingState_AddsMatcherFunctionToCurrentBuildingSpec() {
		$spec = new Spec();
		\spectrum\_private\setCurrentBuildingSpec($spec);
		
		$function = function(){};
		\spectrum\matcher('aaa', $function);

		$this->assertSame($function, $spec->getMatchers()->get('aaa'));
	}

	public function testCallsAtBuildingState_ReturnsReturnValueOfMatcherAddFunction() {
		config::setClassReplacement('\spectrum\core\Matchers', \spectrum\tests\_testware\tools::createClass('
			class ... extends \spectrum\core\Matchers {
				public function add($name, $function) {
					return "some text";
				}
			}
		'));
		
		\spectrum\_private\setCurrentBuildingSpec(new Spec());
		
		$this->assertSame('some text', \spectrum\matcher('aaa', function(){}));
	}
	
	public function testCallsAtRunningState_ThrowsException() {
		\spectrum\config::registerEventListener('onEndingSpecExecuteBefore', function() use(&$exception) {
			try {
				\spectrum\matcher("aaa", function(){});
			} catch (\Exception $e) {
				$exception = $e;
			}
		});
		
		\spectrum\_private\getRootSpec()->run();
		
		$this->assertInstanceOf('\spectrum\Exception', $exception);
		$this->assertSame('Builder "matcher" should be call only at building state', $exception->getMessage());
	}
}