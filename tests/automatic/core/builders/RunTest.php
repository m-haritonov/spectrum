<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core\builders;

use spectrum\core\config;

require_once __DIR__ . '/../../../init.php';

class RunTest extends \spectrum\tests\automatic\Test {
	public function testRunsRootSpec() {
		\spectrum\_private\getRootSpec()->getExecutor()->setFunction(function() use(&$isRootSpecRun) {
			$isRootSpecRun = true;
		});
		
		\spectrum\core\builders\run();
		
		$this->assertTrue($isRootSpecRun);
	}
	
	public function testReturnsRootSpecRunResult() {
		\spectrum\_private\getRootSpec()->getExecutor()->setFunction(function(){
			\spectrum\_private\getRootSpec()->getResults()->add(false);
		});
		
		$this->assertFalse(\spectrum\core\builders\run());
		
		\spectrum\_private\getRootSpec()->getExecutor()->setFunction(function(){
			\spectrum\_private\getRootSpec()->getResults()->add(true);
		});
		
		$this->assertTrue(\spectrum\core\builders\run());
		
		\spectrum\_private\getRootSpec()->getExecutor()->setFunction(function(){
			\spectrum\_private\getRootSpec()->getResults()->add(null);
		});
		
		$this->assertNull(\spectrum\core\builders\run());
	}
	
	public function testLocksConfigBeforeRun() {
		\spectrum\_private\getRootSpec()->getExecutor()->setFunction(function() use(&$isLocked) {
			$isLocked = config::isLocked();
		});
		
		$this->assertFalse(config::isLocked());
		
		\spectrum\core\builders\run();
		
		$this->assertTrue($isLocked);
		$this->assertTrue(config::isLocked());
	}
}