<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests;

use spectrum\config;

require_once __DIR__ . '/init.php';

class RunTest extends Test
{
	public function testRunsRootSpec()
	{
		\spectrum\_internal\getRootSpec()->test->setFunction(function() use(&$isRootSpecRun){
			$isRootSpecRun = true;
		});
		
		\spectrum\run();
		
		$this->assertTrue($isRootSpecRun);
	}
	
	public function testReturnsRootSpecRunResult()
	{
		\spectrum\_internal\getRootSpec()->test->setFunction(function(){
			\spectrum\_internal\getRootSpec()->getResultBuffer()->addResult(false);
		});
		
		$this->assertFalse(\spectrum\run());
		
		\spectrum\_internal\getRootSpec()->test->setFunction(function(){
			\spectrum\_internal\getRootSpec()->getResultBuffer()->addResult(true);
		});
		
		$this->assertTrue(\spectrum\run());
		
		\spectrum\_internal\getRootSpec()->test->setFunction(function(){
			\spectrum\_internal\getRootSpec()->getResultBuffer()->addResult(null);
		});
		
		$this->assertNull(\spectrum\run());
	}
	
	public function testLocksConfigBeforeRun()
	{
		\spectrum\_internal\getRootSpec()->test->setFunction(function() use(&$isLocked){
			$isLocked = config::isLocked();
		});
		
		$this->assertFalse(config::isLocked());
		
		\spectrum\run();
		
		$this->assertTrue($isLocked);
		$this->assertTrue(config::isLocked());
	}
}