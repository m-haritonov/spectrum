<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\constructionCommands\commands\internal;

use spectrum\constructionCommands\callBroker;
use spectrum\core\Spec;

require_once __DIR__ . '/../../../init.php';

class FilterOutExclusionSpecsTest extends \spectrum\tests\Test
{
	public function testCallsAtDeclaringState_ReturnsSpecsWithoutExclusionSpecs()
	{
		$spec1 = new Spec();
		$spec2 = new Spec();
		$spec3 = new Spec();
		$spec4 = new Spec();
		$spec5 = new Spec();
		
		$this->assertSame(array($spec1, $spec2, $spec3, $spec4, $spec5), callBroker::internal_filterOutExclusionSpecs(array($spec1, $spec2, $spec3, $spec4, $spec5)));
		
		callBroker::internal_addExclusionSpec($spec2);
		$this->assertSame(array($spec1, $spec3, $spec4, $spec5), callBroker::internal_filterOutExclusionSpecs(array($spec1, $spec2, $spec3, $spec4, $spec5)));
		
		callBroker::internal_addExclusionSpec($spec5);
		$this->assertSame(array($spec1, $spec3, $spec4), callBroker::internal_filterOutExclusionSpecs(array($spec1, $spec2, $spec3, $spec4, $spec5)));
		
		callBroker::internal_addExclusionSpec($spec1);
		$this->assertSame(array($spec3, $spec4), callBroker::internal_filterOutExclusionSpecs(array($spec1, $spec2, $spec3, $spec4, $spec5)));
		
		callBroker::internal_addExclusionSpec($spec3);
		$this->assertSame(array($spec4), callBroker::internal_filterOutExclusionSpecs(array($spec1, $spec2, $spec3, $spec4, $spec5)));
		
		callBroker::internal_addExclusionSpec($spec4);
		$this->assertSame(array(), callBroker::internal_filterOutExclusionSpecs(array($spec1, $spec2, $spec3, $spec4, $spec5)));
	}

	public function testCallsAtDeclaringState_NoSpecs_ReturnsEmptyArray()
	{
		$this->assertSame(array(), callBroker::internal_filterOutExclusionSpecs(array()));
	}
}