<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core\constructs;

use spectrum\core\config;
use spectrum\core\models\Spec;

require_once __DIR__ . '/../../../init.php';

class BeTest extends \spectrum\tests\automatic\Test {
	public function testCallsAtRunningState_ReturnsNewAssertionInstance() {
		$returnValues = array();
		
		\spectrum\core\config::registerEventListener('onEndingSpecExecuteBefore', function() use(&$returnValues) {
			$returnValues[] = \spectrum\core\constructs\be("aaa");
			$returnValues[] = \spectrum\core\constructs\be("aaa");
		});
		
		\spectrum\core\_private\getRootSpec()->run();
		
		$this->assertSame(2, count($returnValues));
		$this->assertInstanceOf('\spectrum\core\models\Assertion', $returnValues[0]);
		$this->assertInstanceOf('\spectrum\core\models\Assertion', $returnValues[1]);
		$this->assertNotSame($returnValues[0], $returnValues[1]);
	}
	
	public function testCallsAtRunningState_UsesConfigForAssertionClassGetting() {
		$assertClassName = \spectrum\tests\_testware\tools::createClass('class ... extends \spectrum\core\models\Assertion {}');
		config::setCoreClassReplacement('\spectrum\core\models\Assertion', $assertClassName);

		\spectrum\core\config::registerEventListener('onEndingSpecExecuteBefore', function() use(&$returnValue) {
			$returnValue = \spectrum\core\constructs\be("aaa");
		});
		
		\spectrum\core\_private\getRootSpec()->run();
		
		$this->assertInstanceOf($assertClassName, $returnValue);
	}
	
	public function testCallsAtRunningState_PassesToAssertionInstanceCurrentRunningSpecAndTestedValue() {
		\spectrum\tests\_testware\tools::$temp["assertion"] = null;
		\spectrum\tests\_testware\tools::$temp["passedArguments"] = null;
		
		config::setCoreClassReplacement('\spectrum\core\models\Assertion', \spectrum\tests\_testware\tools::createClass('
			class ... extends \spectrum\core\models\Assertion {
				public function __construct(\spectrum\core\models\SpecInterface $ownerSpec, $testedValue) {
					\spectrum\tests\_testware\tools::$temp["assertion"] = $this;
					\spectrum\tests\_testware\tools::$temp["passedArguments"] = func_get_args();
				}
			}
		'));
		
		\spectrum\core\config::registerEventListener('onEndingSpecExecuteBefore', function() use(&$returnValue) {
			$returnValue = \spectrum\core\constructs\be("aaa");
		});
		
		$spec = new Spec();
		\spectrum\core\_private\getRootSpec()->bindChildSpec($spec);
		\spectrum\core\_private\getRootSpec()->run();

		$this->assertInstanceOf('\spectrum\core\models\Assertion', \spectrum\tests\_testware\tools::$temp["assertion"]);
		$this->assertSame(\spectrum\tests\_testware\tools::$temp["assertion"], $returnValue);
		$this->assertSame(2, count(\spectrum\tests\_testware\tools::$temp["passedArguments"]));
		$this->assertInstanceOf('\spectrum\core\models\Spec', \spectrum\tests\_testware\tools::$temp["passedArguments"][0]);
		$this->assertSame($spec, \spectrum\tests\_testware\tools::$temp["passedArguments"][0]);
		$this->assertSame('aaa', \spectrum\tests\_testware\tools::$temp["passedArguments"][1]);
	}
	
	public function testCallsAtBuildingState_ThrowsException() {
		$this->assertThrowsException('\spectrum\core\Exception', 'Function "be" should be call only at running state', function(){
			\spectrum\core\constructs\be("aaa");
		});
	}
}