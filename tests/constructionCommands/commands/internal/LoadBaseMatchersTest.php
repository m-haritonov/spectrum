<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\constructionCommands\commands\internal;

use spectrum\constructionCommands\callBroker;
use spectrum\core\Spec;

require_once __DIR__ . '/../../../init.php';

class LoadBaseMatchersTest extends \spectrum\tests\Test
{
	public function testCallsAtDeclaringState_IncludesBaseMatcherFunctionsAndAddsBaseMatchersToPassedSpec()
	{
		$spec = new Spec();
		$this->assertSame(array(), $spec->matchers->getAll());
		
		callBroker::internal_loadBaseMatchers($spec);
		
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
		), $spec->matchers->getAll());
	}
	
	public function testCallsAtDeclaringState_IncludesBaseMatcherFunctionsOnce()
	{
		$spec = new Spec();
		callBroker::internal_loadBaseMatchers($spec);
		callBroker::internal_loadBaseMatchers($spec);
	}
}