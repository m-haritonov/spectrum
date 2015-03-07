<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core;

use spectrum\config;
use spectrum\core\Spec;
use spectrum\core\SpecInterface;

require_once __DIR__ . '/../../init.php';

class ErrorHandlingTest extends \spectrum\tests\automatic\Test {
	public function setUp() {
		parent::setUp();
		config::setAllowErrorHandlingModify(true);
	}
	
	public function providerSetCatchPhpErrors() {
		return array(
			array(true, -1),
			array(false, 0),
			array('', 0),
			array(null, null),
			array(1, 1),
			array(2, 2),
		);
	}
	
	/**
	 * @dataProvider providerSetCatchPhpErrors
	 */
	public function testSetCatchPhpErrors_SetsValue($errorLevel, $resultErrorLevel) {
		$spec = new Spec();
		$spec->getErrorHandling()->setCatchPhpErrors($errorLevel);
		$this->assertSame($resultErrorLevel, $spec->getErrorHandling()->getCatchPhpErrors());
	}
	
	public function testSetCatchPhpErrors_CallOnRun_ThrowsExceptionAndDoesNotChangeValue() {
		\spectrum\config::registerEventListener('onEndingSpecExecuteBefore', function(SpecInterface $spec) use(&$exception) {
			try {
				$spec->getErrorHandling()->setCatchPhpErrors(0);
			} catch (\Exception $e) {
				$exception = $e;
			}
		});

		$spec = new Spec();
		$spec->getErrorHandling()->setCatchPhpErrors(1);
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\Exception', $exception);
		$this->assertSame('Call of "\spectrum\core\ErrorHandling::setCatchPhpErrors" method is forbidden on run', $exception->getMessage());
		$this->assertSame(1, $spec->getErrorHandling()->getCatchPhpErrors());
	}
	
	public function testSetCatchPhpErrors_ErrorHandlingModifyIsDeniedInConfig_ThrowsExceptionAndDoesNotChangeValue() {
		$spec = new Spec();
		$spec->getErrorHandling()->setCatchPhpErrors(1);
		
		config::setAllowErrorHandlingModify(false);
		
		$this->assertThrowsException('\spectrum\Exception', 'Error handling modify deny in config', function() use($spec){
			$spec->getErrorHandling()->setCatchPhpErrors(0);
		});
		
		$this->assertSame(1, $spec->getErrorHandling()->getCatchPhpErrors());
	}
	
/**/
	
	public function testGetCatchPhpErrors_ReturnsSetValue() {
		$spec = new Spec();
		$spec->getErrorHandling()->setCatchPhpErrors(1);
		$this->assertSame(1, $spec->getErrorHandling()->getCatchPhpErrors());
		
		$spec->getErrorHandling()->setCatchPhpErrors(2);
		$this->assertSame(2, $spec->getErrorHandling()->getCatchPhpErrors());
	}
	
	public function testGetCatchPhpErrors_ReturnsNullByDefault() {
		$spec = new Spec();
		$this->assertSame(null, $spec->getErrorHandling()->getCatchPhpErrors());
	}
	
/**/
	
	public function testGetCatchPhpErrorsThroughRunningAncestors_ReturnsValueFromRunningAncestorOrFromSelf() {
		$returnValues = array();
		\spectrum\config::registerEventListener('onEndingSpecExecuteBefore', function(SpecInterface $spec) use(&$returnValues) {
			$returnValues[] = $spec->getErrorHandling()->getCatchPhpErrorsThroughRunningAncestors();
		});
		
		$specs = $this->createSpecsByListPattern('
			Spec
			->Spec(endingSpec1)
			->Spec(parent1)
			->Spec(parent2)
			->Spec(parent3)
			->->Spec(endingSpec2)
		', array('parent1' => 'endingSpec2', 'parent2' => 'endingSpec2'));
		
		$specs[0]->getErrorHandling()->setCatchPhpErrors(1);
		$specs['endingSpec1']->getErrorHandling()->setCatchPhpErrors(2);
		$specs['parent1']->getErrorHandling()->setCatchPhpErrors(3);
		$specs['parent2']->getErrorHandling()->setCatchPhpErrors(4);
		
		$specs[0]->run();
		
		$this->assertSame(array(2, 3, 4, 1), $returnValues);
	}
	
	public function testGetCatchPhpErrorsThroughRunningAncestors_ReturnsNegativeOneWhenValueIsNotSet() {
		$spec = new Spec();
		$spec->getErrorHandling()->setCatchPhpErrors(null);
		$this->assertSame(-1, $spec->getErrorHandling()->getCatchPhpErrorsThroughRunningAncestors());
	}
	
/**/
	
	public function testSetBreakOnFirstPhpError_SetsValue() {
		$spec = new Spec();
		$spec->getErrorHandling()->setBreakOnFirstPhpError(true);
		$this->assertSame(true, $spec->getErrorHandling()->getBreakOnFirstPhpError());
		
		$spec->getErrorHandling()->setBreakOnFirstPhpError(false);
		$this->assertSame(false, $spec->getErrorHandling()->getBreakOnFirstPhpError());
	}
	
	public function testSetBreakOnFirstPhpError_CallOnRun_ThrowsExceptionAndDoesNotChangeValue() {
		\spectrum\config::registerEventListener('onEndingSpecExecuteBefore', function(SpecInterface $spec) use(&$exception) {
			try {
				$spec->getErrorHandling()->setBreakOnFirstPhpError(false);
			} catch (\Exception $e) {
				$exception = $e;
			}
		});

		$spec = new Spec();
		$spec->getErrorHandling()->setBreakOnFirstPhpError(true);
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\Exception', $exception);
		$this->assertSame('Call of "\spectrum\core\ErrorHandling::setBreakOnFirstPhpError" method is forbidden on run', $exception->getMessage());
		$this->assertSame(true, $spec->getErrorHandling()->getBreakOnFirstPhpError());
	}
	
	public function testSetBreakOnFirstPhpError_ErrorHandlingModifyIsDeniedInConfig_ThrowsExceptionAndDoesNotChangeValue() {
		$spec = new Spec();
		$spec->getErrorHandling()->setBreakOnFirstPhpError(true);
		
		config::setAllowErrorHandlingModify(false);
		
		$this->assertThrowsException('\spectrum\Exception', 'Error handling modify deny in config', function() use($spec){
			$spec->getErrorHandling()->setBreakOnFirstPhpError(false);
		});
		
		$this->assertSame(true, $spec->getErrorHandling()->getBreakOnFirstPhpError());
	}
	
/**/
	
	public function testGetBreakOnFirstPhpError_ReturnsSetValue() {
		$spec = new Spec();
		$spec->getErrorHandling()->setBreakOnFirstPhpError(true);
		$this->assertSame(true, $spec->getErrorHandling()->getBreakOnFirstPhpError());
		
		$spec->getErrorHandling()->setBreakOnFirstPhpError(false);
		$this->assertSame(false, $spec->getErrorHandling()->getBreakOnFirstPhpError());
	}
	
	public function testGetBreakOnFirstPhpError_ReturnsNullByDefault() {
		$spec = new Spec();
		$this->assertSame(null, $spec->getErrorHandling()->getBreakOnFirstPhpError());
	}
	
/**/
	
	public function testGetBreakOnFirstPhpErrorThroughRunningAncestors_ReturnsValueFromRunningAncestorOrFromSelf() {
		$returnValues = array();
		\spectrum\config::registerEventListener('onEndingSpecExecuteBefore', function(SpecInterface $spec) use(&$returnValues) {
			$returnValues[] = $spec->getErrorHandling()->getBreakOnFirstPhpErrorThroughRunningAncestors();
		});
		
		$specs = $this->createSpecsByListPattern('
			Spec
			->Spec(endingSpec1)
			->Spec(parent1)
			->Spec(parent2)
			->->Spec(endingSpec2)
		', array('parent1' => 'endingSpec2'));
		
		$specs[0]->getErrorHandling()->setBreakOnFirstPhpError(true);
		$specs['endingSpec1']->getErrorHandling()->setBreakOnFirstPhpError(false);
		$specs['parent1']->getErrorHandling()->setBreakOnFirstPhpError(false);
		
		$specs[0]->run();
		
		$this->assertSame(array(false, false, true), $returnValues);
	}
	
	public function testGetBreakOnFirstPhpErrorThroughRunningAncestors_ReturnsFalseWhenValueIsNotSet() {
		$spec = new Spec();
		$spec->getErrorHandling()->setBreakOnFirstPhpError(null);
		$this->assertSame(false, $spec->getErrorHandling()->getBreakOnFirstPhpErrorThroughRunningAncestors());
	}
	
/**/
	
	public function testSetBreakOnFirstMatcherFail_SetsValue() {
		$spec = new Spec();
		$spec->getErrorHandling()->setBreakOnFirstMatcherFail(true);
		$this->assertSame(true, $spec->getErrorHandling()->getBreakOnFirstMatcherFail());
		
		$spec->getErrorHandling()->setBreakOnFirstMatcherFail(false);
		$this->assertSame(false, $spec->getErrorHandling()->getBreakOnFirstMatcherFail());
	}
	
	public function testSetBreakOnFirstMatcherFail_CallOnRun_ThrowsExceptionAndDoesNotChangeValue() {
		\spectrum\config::registerEventListener('onEndingSpecExecuteBefore', function(SpecInterface $spec) use(&$exception) {
			try {
				$spec->getErrorHandling()->setBreakOnFirstMatcherFail(false);
			} catch (\Exception $e) {
				$exception = $e;
			}
		});

		$spec = new Spec();
		$spec->getErrorHandling()->setBreakOnFirstMatcherFail(true);
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\Exception', $exception);
		$this->assertSame('Call of "\spectrum\core\ErrorHandling::setBreakOnFirstMatcherFail" method is forbidden on run', $exception->getMessage());
		$this->assertSame(true, $spec->getErrorHandling()->getBreakOnFirstMatcherFail());
	}
	
	public function testSetBreakOnFirstMatcherFail_ErrorHandlingModifyIsDeniedInConfig_ThrowsExceptionAndDoesNotChangeValue() {
		$spec = new Spec();
		$spec->getErrorHandling()->setBreakOnFirstMatcherFail(true);
		
		config::setAllowErrorHandlingModify(false);
		
		$this->assertThrowsException('\spectrum\Exception', 'Error handling modify deny in config', function() use($spec){
			$spec->getErrorHandling()->setBreakOnFirstMatcherFail(false);
		});
		
		$this->assertSame(true, $spec->getErrorHandling()->getBreakOnFirstMatcherFail());
	}
	
/**/
	
	public function testGetBreakOnFirstMatcherFail_ReturnsSetValue() {
		$spec = new Spec();
		$spec->getErrorHandling()->setBreakOnFirstMatcherFail(true);
		$this->assertSame(true, $spec->getErrorHandling()->getBreakOnFirstMatcherFail());
		
		$spec->getErrorHandling()->setBreakOnFirstMatcherFail(false);
		$this->assertSame(false, $spec->getErrorHandling()->getBreakOnFirstMatcherFail());
	}
	
	public function testGetBreakOnFirstMatcherFail_ReturnsNullByDefault() {
		$spec = new Spec();
		$this->assertSame(null, $spec->getErrorHandling()->getBreakOnFirstMatcherFail());
	}
	
/**/
	
	public function testGetBreakOnFirstMatcherFailThroughRunningAncestors_ReturnsValueFromRunningAncestorOrFromSelf() {
		$returnValues = array();
		
		\spectrum\config::registerEventListener('onEndingSpecExecuteBefore', function(SpecInterface $spec) use(&$returnValues) {
			$returnValues[] = $spec->getErrorHandling()->getBreakOnFirstMatcherFailThroughRunningAncestors();
		});
		
		$specs = $this->createSpecsByListPattern('
			Spec
			->Spec(endingSpec1)
			->Spec(parent1)
			->Spec(parent2)
			->->Spec(endingSpec2)
		', array('parent1' => 'endingSpec2'));
		
		$specs[0]->getErrorHandling()->setBreakOnFirstMatcherFail(true);
		$specs['endingSpec1']->getErrorHandling()->setBreakOnFirstMatcherFail(false);
		$specs['parent1']->getErrorHandling()->setBreakOnFirstMatcherFail(false);
		
		$specs[0]->run();
		
		$this->assertSame(array(false, false, true), $returnValues);
	}
	
	public function testGetBreakOnFirstMatcherFailThroughRunningAncestors_ReturnsFalseWhenValueIsNotSet() {
		$spec = new Spec();
		$spec->getErrorHandling()->setBreakOnFirstMatcherFail(null);
		$this->assertSame(false, $spec->getErrorHandling()->getBreakOnFirstMatcherFailThroughRunningAncestors());
	}
}