<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core\plugins;

use spectrum\core\Spec;

require_once __DIR__ . '/../../../init.php';

class MatchersTest extends \spectrum\tests\automatic\Test {
	public function testAdd_AddsMatcherToArrayWithNameAsIndex() {
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		$spec = new Spec();
		
		$spec->matchers->add('aaa', $function1);
		$this->assertSame(array('aaa' => $function1), $spec->matchers->getAll());
		
		$spec->matchers->add('bbb', $function2);
		$this->assertSame(array('aaa' => $function1, 'bbb' => $function2), $spec->matchers->getAll());
		
		$spec->matchers->add('ccc', $function3);
		$this->assertSame(array('aaa' => $function1, 'bbb' => $function2, 'ccc' => $function3), $spec->matchers->getAll());
	}
	
	public function testAdd_OverridesMatcherWithExistsName() {
		$function1 = function(){};
		$function2 = function(){};
		$spec = new Spec();
		
		$spec->matchers->add('aaa', $function1);
		$this->assertSame(array('aaa' => $function1), $spec->matchers->getAll());
		
		$spec->matchers->add('aaa', $function2);
		$this->assertSame(array('aaa' => $function2), $spec->matchers->getAll());
	}
	
	public function testAdd_CallOnRun_ThrowsExceptionAndDoesNotAddMatcher() {
		\spectrum\tests\automatic\Test::$temp["exception"] = null;
		
		$this->registerPluginWithCodeInEvent('
			try {
				$this->getOwnerSpec()->matchers->add("aaa", function(){});
			} catch (\Exception $e) {
				\spectrum\tests\automatic\Test::$temp["exception"] = $e;
			}
		');
		

		$spec = new Spec();
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\Exception', \spectrum\tests\automatic\Test::$temp["exception"]);
		$this->assertSame('Call of "\spectrum\core\plugins\Matchers::add" method is forbidden on run', \spectrum\tests\automatic\Test::$temp["exception"]->getMessage());
		$this->assertSame(array(), $spec->matchers->getAll());
	}
	
/**/
	
	public function testGet_ReturnsMatcherFunctionByMatcherName() {
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		$spec = new Spec();
		
		$spec->matchers->add('aaa', $function1);
		$spec->matchers->add('bbb', $function2);
		$spec->matchers->add('ccc', $function3);
		
		$this->assertSame($function1, $spec->matchers->get('aaa'));
		$this->assertSame($function2, $spec->matchers->get('bbb'));
		$this->assertSame($function3, $spec->matchers->get('ccc'));
	}
	
	public function testGet_ReturnsNullForNotExistsMatchers() {
		$spec = new Spec();
		$this->assertSame(null, $spec->matchers->get('aaa'));
		
		$spec->matchers->add('aaa', function(){});
		$this->assertSame(null, $spec->matchers->get('bbb'));
	}
	
/**/
	
	public function testGetThroughRunningAncestors_ReturnsMatcherFunctionFromRunningAncestorOrFromSelf() {
		\spectrum\tests\automatic\Test::$temp["returnValues"] = array();
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\automatic\Test::$temp["returnValues"][] = $this->getOwnerSpec()->matchers->getThroughRunningAncestors("aaa");
		', 'onEndingSpecExecute');
		
		$specs = $this->createSpecsByListPattern('
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
		
		$specs[0]->matchers->add('aaa', $function1);
		$specs['endingSpec1']->matchers->add('aaa', $function2);
		$specs['parent1']->matchers->add('aaa', $function3);
		$specs['parent2']->matchers->add('aaa', $function4);
		
		$specs[0]->run();
		
		$this->assertSame(array($function2, $function3, $function4, $function1), \spectrum\tests\automatic\Test::$temp["returnValues"]);
	}
	
	public function testGetThroughRunningAncestors_ReturnsNullForNotExistsMatchers() {
		$spec = new Spec();
		$this->assertSame(null, $spec->matchers->getThroughRunningAncestors('aaa'));
		
		$spec->matchers->add('aaa', function(){});
		$this->assertSame(null, $spec->matchers->getThroughRunningAncestors('bbb'));
	}
	
/**/
	
	public function testGetAll_ReturnsArrayWithAllAddedMatchers() {
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		$spec = new Spec();
		
		$spec->matchers->add('aaa', $function1);
		$spec->matchers->add('bbb', $function2);
		$spec->matchers->add('ccc', $function3);
		
		$this->assertSame(array('aaa' => $function1, 'bbb' => $function2, 'ccc' => $function3), $spec->matchers->getAll());
	}
	
	public function testGetAll_ReturnsEmptyArrayByDefault() {
		$spec = new Spec();
		$this->assertSame(array(), $spec->matchers->getAll());
	}
	
/**/
	
	public function testRemove_RemovesMatcherWithSameName() {
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		$spec = new Spec();
		
		$spec->matchers->add('aaa', $function1);
		$spec->matchers->add('bbb', $function2);
		$spec->matchers->add('ccc', $function3);
		
		$this->assertSame(array('aaa' => $function1, 'bbb' => $function2, 'ccc' => $function3), $spec->matchers->getAll());
		
		$spec->matchers->remove('bbb');
		$this->assertSame(array('aaa' => $function1, 'ccc' => $function3), $spec->matchers->getAll());
		
		$spec->matchers->remove('aaa');
		$this->assertSame(array('ccc' => $function3), $spec->matchers->getAll());
		
		$spec->matchers->remove('ccc');
		$this->assertSame(array(), $spec->matchers->getAll());
	}
	
	public function testRemove_CallOnRun_ThrowsExceptionAndDoesNotRemoveMatcher() {
		\spectrum\tests\automatic\Test::$temp["exception"] = null;
		
		$this->registerPluginWithCodeInEvent('
			try {
				$this->getOwnerSpec()->matchers->remove("aaa");
			} catch (\Exception $e) {
				\spectrum\tests\automatic\Test::$temp["exception"] = $e;
			}
		');
		
		$function1 = function(){};
		
		$spec = new Spec();
		$spec->matchers->add('aaa', $function1);
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\Exception', \spectrum\tests\automatic\Test::$temp["exception"]);
		$this->assertSame('Call of "\spectrum\core\plugins\Matchers::remove" method is forbidden on run', \spectrum\tests\automatic\Test::$temp["exception"]->getMessage());
		$this->assertSame(array('aaa' => $function1), $spec->matchers->getAll());
	}
	
/**/
	
	public function testRemoveAll_RemovesAllMatchers() {
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		$spec = new Spec();
		
		$spec->matchers->add('aaa', $function1);
		$spec->matchers->add('bbb', $function2);
		$spec->matchers->add('ccc', $function3);
		
		$this->assertSame(array('aaa' => $function1, 'bbb' => $function2, 'ccc' => $function3), $spec->matchers->getAll());
		
		$spec->matchers->removeAll();
		$this->assertSame(array(), $spec->matchers->getAll());
	}
	
	public function testRemoveAll_CallOnRun_ThrowsExceptionAndDoesNotRemoveMatchers() {
		\spectrum\tests\automatic\Test::$temp["exception"] = null;
		
		$this->registerPluginWithCodeInEvent('
			try {
				$this->getOwnerSpec()->matchers->removeAll();
			} catch (\Exception $e) {
				\spectrum\tests\automatic\Test::$temp["exception"] = $e;
			}
		');
		
		$function1 = function(){};
		
		$spec = new Spec();
		$spec->matchers->add('aaa', $function1);
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\Exception', \spectrum\tests\automatic\Test::$temp["exception"]);
		$this->assertSame('Call of "\spectrum\core\plugins\Matchers::removeAll" method is forbidden on run', \spectrum\tests\automatic\Test::$temp["exception"]->getMessage());
		$this->assertSame(array('aaa' => $function1), $spec->matchers->getAll());
	}
}