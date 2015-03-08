<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic;

use spectrum\core\SpecInterface;

require_once __DIR__ . '/../init.php';

class SelfTest extends \spectrum\tests\automatic\Test {
	public function testCallsAtRunningState_ReturnsCurrentRunningSpec() {
		$self = null;
		$expected = \spectrum\test(function() use(&$self){
			$self = \spectrum\self();
		});
		
		$expected->run();
		
		$this->assertSame($expected, $self);
		$this->assertTrue($self instanceof SpecInterface);
	}
	
	public function testCallsAtBuildingState_ReturnsCurrentBuildingSpec() {
		$self = null;
		$expected = \spectrum\group(function() use(&$self){
			$self = \spectrum\self();
		});
		
		$this->assertSame($expected, $self);
		$this->assertTrue($self instanceof SpecInterface);
	}
}