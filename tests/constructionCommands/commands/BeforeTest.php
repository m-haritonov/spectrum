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

class BeforeTest extends \spectrum\tests\Test
{
	public function testCallsAtDeclaringState_AddsContextFunctionWithBeforeTypeToCurrentDeclaringSpec()
	{
		$spec = new Spec();
		callBroker::internal_setDeclaringSpec($spec);
		
		$function1 = function(){};
		$function2 = function(){};
		callBroker::before($function1);
		callBroker::before($function2);

		$this->assertSame(array(
			array('function' => $function1, 'type' => 'before'),
			array('function' => $function2, 'type' => 'before'),
		), $spec->contexts->getAll());
	}

	public function testCallsAtDeclaringState_ReturnsReturnValueOfContextAddFunction()
	{
		config::unregisterSpecPlugins('\spectrum\core\plugins\basePlugins\contexts\Contexts');
		config::registerSpecPlugin($this->createClass('
			class ... extends \spectrum\core\plugins\basePlugins\contexts\Contexts
			{
				public function add($function, $type = "before")
				{
					return "some text";
				}
			}
		'));
		
		$this->assertSame('some text', callBroker::before(function(){}));
	}
	
	public function testCallsAtRunningState_ThrowsException()
	{
		\spectrum\tests\Test::$temp["exception"] = null;
		
		$this->registerPluginWithCodeInEvent('
			try
			{
				\spectrum\constructionCommands\callBroker::before(function(){});
			}
			catch (\Exception $e)
			{
				\spectrum\tests\Test::$temp["exception"] = $e;
			}
		', 'onEndingSpecExecute');
		
		callBroker::internal_getRootSpec()->run();
		
		$this->assertInstanceOf('\spectrum\constructionCommands\Exception', \spectrum\tests\Test::$temp["exception"]);
		$this->assertSame('Construction command "before" should be call only at declaring state', \spectrum\tests\Test::$temp["exception"]->getMessage());
	}
}