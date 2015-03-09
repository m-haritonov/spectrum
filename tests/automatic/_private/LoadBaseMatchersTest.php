<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\_private;

require_once __DIR__ . '/../../init.php';

class LoadBaseMatchersTest extends \spectrum\tests\automatic\Test {
	public function testCallsAtBuildingState_IncludesBaseMatcherFunctionsAndReturnsBaseMatchers() {
		$matchers = \spectrum\_private\loadBaseMatchers();
		
		$this->assertTrue(function_exists('\spectrum\core\matchers\eq'));
		$this->assertTrue(function_exists('\spectrum\core\matchers\gt'));
		$this->assertTrue(function_exists('\spectrum\core\matchers\gte'));
		$this->assertTrue(function_exists('\spectrum\core\matchers\ident'));
		$this->assertTrue(function_exists('\spectrum\core\matchers\is'));
		$this->assertTrue(function_exists('\spectrum\core\matchers\lt'));
		$this->assertTrue(function_exists('\spectrum\core\matchers\lte'));
		$this->assertTrue(function_exists('\spectrum\core\matchers\throwsException'));
		
		$this->assertSame(array(
			'eq' => '\spectrum\core\matchers\eq',
			'gt' => '\spectrum\core\matchers\gt',
			'gte' => '\spectrum\core\matchers\gte',
			'ident' => '\spectrum\core\matchers\ident',
			'is' => '\spectrum\core\matchers\is',
			'lt' => '\spectrum\core\matchers\lt',
			'lte' => '\spectrum\core\matchers\lte',
			'throwsException' => '\spectrum\core\matchers\throwsException',
		), $matchers);
	}
	
	public function testCallsAtBuildingState_IncludesBaseMatcherFunctionsOnce() {
		\spectrum\_private\loadBaseMatchers();
		\spectrum\_private\loadBaseMatchers();
	}
}