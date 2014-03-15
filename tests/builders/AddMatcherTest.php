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

class AddMatcherTest extends \spectrum\tests\Test
{
	public function testCallsAtBuildingState_AddsMatcherFunctionToCurrentBuildingSpec()
	{
		$spec = new Spec();
		\spectrum\_internal\setBuildingSpec($spec);
		
		$function = function(){};
		\spectrum\builders\addMatcher('aaa', $function);

		$this->assertSame($function, $spec->matchers->get('aaa'));
	}

	public function testCallsAtBuildingState_ReturnsReturnValueOfMatcherAddFunction()
	{
		config::unregisterSpecPlugins('\spectrum\core\plugins\Matchers');
		config::registerSpecPlugin($this->createClass('
			class ... extends \spectrum\core\plugins\Matchers
			{
				public function add($name, $function)
				{
					return "some text";
				}
			}
		'));
		
		$this->assertSame('some text', \spectrum\builders\addMatcher('aaa', function(){}));
	}
	
	public function testCallsAtRunningState_ThrowsException()
	{
		\spectrum\tests\Test::$temp["exception"] = null;
		
		$this->registerPluginWithCodeInEvent('
			try
			{
				\spectrum\builders\addMatcher("aaa", function(){});
			}
			catch (\Exception $e)
			{
				\spectrum\tests\Test::$temp["exception"] = $e;
			}
		', 'onEndingSpecExecute');
		
		\spectrum\_internal\getRootSpec()->run();
		
		$this->assertInstanceOf('\spectrum\builders\Exception', \spectrum\tests\Test::$temp["exception"]);
		$this->assertSame('Builder "addMatcher" should be call only at building state', \spectrum\tests\Test::$temp["exception"]->getMessage());
	}
}