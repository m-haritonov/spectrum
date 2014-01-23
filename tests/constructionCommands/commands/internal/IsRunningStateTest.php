<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\constructionCommands\commands\internal;

use spectrum\constructionCommands\callBroker;

require_once __DIR__ . '/../../../init.php';

class IsRunningStateTest extends \spectrum\tests\Test
{
	public function testCallsAtDeclaringState_ReturnsFalse()
	{
		$this->assertSame(false, callBroker::internal_isRunningState());
	}
	
	public function testCallsAtRunningState_ReturnsTrue()
	{
		\spectrum\tests\Test::$temp["returnValue"] = null;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["returnValue"] = \spectrum\constructionCommands\callBroker::internal_isRunningState();
		', 'onEndingSpecExecute');
		
		callBroker::internal_getRootSpec()->run();
		
		$this->assertSame(true, \spectrum\tests\Test::$temp["returnValue"]);
	}
	
	public function testCallsAtRunningState_CallsFromCustomSpecClass_ReturnsTrue()
	{
		\spectrum\tests\Test::$temp["returnValue"] = null;
		
		$specClassName = $this->createClass('
			class ... implements \spectrum\core\SpecInterface
			{
				public function __get($pluginAccessName){}
				
				public function enable(){}
				public function disable(){}
				public function isEnabled(){}
				
				public function setName($name){}
				public function getName(){}
				public function isAnonymous(){}
				
				public function getSpecId(){}
				public function getSpecById($specId){}
			
				public function getParentSpecs(){}
				public function hasParentSpec(\spectrum\core\SpecInterface $spec){}
				public function bindParentSpec(\spectrum\core\SpecInterface $spec){}
				public function unbindParentSpec(\spectrum\core\SpecInterface $spec){}
				public function unbindAllParentSpecs(){}
			
				public function getChildSpecs(){}
				public function getChildSpecsByName($name){}
				public function getChildSpecByNumber($number){}
				public function hasChildSpec(\spectrum\core\SpecInterface $spec){}
				public function bindChildSpec(\spectrum\core\SpecInterface $spec){}
				public function unbindChildSpec(\spectrum\core\SpecInterface $spec){}
				public function unbindAllChildSpecs(){}
				
				public function getRootSpecs(){}
				public function getEndingSpecs(){}
				public function getRunningParentSpec(){}
				public function getRunningAncestorSpecs(){}
				public function getRunningChildSpec(){}
				public function getRunningEndingSpec(){}
			
				public function getResultBuffer(){}
				public function isRunning(){}
				public function run()
				{
					\spectrum\tests\Test::$temp["returnValue"] = \spectrum\constructionCommands\callBroker::internal_isRunningState();
				}
			}
		');
		
		$spec = new $specClassName();
		$spec->run();
		
		$this->assertSame(true, \spectrum\tests\Test::$temp["returnValue"]);
	}
}