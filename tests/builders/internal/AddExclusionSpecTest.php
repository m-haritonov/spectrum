<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\builders\internal;

use spectrum\core\Spec;

require_once __DIR__ . '/../../init.php';

class AddExclusionSpecTest extends \spectrum\tests\Test
{
	public function testCallsAtBuildingState_SavesSpecsToStaticVariable()
	{
		$spec1 = new Spec();
		$spec2 = new Spec();
		$spec3 = new Spec();
		
		$reflection = new \ReflectionFunction('spectrum\builders\internal\addExclusionSpec');
		
		\spectrum\builders\internal\addExclusionSpec($spec1);
		$staticVariables = $reflection->getStaticVariables();
		$this->assertTrue(in_array($spec1, $staticVariables['exclusionSpecs']));
		
		\spectrum\builders\internal\addExclusionSpec($spec2);
		$staticVariables = $reflection->getStaticVariables();
		$this->assertTrue(in_array($spec1, $staticVariables['exclusionSpecs']));
		$this->assertTrue(in_array($spec2, $staticVariables['exclusionSpecs']));
		
		\spectrum\builders\internal\addExclusionSpec($spec3);
		$staticVariables = $reflection->getStaticVariables();
		$this->assertTrue(in_array($spec1, $staticVariables['exclusionSpecs']));
		$this->assertTrue(in_array($spec2, $staticVariables['exclusionSpecs']));
		$this->assertTrue(in_array($spec3, $staticVariables['exclusionSpecs']));
	}
}