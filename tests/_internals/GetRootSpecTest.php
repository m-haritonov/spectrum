<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\_internals;

require_once __DIR__ . '/../init.php';

class GetRootSpecTest extends \spectrum\tests\Test
{
	public function testCallsAtBuildingState_ReturnsSameSpecOnEachCall()
	{
		$rootSpec = \spectrum\_internals\getRootSpec();
		$this->assertInstanceOf('\spectrum\core\Spec', $rootSpec);

		$this->assertSame($rootSpec, \spectrum\_internals\getRootSpec());
		$this->assertSame($rootSpec, \spectrum\_internals\getRootSpec());
		$this->assertSame($rootSpec, \spectrum\_internals\getRootSpec());
	}
	
	public function testCallsAtBuildingState_AddsToSpecBaseMatchers()
	{
		$this->assertSame(array(
			'eq' => '\spectrum\matchers\eq',
			'gt' => '\spectrum\matchers\gt',
			'gte' => '\spectrum\matchers\gte',
			'ident' => '\spectrum\matchers\ident',
			'instanceof' => '\spectrum\matchers\instanceofMatcher',
			'lt' => '\spectrum\matchers\lt',
			'lte' => '\spectrum\matchers\lte',
			'throwsException' => '\spectrum\matchers\throwsException',
		), \spectrum\_internals\getRootSpec()->matchers->getAll());
	}
}