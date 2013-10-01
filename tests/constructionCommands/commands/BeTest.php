<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\constructionCommands\commands;

use spectrum\config;
use spectrum\constructionCommands\callBroker;
use spectrum\core\Spec;

require_once __DIR__ . '/../../init.php';

class BeTest extends \spectrum\tests\Test
{
	public function testCallsAtRunningState_ReturnsNewAssertInstance()
	{
		\spectrum\tests\Test::$temp["returnValues"] = array();
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["returnValues"][] = \spectrum\constructionCommands\callBroker::be("aaa");
			\spectrum\tests\Test::$temp["returnValues"][] = \spectrum\constructionCommands\callBroker::be("aaa");
		', 'onEndingSpecExecute');
		
		callBroker::internal_getRootSpec()->run();
		
		$this->assertSame(2, count(\spectrum\tests\Test::$temp["returnValues"]));
		$this->assertInstanceOf('\spectrum\core\Assert', \spectrum\tests\Test::$temp["returnValues"][0]);
		$this->assertInstanceOf('\spectrum\core\Assert', \spectrum\tests\Test::$temp["returnValues"][1]);
		$this->assertNotSame(\spectrum\tests\Test::$temp["returnValues"][0], \spectrum\tests\Test::$temp["returnValues"][1]);
	}
	
	public function testCallsAtRunningState_UsesConfigForAssertClassGetting()
	{
		$assertClassName = $this->createClass('class ... extends \spectrum\core\Assert {}');
		config::setAssertClass($assertClassName);

		\spectrum\tests\Test::$temp["returnValue"] = null;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["returnValue"] = \spectrum\constructionCommands\callBroker::be("aaa");
		', 'onEndingSpecExecute');
		
		callBroker::internal_getRootSpec()->run();
		
		$this->assertInstanceOf($assertClassName, \spectrum\tests\Test::$temp["returnValue"]);
	}
	
	public function testCallsAtRunningState_PassesToAssertInstanceCurrentRunningSpecAndTestedValue()
	{
		\spectrum\tests\Test::$temp["assert"] = null;
		\spectrum\tests\Test::$temp["passedArguments"] = array();
		
		config::setAssertClass($this->createClass('
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
			\spectrum\tests\Test::$temp["returnValue"] = \spectrum\constructionCommands\callBroker::be("aaa");
		', 'onEndingSpecExecute');
		
		$spec = new Spec();
		callBroker::internal_getRootSpec()->bindChildSpec($spec);
		callBroker::internal_getRootSpec()->run();

		$this->assertInstanceOf('\spectrum\core\Assert', \spectrum\tests\Test::$temp["assert"]);
		$this->assertSame(\spectrum\tests\Test::$temp["assert"], \spectrum\tests\Test::$temp["returnValue"]);
		$this->assertSame(2, count(\spectrum\tests\Test::$temp["passedArguments"]));
		$this->assertInstanceOf('\spectrum\core\Spec', \spectrum\tests\Test::$temp["passedArguments"][0]);
		$this->assertSame($spec, \spectrum\tests\Test::$temp["passedArguments"][0]);
		$this->assertSame('aaa', \spectrum\tests\Test::$temp["passedArguments"][1]);
	}
	
	public function testCallsAtDeclaringState_ThrowsException()
	{
		$this->assertThrowsException('\spectrum\constructionCommands\Exception', 'Construction command "be" should be call only at running state', function(){
			\spectrum\constructionCommands\callBroker::be("aaa");
		});
	}
}