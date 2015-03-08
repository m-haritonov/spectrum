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
			'eq' => '\spectrum\_private\matchers\eq',
			'gt' => '\spectrum\_private\matchers\gt',
			'gte' => '\spectrum\_private\matchers\gte',
			'ident' => '\spectrum\_private\matchers\ident',
			'is' => '\spectrum\_private\matchers\is',
			'lt' => '\spectrum\_private\matchers\lt',
			'lte' => '\spectrum\_private\matchers\lte',
			'throwsException' => '\spectrum\_private\matchers\throwsException',
		), \spectrum\_private\getRootSpec()->getMatchers()->getAll());
	}
}