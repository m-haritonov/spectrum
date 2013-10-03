<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\constructionCommands\commands\internal;

use spectrum\constructionCommands\callBroker;
use spectrum\core\Spec;

require_once __DIR__ . '/../../../init.php';

class GetDeclaringSpecTest extends \spectrum\tests\Test
{
	public function testCallsAtDeclaringState_ReturnsDeclaringSpec()
	{
		$spec = new Spec();
		callBroker::internal_setDeclaringSpec($spec);
		$this->assertSame($spec, callBroker::internal_getDeclaringSpec());
		
		$spec = new Spec();
		callBroker::internal_setDeclaringSpec($spec);
		$this->assertSame($spec, callBroker::internal_getDeclaringSpec());
	}
	
	public function testCallsAtDeclaringState_DeclaringSpecIsNotSet_ReturnsRootSpec()
	{
		$rootSpec = callBroker::internal_getRootSpec();
		$this->assertInstanceOf('\spectrum\core\SpecInterface', $rootSpec);
		$this->assertSame($rootSpec, callBroker::internal_getDeclaringSpec());
		
		callBroker::internal_setDeclaringSpec(new Spec());
		callBroker::internal_setDeclaringSpec(null);
		$this->assertSame($rootSpec, callBroker::internal_getDeclaringSpec());
	}
}