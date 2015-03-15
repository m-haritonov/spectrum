<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core\constructs;

use spectrum\core\models\SpecInterface;

require_once __DIR__ . '/../../../init.php';

class SelfTest extends \spectrum\tests\automatic\Test {
	public function testCallsAtRunningState_ReturnsCurrentRunningSpec() {
		$self = null;
		$expected = \spectrum\core\constructs\test(function() use(&$self){
			$self = \spectrum\core\constructs\self();
		});
		
		$expected->run();
		
		$this->assertSame($expected, $self);
		$this->assertTrue($self instanceof SpecInterface);
	}
	
	public function testCallsAtBuildingState_ReturnsCurrentBuildingSpec() {
		$self = null;
		$expected = \spectrum\core\constructs\group(function() use(&$self){
			$self = \spectrum\core\constructs\self();
		});
		
		$this->assertSame($expected, $self);
		$this->assertTrue($self instanceof SpecInterface);
	}
}