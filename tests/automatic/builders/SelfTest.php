<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\builders;

use spectrum\core\SpecInterface;

require_once __DIR__ . '/../../init.php';

class SelfTest extends \spectrum\tests\automatic\Test {
	public function testCallsAtRunningState_ReturnsCurrentRunningSpec() {
		$self = null;
		$expected = \spectrum\builders\test(function() use(&$self){
			$self = \spectrum\builders\self();
		});
		
		$expected->run();
		
		$this->assertSame($expected, $self);
		$this->assertTrue($self instanceof SpecInterface);
	}
	
	public function testCallsAtBuildingState_ReturnsCurrentBuildingSpec() {
		$self = null;
		$expected = \spectrum\builders\group(function() use(&$self){
			$self = \spectrum\builders\self();
		});
		
		$this->assertSame($expected, $self);
		$this->assertTrue($self instanceof SpecInterface);
	}
}