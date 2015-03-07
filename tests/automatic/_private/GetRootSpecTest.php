<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\_private;

require_once __DIR__ . '/../../init.php';

class GetRootSpecTest extends \spectrum\tests\automatic\Test {
	public function testCallsAtBuildingState_ReturnsSameSpecOnEachCall() {
		$rootSpec = \spectrum\_private\getRootSpec();
		$this->assertInstanceOf('\spectrum\core\Spec', $rootSpec);

		$this->assertSame($rootSpec, \spectrum\_private\getRootSpec());
		$this->assertSame($rootSpec, \spectrum\_private\getRootSpec());
		$this->assertSame($rootSpec, \spectrum\_private\getRootSpec());
	}
	
	public function testCallsAtBuildingState_AddsToSpecBaseMatchers() {
		$this->assertSame(array(
			'eq' => '\spectrum\matchers\eq',
			'gt' => '\spectrum\matchers\gt',
			'gte' => '\spectrum\matchers\gte',
			'ident' => '\spectrum\matchers\ident',
			'is' => '\spectrum\matchers\is',
			'lt' => '\spectrum\matchers\lt',
			'lte' => '\spectrum\matchers\lte',
			'throwsException' => '\spectrum\matchers\throwsException',
		), \spectrum\_private\getRootSpec()->getMatchers()->getAll());
	}
}