<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core\constructs;

use spectrum\core\config;

require_once __DIR__ . '/../../../init.php';

class RunTest extends \spectrum\tests\automatic\Test {
	public function testRunsRootSpec() {
		\spectrum\core\_private\getRootSpec()->getExecutor()->setFunction(function() use(&$isRootSpecRun) {
			$isRootSpecRun = true;
		});
		
		\spectrum\core\constructs\run();
		
		$this->assertTrue($isRootSpecRun);
	}
	
	public function testReturnsRootSpecRunResult() {
		\spectrum\core\_private\getRootSpec()->getExecutor()->setFunction(function(){
			\spectrum\core\_private\getRootSpec()->getResults()->add(false);
		});
		
		$this->assertFalse(\spectrum\core\constructs\run());
		
		\spectrum\core\_private\getRootSpec()->getExecutor()->setFunction(function(){
			\spectrum\core\_private\getRootSpec()->getResults()->add(true);
		});
		
		$this->assertTrue(\spectrum\core\constructs\run());
		
		\spectrum\core\_private\getRootSpec()->getExecutor()->setFunction(function(){
			\spectrum\core\_private\getRootSpec()->getResults()->add(null);
		});
		
		$this->assertNull(\spectrum\core\constructs\run());
	}
	
	public function testLocksConfigBeforeRun() {
		\spectrum\core\_private\getRootSpec()->getExecutor()->setFunction(function() use(&$isLocked) {
			$isLocked = config::isLocked();
		});
		
		$this->assertFalse(config::isLocked());
		
		\spectrum\core\constructs\run();
		
		$this->assertTrue($isLocked);
		$this->assertTrue(config::isLocked());
	}
}