<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\_internals;

require_once __DIR__ . '/../../init.php';

class IsRunningStateTest extends \spectrum\tests\automatic\Test {
	public function testCallsAtBuildingState_ReturnsFalse() {
		$this->assertSame(false, \spectrum\_internals\isRunningState());
	}
	
	public function testCallsAtRunningState_ReturnsTrue() {
		\spectrum\tests\automatic\Test::$temp["returnValue"] = null;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\automatic\Test::$temp["returnValue"] = \spectrum\_internals\isRunningState();
		', 'onEndingSpecExecute');
		
		\spectrum\_internals\getRootSpec()->run();
		
		$this->assertSame(true, \spectrum\tests\automatic\Test::$temp["returnValue"]);
	}
	
	public function testCallsAtRunningState_CallsFromCustomSpecClass_ReturnsTrue() {
		\spectrum\tests\automatic\Test::$temp["returnValue"] = null;
		
		$specClassName = $this->createClass('
			class ... implements \spectrum\core\SpecInterface {
				public function __get($pluginAccessName){}
				
				public function enable(){}
				public function disable(){}
				public function isEnabled(){}
			
				public function setName($name){}
				public function getName(){}
				public function isAnonymous(){}
				
				public function getParentSpecs(){}
				public function hasParentSpec(\spectrum\core\SpecInterface $spec){}
				public function bindParentSpec(\spectrum\core\SpecInterface $spec){}
				public function unbindParentSpec(\spectrum\core\SpecInterface $spec){}
				public function unbindAllParentSpecs(){}
			
				public function getChildSpecs(){}
				public function hasChildSpec(\spectrum\core\SpecInterface $spec){}
				public function bindChildSpec(\spectrum\core\SpecInterface $spec){}
				public function unbindChildSpec(\spectrum\core\SpecInterface $spec){}
				public function unbindAllChildSpecs(){}
				
				public function getAncestorRootSpecs(){}
				public function getDescendantEndingSpecs(){}
				public function getRunningParentSpec(){}
				public function getRunningAncestorSpecs(){}
				public function getRunningChildSpec(){}
				public function getRunningDescendantEndingSpec(){}
				public function getSpecsByRunId($id){}
			
				public function getResultBuffer(){}
				public function getRunId(){}
				public function isRunning(){}
				public function run() {
					\spectrum\tests\automatic\Test::$temp["returnValue"] = \spectrum\_internals\isRunningState();
				}
			}
		');
		
		$spec = new $specClassName();
		$spec->run();
		
		$this->assertSame(true, \spectrum\tests\automatic\Test::$temp["returnValue"]);
	}
}