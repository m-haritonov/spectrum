<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic;

use spectrum\config;

require_once __DIR__ . '/../init.php';

class RunTest extends Test {
	public function testRunsRootSpec() {
		\spectrum\_private\getRootSpec()->getTest()->setFunction(function() use(&$isRootSpecRun) {
			$isRootSpecRun = true;
		});
		
		\spectrum\run();
		
		$this->assertTrue($isRootSpecRun);
	}
	
	public function testReturnsRootSpecRunResult() {
		\spectrum\_private\getRootSpec()->getTest()->setFunction(function(){
			\spectrum\_private\getRootSpec()->getResultBuffer()->addResult(false);
		});
		
		$this->assertFalse(\spectrum\run());
		
		\spectrum\_private\getRootSpec()->getTest()->setFunction(function(){
			\spectrum\_private\getRootSpec()->getResultBuffer()->addResult(true);
		});
		
		$this->assertTrue(\spectrum\run());
		
		\spectrum\_private\getRootSpec()->getTest()->setFunction(function(){
			\spectrum\_private\getRootSpec()->getResultBuffer()->addResult(null);
		});
		
		$this->assertNull(\spectrum\run());
	}
	
	public function testLocksConfigBeforeRun() {
		\spectrum\_private\getRootSpec()->getTest()->setFunction(function() use(&$isLocked) {
			$isLocked = config::isLocked();
		});
		
		$this->assertFalse(config::isLocked());
		
		\spectrum\run();
		
		$this->assertTrue($isLocked);
		$this->assertTrue(config::isLocked());
	}
}