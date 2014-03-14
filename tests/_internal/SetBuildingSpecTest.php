<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\_internal;

use spectrum\core\Spec;

require_once __DIR__ . '/../init.php';

class SetBuildingSpecTest extends \spectrum\tests\Test
{
	public function testCallsAtBuildingState_ArgumentIsNull_SetsNullToStaticVariable()
	{
		$reflection = new \ReflectionFunction('spectrum\_internal\setBuildingSpec');
		
		$spec = new Spec();
		\spectrum\_internal\setBuildingSpec($spec);
		$staticVariables = $reflection->getStaticVariables();
		$this->assertSame($spec, $staticVariables['buildingSpec']);
		
		\spectrum\_internal\setBuildingSpec(null);
		$staticVariables = $reflection->getStaticVariables();
		$this->assertSame(null, $staticVariables['buildingSpec']);
	}
	
	public function testCallsAtBuildingState_ArgumentIsSpec_SetsSpecToStaticVariable()
	{
		$reflection = new \ReflectionFunction('spectrum\_internal\setBuildingSpec');
		
		$spec = new Spec();
		\spectrum\_internal\setBuildingSpec($spec);
		$staticVariables = $reflection->getStaticVariables();
		$this->assertSame($spec, $staticVariables['buildingSpec']);
		
		$spec = new Spec();
		\spectrum\_internal\setBuildingSpec($spec);
		$staticVariables = $reflection->getStaticVariables();
		$this->assertSame($spec, $staticVariables['buildingSpec']);
		
		\spectrum\_internal\setBuildingSpec(null);
		$staticVariables = $reflection->getStaticVariables();
		$this->assertSame(null, $staticVariables['buildingSpec']);
		
		$spec = new Spec();
		\spectrum\_internal\setBuildingSpec($spec);
		$staticVariables = $reflection->getStaticVariables();
		$this->assertSame($spec, $staticVariables['buildingSpec']);
	}
}