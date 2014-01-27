<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\builders\internal;

use spectrum\core\Spec;

require_once __DIR__ . '/../../init.php';

class GetExclusionSpecsTest extends \spectrum\tests\Test
{
	public function testCallsAtBuildingState_ReturnsArrayWithExclusionSpecs()
	{
		$spec1 = new Spec();
		\spectrum\builders\internal\addExclusionSpec($spec1);
		$this->assertTrue(in_array($spec1, \spectrum\builders\internal\getExclusionSpecs()));
		
		$spec2 = new Spec();
		\spectrum\builders\internal\addExclusionSpec($spec2);
		$this->assertTrue(in_array($spec1, \spectrum\builders\internal\getExclusionSpecs()));
		$this->assertTrue(in_array($spec2, \spectrum\builders\internal\getExclusionSpecs()));
		
		$spec3 = new Spec();
		\spectrum\builders\internal\addExclusionSpec($spec3);
		$this->assertTrue(in_array($spec1, \spectrum\builders\internal\getExclusionSpecs()));
		$this->assertTrue(in_array($spec2, \spectrum\builders\internal\getExclusionSpecs()));
		$this->assertTrue(in_array($spec3, \spectrum\builders\internal\getExclusionSpecs()));
	}
}