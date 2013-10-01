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

class AddMatcherTest extends \spectrum\tests\Test
{
	public function testCallsAtDeclaringState_AddsMatcherFunctionToCurrentDeclaringSpec()
	{
		$spec = new Spec();
		callBroker::internal_setDeclaringSpec($spec);
		
		$function = function(){};
		callBroker::addMatcher('aaa', $function);

		$this->assertSame($function, $spec->matchers->get('aaa'));
	}

	public function testCallsAtDeclaringState_ReturnsReturnValueOfMatcherAddFunction()
	{
		config::unregisterSpecPlugins('\spectrum\core\plugins\basePlugins\Matchers');
		config::registerSpecPlugin($this->createClass('
			class ... extends \spectrum\core\plugins\basePlugins\Matchers
			{
				public function add($name, $function)
				{
					return "some text";
				}
			}
		'));
		
		$this->assertSame('some text', callBroker::addMatcher('aaa', function(){}));
	}
	
	public function testCallsAtRunningState_ThrowsException()
	{
		\spectrum\tests\Test::$temp["exception"] = null;
		
		$this->registerPluginWithCodeInEvent('
			try
			{
				\spectrum\constructionCommands\callBroker::addMatcher("aaa", function(){});
			}
			catch (\Exception $e)
			{
				\spectrum\tests\Test::$temp["exception"] = $e;
			}
		', 'onEndingSpecExecute');
		
		callBroker::internal_getRootSpec()->run();
		
		$this->assertInstanceOf('\spectrum\constructionCommands\Exception', \spectrum\tests\Test::$temp["exception"]);
		$this->assertSame('Construction command "addMatcher" should be call only at declaring state', \spectrum\tests\Test::$temp["exception"]->getMessage());
	}
}