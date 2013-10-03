<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\constructionCommands\commands\internal;

use spectrum\config;
use spectrum\constructionCommands\callBroker;
use spectrum\core\Spec;

require_once __DIR__ . '/../../../init.php';

class SetDeclaringSpecTest extends \spectrum\tests\Test
{
	public function setUp()
	{
		parent::setUp();
		config::registerConstructionCommand('getStorage', function($storage){
			unset($storage['_self_']);
			unset($storage['getStorage']);
			return $storage;
		});
	}
	
	public function testCallsAtDeclaringState_SetsSpecOrNullToStorage()
	{
		$spec = new Spec();
		callBroker::internal_setDeclaringSpec($spec);
		$this->assertSame(array(
			'internal_setDeclaringSpec' => array(
				'declaringSpec' => $spec,
			),
		), callBroker::getStorage());
		
		$spec = new Spec();
		callBroker::internal_setDeclaringSpec($spec);
		$this->assertSame(array(
			'internal_setDeclaringSpec' => array(
				'declaringSpec' => $spec,
			),
		), callBroker::getStorage());
		
		callBroker::internal_setDeclaringSpec(null);
		$this->assertSame(array(
			'internal_setDeclaringSpec' => array(
				'declaringSpec' => null,
			),
		), callBroker::getStorage());
		
		$spec = new Spec();
		callBroker::internal_setDeclaringSpec($spec);
		$this->assertSame(array(
			'internal_setDeclaringSpec' => array(
				'declaringSpec' => $spec,
			),
		), callBroker::getStorage());
	}
}