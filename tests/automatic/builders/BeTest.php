<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\builders;

use spectrum\config;
use spectrum\core\Spec;

require_once __DIR__ . '/../../init.php';

class BeTest extends \spectrum\tests\automatic\Test {
	public function testCallsAtRunningState_ReturnsNewAssertionInstance() {
		\spectrum\tests\automatic\Test::$temp["returnValues"] = array();
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\automatic\Test::$temp["returnValues"][] = \spectrum\builders\be("aaa");
			\spectrum\tests\automatic\Test::$temp["returnValues"][] = \spectrum\builders\be("aaa");
		', 'onEndingSpecExecute');
		
		\spectrum\_internals\getRootSpec()->run();
		
		$this->assertSame(2, count(\spectrum\tests\automatic\Test::$temp["returnValues"]));
		$this->assertInstanceOf('\spectrum\core\Assertion', \spectrum\tests\automatic\Test::$temp["returnValues"][0]);
		$this->assertInstanceOf('\spectrum\core\Assertion', \spectrum\tests\automatic\Test::$temp["returnValues"][1]);
		$this->assertNotSame(\spectrum\tests\automatic\Test::$temp["returnValues"][0], \spectrum\tests\automatic\Test::$temp["returnValues"][1]);
	}
	
	public function testCallsAtRunningState_UsesConfigForAssertionClassGetting() {
		$assertClassName = $this->createClass('class ... extends \spectrum\core\Assertion {}');
		config::setClassReplacement('\spectrum\core\Assertion', $assertClassName);

		\spectrum\tests\automatic\Test::$temp["returnValue"] = null;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\automatic\Test::$temp["returnValue"] = \spectrum\builders\be("aaa");
		', 'onEndingSpecExecute');
		
		\spectrum\_internals\getRootSpec()->run();
		
		$this->assertInstanceOf($assertClassName, \spectrum\tests\automatic\Test::$temp["returnValue"]);
	}
	
	public function testCallsAtRunningState_PassesToAssertionInstanceCurrentRunningSpecAndTestedValue() {
		\spectrum\tests\automatic\Test::$temp["assertion"] = null;
		\spectrum\tests\automatic\Test::$temp["passedArguments"] = array();
		
		config::setClassReplacement('\spectrum\core\Assertion', $this->createClass('
			class ... extends \spectrum\core\Assertion {
				public function __construct(\spectrum\core\SpecInterface $ownerSpec, $testedValue) {
					\spectrum\tests\automatic\Test::$temp["assertion"] = $this;
					\spectrum\tests\automatic\Test::$temp["passedArguments"] = func_get_args();
				}
			}
		'));
		
		\spectrum\tests\automatic\Test::$temp["returnValue"] = null;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\automatic\Test::$temp["returnValue"] = \spectrum\builders\be("aaa");
		', 'onEndingSpecExecute');
		
		$spec = new Spec();
		\spectrum\_internals\getRootSpec()->bindChildSpec($spec);
		\spectrum\_internals\getRootSpec()->run();

		$this->assertInstanceOf('\spectrum\core\Assertion', \spectrum\tests\automatic\Test::$temp["assertion"]);
		$this->assertSame(\spectrum\tests\automatic\Test::$temp["assertion"], \spectrum\tests\automatic\Test::$temp["returnValue"]);
		$this->assertSame(2, count(\spectrum\tests\automatic\Test::$temp["passedArguments"]));
		$this->assertInstanceOf('\spectrum\core\Spec', \spectrum\tests\automatic\Test::$temp["passedArguments"][0]);
		$this->assertSame($spec, \spectrum\tests\automatic\Test::$temp["passedArguments"][0]);
		$this->assertSame('aaa', \spectrum\tests\automatic\Test::$temp["passedArguments"][1]);
	}
	
	public function testCallsAtBuildingState_ThrowsException() {
		$this->assertThrowsException('\spectrum\Exception', 'Builder "be" should be call only at running state', function(){
			\spectrum\builders\be("aaa");
		});
	}
}