<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core\_private;

require_once __DIR__ . '/../../../init.php';

class GetRootSpecTest extends \spectrum\tests\automatic\Test {
	public function testCallsAtBuildingState_ReturnsSameSpecOnEachCall() {
		$rootSpec = \spectrum\core\_private\getRootSpec();
		$this->assertInstanceOf('\spectrum\core\Spec', $rootSpec);

		$this->assertSame($rootSpec, \spectrum\core\_private\getRootSpec());
		$this->assertSame($rootSpec, \spectrum\core\_private\getRootSpec());
		$this->assertSame($rootSpec, \spectrum\core\_private\getRootSpec());
	}
	
	public function testCallsAtBuildingState_AddsToSpecBaseMatchers() {
		$this->assertSame(array(
			'eq' => '\spectrum\core\matchers\eq',
			'gt' => '\spectrum\core\matchers\gt',
			'gte' => '\spectrum\core\matchers\gte',
			'ident' => '\spectrum\core\matchers\ident',
			'is' => '\spectrum\core\matchers\is',
			'lt' => '\spectrum\core\matchers\lt',
			'lte' => '\spectrum\core\matchers\lte',
			'throwsException' => '\spectrum\core\matchers\throwsException',
		), \spectrum\core\_private\getRootSpec()->getMatchers()->getAll());
	}
}