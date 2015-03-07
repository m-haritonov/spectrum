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

		$this->assertSame($function, $spec->getMatchers()->get('aaa'));
	}

	public function testCallsAtBuildingState_ReturnsReturnValueOfMatcherAddFunction() {
		config::setClassReplacement('\spectrum\core\Matchers', $this->createClass('
			class ... extends \spectrum\core\Matchers {
				public function add($name, $function) {
					return "some text";
				}
			}
		'));
		
		\spectrum\_internals\setCurrentBuildingSpec(new Spec());
		
		$this->assertSame('some text', \spectrum\builders\addMatcher('aaa', function(){}));
	}
	
	public function testCallsAtRunningState_ThrowsException() {
		\spectrum\config::registerEventListener('onEndingSpecExecuteBefore', function() use(&$exception) {
			try {
				\spectrum\builders\addMatcher("aaa", function(){});
			} catch (\Exception $e) {
				$exception = $e;
			}
		});
		
		\spectrum\_internals\getRootSpec()->run();
		
		$this->assertInstanceOf('\spectrum\Exception', $exception);
		$this->assertSame('Builder "addMatcher" should be call only at building state', $exception->getMessage());
	}
}