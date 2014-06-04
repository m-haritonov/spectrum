<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\_internals;

require_once __DIR__ . '/../init.php';

class LoadBaseMatchersTest extends \spectrum\tests\Test
{
	public function testCallsAtBuildingState_IncludesBaseMatcherFunctionsAndReturnsBaseMatchers()
	{
		$matchers = \spectrum\_internals\loadBaseMatchers();
		
		$this->assertTrue(function_exists('\spectrum\matchers\eq'));
		$this->assertTrue(function_exists('\spectrum\matchers\false'));
		$this->assertTrue(function_exists('\spectrum\matchers\gt'));
		$this->assertTrue(function_exists('\spectrum\matchers\gte'));
		$this->assertTrue(function_exists('\spectrum\matchers\ident'));
		$this->assertTrue(function_exists('\spectrum\matchers\instanceofMatcher'));
		$this->assertTrue(function_exists('\spectrum\matchers\lt'));
		$this->assertTrue(function_exists('\spectrum\matchers\lte'));
		$this->assertTrue(function_exists('\spectrum\matchers\null'));
		$this->assertTrue(function_exists('\spectrum\matchers\throwsException'));
		$this->assertTrue(function_exists('\spectrum\matchers\true'));
		
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
		), $matchers);
	}
	
	public function testCallsAtBuildingState_IncludesBaseMatcherFunctionsOnce()
	{
		\spectrum\_internals\loadBaseMatchers();
		\spectrum\_internals\loadBaseMatchers();
	}
}