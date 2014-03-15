<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\builders;

use spectrum\config;
use spectrum\core\Spec;

require_once __DIR__ . '/../init.php';

class BeTest extends \spectrum\tests\Test
{
	public function testCallsAtRunningState_ReturnsNewAssertInstance()
	{
		\spectrum\tests\Test::$temp["returnValues"] = array();
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["returnValues"][] = \spectrum\builders\be("aaa");
			\spectrum\tests\Test::$temp["returnValues"][] = \spectrum\builders\be("aaa");
		', 'onEndingSpecExecute');
		
		\spectrum\_internal\getRootSpec()->run();
		
		$this->assertSame(2, count(\spectrum\tests\Test::$temp["returnValues"]));
		$this->assertInstanceOf('\spectrum\core\Assert', \spectrum\tests\Test::$temp["returnValues"][0]);
		$this->assertInstanceOf('\spectrum\core\Assert', \spectrum\tests\Test::$temp["returnValues"][1]);
		$this->assertNotSame(\spectrum\tests\Test::$temp["returnValues"][0], \spectrum\tests\Test::$temp["returnValues"][1]);
	}
	
	public function testCallsAtRunningState_UsesConfigForAssertClassGetting()
	{
		$assertClassName = $this->createClass('class ... extends \spectrum\core\Assert {}');
		config::setClassReplacement('\spectrum\core\Assert', $assertClassName);

		\spectrum\tests\Test::$temp["returnValue"] = null;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["returnValue"] = \spectrum\builders\be("aaa");
		', 'onEndingSpecExecute');
		
		\spectrum\_internal\getRootSpec()->run();
		
		$this->assertInstanceOf($assertClassName, \spectrum\tests\Test::$temp["returnValue"]);
	}
	
	public function testCallsAtRunningState_PassesToAssertInstanceCurrentRunningSpecAndTestedValue()
	{
		\spectrum\tests\Test::$temp["assert"] = null;
		\spectrum\tests\Test::$temp["passedArguments"] = array();
		
		config::setClassReplacement('\spectrum\core\Assert', $this->createClass('
			class ... extends \spectrum\core\Assert
			{
				public function __construct(\spectrum\core\SpecInterface $ownerSpec, $testedValue)
				{
					\spectrum\tests\Test::$temp["assert"] = $this;
					\spectrum\tests\Test::$temp["passedArguments"] = func_get_args();
				}
			}
		'));
		
		\spectrum\tests\Test::$temp["returnValue"] = null;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["returnValue"] = \spectrum\builders\be("aaa");
		', 'onEndingSpecExecute');
		
		$spec = new Spec();
		\spectrum\_internal\getRootSpec()->bindChildSpec($spec);
		\spectrum\_internal\getRootSpec()->run();

		$this->assertInstanceOf('\spectrum\core\Assert', \spectrum\tests\Test::$temp["assert"]);
		$this->assertSame(\spectrum\tests\Test::$temp["assert"], \spectrum\tests\Test::$temp["returnValue"]);
		$this->assertSame(2, count(\spectrum\tests\Test::$temp["passedArguments"]));
		$this->assertInstanceOf('\spectrum\core\Spec', \spectrum\tests\Test::$temp["passedArguments"][0]);
		$this->assertSame($spec, \spectrum\tests\Test::$temp["passedArguments"][0]);
		$this->assertSame('aaa', \spectrum\tests\Test::$temp["passedArguments"][1]);
	}
	
	public function testCallsAtBuildingState_ThrowsException()
	{
		$this->assertThrowsException('\spectrum\builders\Exception', 'Builder "be" should be call only at running state', function(){
			\spectrum\builders\be("aaa");
		});
	}
}