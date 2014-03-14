<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\_internal;

use spectrum\core\Spec;

require_once __DIR__ . '/../init.php';

class FilterOutExclusionSpecsTest extends \spectrum\tests\Test
{
	public function testCallsAtBuildingState_ReturnsSpecsWithoutExclusionSpecs()
	{
		$spec1 = new Spec();
		$spec2 = new Spec();
		$spec3 = new Spec();
		$spec4 = new Spec();
		$spec5 = new Spec();
		
		$this->assertSame(array($spec1, $spec2, $spec3, $spec4, $spec5), \spectrum\_internal\filterOutExclusionSpecs(array($spec1, $spec2, $spec3, $spec4, $spec5)));
		
		\spectrum\_internal\addExclusionSpec($spec2);
		$this->assertSame(array($spec1, $spec3, $spec4, $spec5), \spectrum\_internal\filterOutExclusionSpecs(array($spec1, $spec2, $spec3, $spec4, $spec5)));
		
		\spectrum\_internal\addExclusionSpec($spec5);
		$this->assertSame(array($spec1, $spec3, $spec4), \spectrum\_internal\filterOutExclusionSpecs(array($spec1, $spec2, $spec3, $spec4, $spec5)));
		
		\spectrum\_internal\addExclusionSpec($spec1);
		$this->assertSame(array($spec3, $spec4), \spectrum\_internal\filterOutExclusionSpecs(array($spec1, $spec2, $spec3, $spec4, $spec5)));
		
		\spectrum\_internal\addExclusionSpec($spec3);
		$this->assertSame(array($spec4), \spectrum\_internal\filterOutExclusionSpecs(array($spec1, $spec2, $spec3, $spec4, $spec5)));
		
		\spectrum\_internal\addExclusionSpec($spec4);
		$this->assertSame(array(), \spectrum\_internal\filterOutExclusionSpecs(array($spec1, $spec2, $spec3, $spec4, $spec5)));
	}

	public function testCallsAtBuildingState_NoSpecs_ReturnsEmptyArray()
	{
		$this->assertSame(array(), \spectrum\_internal\filterOutExclusionSpecs(array()));
	}
}