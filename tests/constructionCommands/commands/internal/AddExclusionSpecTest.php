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

class AddExclusionSpecTest extends \spectrum\tests\Test
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
	
	public function testCallsAtDeclaringState_AddsSpecToStorage()
	{
		$spec1 = new Spec();
		$spec2 = new Spec();
		$spec3 = new Spec();
		
		callBroker::internal_addExclusionSpec($spec1);
		$this->assertSame(array(
			'internal_addExclusionSpec' => array(
				'exclusionSpecs' => array($spec1),
			),
		), callBroker::getStorage());
		
		callBroker::internal_addExclusionSpec($spec2);
		$this->assertSame(array(
			'internal_addExclusionSpec' => array(
				'exclusionSpecs' => array($spec1, $spec2),
			),
		), callBroker::getStorage());
		
		callBroker::internal_addExclusionSpec($spec3);
		$this->assertSame(array(
			'internal_addExclusionSpec' => array(
				'exclusionSpecs' => array($spec1, $spec2, $spec3),
			),
		), callBroker::getStorage());
	}
}