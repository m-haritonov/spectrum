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

class ExecutorTest extends \spectrum\tests\automatic\Test {
	public function testSetFunction_SetsNewFunction() {
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->getExecutor()->setFunction($function1);
		$this->assertSame($function1, $spec->getExecutor()->getFunction());
		
		$spec->getExecutor()->setFunction($function2);
		$this->assertSame($function2, $spec->getExecutor()->getFunction());
	}
	
	public function testSetFunction_CallOnRun_ThrowsExceptionAndDoesNotChangeFunction() {
		$spec = new Spec();
		$function = function() use(&$spec, &$exception) {
			try {
				$spec->getExecutor()->setFunction(function(){});
			} catch (\Exception $e) {
				$exception = $e;
			}
		};
		$spec->getExecutor()->setFunction($function);
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\Exception', $exception);
		$this->assertSame('Call of "\spectrum\core\Executor::setFunction" method is forbidden on run', $exception->getMessage());
		$this->assertSame($function, $spec->getExecutor()->getFunction());
	}
	
/**/
	
	public function testGetFunction_ReturnsSetFunction() {
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->getExecutor()->setFunction($function1);
		$this->assertSame($function1, $spec->getExecutor()->getFunction());
		
		$spec->getExecutor()->setFunction($function2);
		$this->assertSame($function2, $spec->getExecutor()->getFunction());
	}
	
	public function testGetFunction_ReturnsNullByDefault() {
		$spec = new Spec();
		$this->assertSame(null, $spec->getExecutor()->getFunction());
	}

/**/
	
	public function testGetFunctionThroughRunningAncestors_ReturnsFunctionFromRunningAncestorOrFromSelf() {
		$returnValues = array();
		config::registerEventListener('onEndingSpecExecuteBefore', function(SpecInterface $spec) use(&$returnValues) {
			$returnValues[] = $spec->getExecutor()->getFunctionThroughRunningAncestors();
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec(endingSpec1)
			->Spec(parent1)
			->Spec(parent2)
			->Spec(parent3)
			->->Spec(endingSpec2)
		', array('parent1' => 'endingSpec2', 'parent2' => 'endingSpec2'));
		
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		$function4 = function(){};
		
		$specs[0]->getExecutor()->setFunction($function1);
		$specs['endingSpec1']->getExecutor()->setFunction($function2);
		$specs['parent1']->getExecutor()->setFunction($function3);
		$specs['parent2']->getExecutor()->setFunction($function4);
		
		$specs[0]->run();
		
		$this->assertSame(array($function2, $function3, $function4, $function1), $returnValues);
	}
	
	public function testGetFunctionThroughRunningAncestors_ReturnsNullByDefault() {
		$spec = new Spec();
		$this->assertSame(null, $spec->getExecutor()->getFunctionThroughRunningAncestors());
	}
}