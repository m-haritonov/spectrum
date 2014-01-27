<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\builders;

require_once __DIR__ . '/../init.php';

class GetRootSpecTest extends \spectrum\tests\Test
{
	public function testCallsAtBuildingState_ReturnsSameSpecOnEachCall()
	{
		$rootSpec = \spectrum\builders\getRootSpec();
		$this->assertInstanceOf('\spectrum\core\Spec', $rootSpec);

		$this->assertSame($rootSpec, \spectrum\builders\getRootSpec());
		$this->assertSame($rootSpec, \spectrum\builders\getRootSpec());
		$this->assertSame($rootSpec, \spectrum\builders\getRootSpec());
	}
	
	public function testCallsAtBuildingState_AddsToSpecBaseMatchers()
	{
		$this->assertSame(array(
			'eq' => '\spectrum\matchers\eq',
			'false' => '\spectrum\matchers\false',
			'gt' => '\spectrum\matchers\gt',
			'gte' => '\spectrum\matchers\gte',
			'ident' => '\spectrum\matchers\ident',
			'instanceof' => '\spectrum\matchers\instanceofMatcher',
			'lt' => '\spectrum\matchers\lt',
			'lte' => '\spectrum\matchers\lte',
			'null' => '\spectrum\matchers\null',
			'throwsException' => '\spectrum\matchers\throwsException',
			'true' => '\spectrum\matchers\true',
		), \spectrum\builders\getRootSpec()->matchers->getAll());
	}
}