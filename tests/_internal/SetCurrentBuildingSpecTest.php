<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\_internal;

use spectrum\core\Spec;

require_once __DIR__ . '/../init.php';

class SetCurrentBuildingSpecTest extends \spectrum\tests\Test
{
	public function testCallsAtBuildingState_ArgumentIsNull_SetsNullToStaticVariable()
	{
		$reflection = new \ReflectionFunction('spectrum\_internal\setCurrentBuildingSpec');
		
		$spec = new Spec();
		\spectrum\_internal\setCurrentBuildingSpec($spec);
		$staticVariables = $reflection->getStaticVariables();
		$this->assertSame($spec, $staticVariables['buildingSpec']);
		
		\spectrum\_internal\setCurrentBuildingSpec(null);
		$staticVariables = $reflection->getStaticVariables();
		$this->assertSame(null, $staticVariables['buildingSpec']);
	}
	
	public function testCallsAtBuildingState_ArgumentIsSpec_SetsSpecToStaticVariable()
	{
		$reflection = new \ReflectionFunction('spectrum\_internal\setCurrentBuildingSpec');
		
		$spec = new Spec();
		\spectrum\_internal\setCurrentBuildingSpec($spec);
		$staticVariables = $reflection->getStaticVariables();
		$this->assertSame($spec, $staticVariables['buildingSpec']);
		
		$spec = new Spec();
		\spectrum\_internal\setCurrentBuildingSpec($spec);
		$staticVariables = $reflection->getStaticVariables();
		$this->assertSame($spec, $staticVariables['buildingSpec']);
		
		\spectrum\_internal\setCurrentBuildingSpec(null);
		$staticVariables = $reflection->getStaticVariables();
		$this->assertSame(null, $staticVariables['buildingSpec']);
		
		$spec = new Spec();
		\spectrum\_internal\setCurrentBuildingSpec($spec);
		$staticVariables = $reflection->getStaticVariables();
		$this->assertSame($spec, $staticVariables['buildingSpec']);
	}
}