<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core\models;

use spectrum\core\models\Spec;
use spectrum\core\models\SpecInterface;

require_once __DIR__ . '/../../../init.php';

class MatchersTest extends \spectrum\tests\automatic\Test {
	public function testAdd_AddsMatcherToArrayWithNameAsIndex() {
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		$spec = new Spec();
		
		$spec->getMatchers()->add('aaa', $function1);
		$this->assertSame(array('aaa' => $function1), $spec->getMatchers()->getAll());
		
		$spec->getMatchers()->add('bbb', $function2);
		$this->assertSame(array('aaa' => $function1, 'bbb' => $function2), $spec->getMatchers()->getAll());
		
		$spec->getMatchers()->add('ccc', $function3);
		$this->assertSame(array('aaa' => $function1, 'bbb' => $function2, 'ccc' => $function3), $spec->getMatchers()->getAll());
	}
	
	public function testAdd_OverridesMatcherWithExistsName() {
		$function1 = function(){};
		$function2 = function(){};
		$spec = new Spec();
		
		$spec->getMatchers()->add('aaa', $function1);
		$this->assertSame(array('aaa' => $function1), $spec->getMatchers()->getAll());
		
		$spec->getMatchers()->add('aaa', $function2);
		$this->assertSame(array('aaa' => $function2), $spec->getMatchers()->getAll());
	}
	
	public function testAdd_CallOnRun_ThrowsExceptionAndDoesNotAddMatcher() {
		$spec = new Spec();
		$spec->getExecutor()->setFunction(function() use(&$spec, &$exception) {
			try {
				$spec->getMatchers()->add("aaa", function(){});
			} catch (\Exception $e) {
				$exception = $e;
			}
		});
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\core\Exception', $exception);
		$this->assertSame('Call of "\spectrum\core\models\Matchers::add" method is forbidden on run', $exception->getMessage());
		$this->assertSame(array(), $spec->getMatchers()->getAll());
	}
	
/**/
	
	public function testGet_ReturnsMatcherFunctionByMatcherName() {
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		$spec = new Spec();
		
		$spec->getMatchers()->add('aaa', $function1);
		$spec->getMatchers()->add('bbb', $function2);
		$spec->getMatchers()->add('ccc', $function3);
		
		$this->assertSame($function1, $spec->getMatchers()->get('aaa'));
		$this->assertSame($function2, $spec->getMatchers()->get('bbb'));
		$this->assertSame($function3, $spec->getMatchers()->get('ccc'));
	}
	
	public function testGet_ReturnsNullForNotExistsMatchers() {
		$spec = new Spec();
		$this->assertSame(null, $spec->getMatchers()->get('aaa'));
		
		$spec->getMatchers()->add('aaa', function(){});
		$this->assertSame(null, $spec->getMatchers()->get('bbb'));
	}
	
/**/
	
	public function testGetThroughRunningAncestors_ReturnsMatcherFunctionFromRunningAncestorOrFromSelf() {
		$returnValues = array();
		
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
		
		$specs[0]->getMatchers()->add('aaa', $function1);
		$specs['endingSpec1']->getMatchers()->add('aaa', $function2);
		$specs['parent1']->getMatchers()->add('aaa', $function3);
		$specs['parent2']->getMatchers()->add('aaa', $function4);
		
		$specs[0]->getExecutor()->setFunction(function() use(&$specs, &$returnValues) {
			$returnValues[] = $specs[0]->getRunningDescendantEndingSpec()->getMatchers()->getThroughRunningAncestors("aaa");
		});
		
		$specs[0]->run();
		
		$this->assertSame(array($function2, $function3, $function4, $function1), $returnValues);
	}
	
	public function testGetThroughRunningAncestors_ReturnsNullForNotExistsMatchers() {
		$spec = new Spec();
		$this->assertSame(null, $spec->getMatchers()->getThroughRunningAncestors('aaa'));
		
		$spec->getMatchers()->add('aaa', function(){});
		$this->assertSame(null, $spec->getMatchers()->getThroughRunningAncestors('bbb'));
	}
	
/**/
	
	public function testGetAll_ReturnsArrayWithAllAddedMatchers() {
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		$spec = new Spec();
		
		$spec->getMatchers()->add('aaa', $function1);
		$spec->getMatchers()->add('bbb', $function2);
		$spec->getMatchers()->add('ccc', $function3);
		
		$this->assertSame(array('aaa' => $function1, 'bbb' => $function2, 'ccc' => $function3), $spec->getMatchers()->getAll());
	}
	
	public function testGetAll_ReturnsEmptyArrayByDefault() {
		$spec = new Spec();
		$this->assertSame(array(), $spec->getMatchers()->getAll());
	}
	
/**/
	
	public function testRemove_RemovesMatcherWithSameName() {
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		$spec = new Spec();
		
		$spec->getMatchers()->add('aaa', $function1);
		$spec->getMatchers()->add('bbb', $function2);
		$spec->getMatchers()->add('ccc', $function3);
		
		$this->assertSame(array('aaa' => $function1, 'bbb' => $function2, 'ccc' => $function3), $spec->getMatchers()->getAll());
		
		$spec->getMatchers()->remove('bbb');
		$this->assertSame(array('aaa' => $function1, 'ccc' => $function3), $spec->getMatchers()->getAll());
		
		$spec->getMatchers()->remove('aaa');
		$this->assertSame(array('ccc' => $function3), $spec->getMatchers()->getAll());
		
		$spec->getMatchers()->remove('ccc');
		$this->assertSame(array(), $spec->getMatchers()->getAll());
	}
	
	public function testRemove_CallOnRun_ThrowsExceptionAndDoesNotRemoveMatcher() {
		$function1 = function(){};
		
		$spec = new Spec();
		$spec->getMatchers()->add('aaa', $function1);
		$spec->getExecutor()->setFunction(function() use(&$spec, &$exception) {
			try {
				$spec->getMatchers()->remove("aaa");
			} catch (\Exception $e) {
				$exception = $e;
			}
		});
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\core\Exception', $exception);
		$this->assertSame('Call of "\spectrum\core\models\Matchers::remove" method is forbidden on run', $exception->getMessage());
		$this->assertSame(array('aaa' => $function1), $spec->getMatchers()->getAll());
	}
	
/**/
	
	public function testRemoveAll_RemovesAllMatchers() {
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		$spec = new Spec();
		
		$spec->getMatchers()->add('aaa', $function1);
		$spec->getMatchers()->add('bbb', $function2);
		$spec->getMatchers()->add('ccc', $function3);
		
		$this->assertSame(array('aaa' => $function1, 'bbb' => $function2, 'ccc' => $function3), $spec->getMatchers()->getAll());
		
		$spec->getMatchers()->removeAll();
		$this->assertSame(array(), $spec->getMatchers()->getAll());
	}
	
	public function testRemoveAll_CallOnRun_ThrowsExceptionAndDoesNotRemoveMatchers() {
		$function1 = function(){};
		
		$spec = new Spec();
		$spec->getMatchers()->add('aaa', $function1);
		$spec->getExecutor()->setFunction(function() use(&$spec, &$exception) {
			try {
				$spec->getMatchers()->removeAll();
			} catch (\Exception $e) {
				$exception = $e;
			}
		});
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\core\Exception', $exception);
		$this->assertSame('Call of "\spectrum\core\models\Matchers::removeAll" method is forbidden on run', $exception->getMessage());
		$this->assertSame(array('aaa' => $function1), $spec->getMatchers()->getAll());
	}
}