<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\constructionCommands\commands\internal;

use spectrum\constructionCommands\callBroker;

require_once __DIR__ . '/../../../init.php';

class GetRootSpecTest extends \spectrum\tests\Test
{
	public function testCallsAtDeclaringState_ReturnsSameSpecOnEachCall()
	{
		$rootSpec = callBroker::internal_getRootSpec();
		$this->assertInstanceOf('\spectrum\core\Spec', $rootSpec);

		$this->assertSame($rootSpec, callBroker::internal_getRootSpec());
		$this->assertSame($rootSpec, callBroker::internal_getRootSpec());
		$this->assertSame($rootSpec, callBroker::internal_getRootSpec());
	}
	
	public function testCallsAtDeclaringState_AddsToSpecBaseMatchers()
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
		), callBroker::internal_getRootSpec()->matchers->getAll());
	}
}