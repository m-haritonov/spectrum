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

class GetExclusionSpecsTest extends \spectrum\tests\Test
{
	public function testCallsAtDeclaringState_ReturnsArrayWithExclusionSpecs()
	{
		$spec1 = new Spec();
		callBroker::internal_addExclusionSpec($spec1);
		$this->assertSame(array($spec1), callBroker::internal_getExclusionSpecs());
		
		$spec2 = new Spec();
		callBroker::internal_addExclusionSpec($spec2);
		$this->assertSame(array($spec1, $spec2), callBroker::internal_getExclusionSpecs());
		
		$spec3 = new Spec();
		callBroker::internal_addExclusionSpec($spec3);
		$this->assertSame(array($spec1, $spec2, $spec3), callBroker::internal_getExclusionSpecs());
	}
	
	public function testCallsAtDeclaringState_ThereAreNoInclusionSpecs_ReturnsEmptyArray()
	{
		$this->assertSame(array(), callBroker::internal_getExclusionSpecs());
	}
}