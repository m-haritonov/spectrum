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
		
		$this->assertTrue(function_exists('\spectrum\_private\matchers\eq'));
		$this->assertTrue(function_exists('\spectrum\_private\matchers\gt'));
		$this->assertTrue(function_exists('\spectrum\_private\matchers\gte'));
		$this->assertTrue(function_exists('\spectrum\_private\matchers\ident'));
		$this->assertTrue(function_exists('\spectrum\_private\matchers\is'));
		$this->assertTrue(function_exists('\spectrum\_private\matchers\lt'));
		$this->assertTrue(function_exists('\spectrum\_private\matchers\lte'));
		$this->assertTrue(function_exists('\spectrum\_private\matchers\throwsException'));
		
		$this->assertSame(array(
			'eq' => '\spectrum\_private\matchers\eq',
			'gt' => '\spectrum\_private\matchers\gt',
			'gte' => '\spectrum\_private\matchers\gte',
			'ident' => '\spectrum\_private\matchers\ident',
			'is' => '\spectrum\_private\matchers\is',
			'lt' => '\spectrum\_private\matchers\lt',
			'lte' => '\spectrum\_private\matchers\lte',
			'throwsException' => '\spectrum\_private\matchers\throwsException',
		), $matchers);
	}
	
	public function testCallsAtBuildingState_IncludesBaseMatcherFunctionsOnce() {
		\spectrum\_private\loadBaseMatchers();
		\spectrum\_private\loadBaseMatchers();
	}
}