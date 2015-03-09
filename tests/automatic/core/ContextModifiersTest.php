<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core;

use spectrum\core\Spec;
use spectrum\core\SpecInterface;

require_once __DIR__ . '/../../init.php';

class ContextModifiersTest extends \spectrum\tests\automatic\Test {
	public function testAdd_AddsFunctionAndType() {
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		$function4 = function(){};
		
		$spec = new Spec();
		$spec->getContextModifiers()->add($function1, 'before');
		$spec->getContextModifiers()->add($function2, 'after');
		$spec->getContextModifiers()->add($function3, 'before');
		$spec->getContextModifiers()->add($function4, 'after');
		
		$this->assertSame(array(
			array('function' => $function1, 'type' => 'before'),
			array('function' => $function2, 'type' => 'after'),
			array('function' => $function3, 'type' => 'before'),
			array('function' => $function4, 'type' => 'after'),
		), $spec->getContextModifiers()->getAll());
	}
	
	public function testAdd_ConvertsTypeToLowercase() {
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->getContextModifiers()->add($function1, 'beFOre');
		$spec->getContextModifiers()->add($function2, 'AFTer');
		
		$this->assertSame(array(
			array('function' => $function1, 'type' => 'before'),
			array('function' => $function2, 'type' => 'after'),
		), $spec->getContextModifiers()->getAll());
	}
	
	public function testAdd_CallOnRun_ThrowsExceptionAndDoesNotAddValue() {
		\spectrum\core\config::registerEventListener('onEndingSpecExecuteBefore', function(SpecInterface $spec) use(&$exception) {
			try {
				$spec->getContextModifiers()->add(function(){}, "before");
			} catch (\Exception $e) {
				$exception = $e;
			}
		});

		$spec = new Spec();
		$spec->run();
		$this->assertInstanceOf('\spectrum\core\Exception', $exception);
		$this->assertSame('Call of "\spectrum\core\ContextModifiers::add" method is forbidden on run', $exception->getMessage());
		$this->assertSame(array(), $spec->getContextModifiers()->getAll());
	}
	
	public function testAdd_ThrowsExceptionForInvalidTypes() {
		$spec = new Spec();
		$this->assertThrowsException('\spectrum\core\Exception', 'Unknown type "aaa" is passed to "\spectrum\core\ContextModifiers::add" method', function() use($spec) {
			$spec->getContextModifiers()->add(function(){}, 'aaa');
		});
	}

/**/
	
	public function testGetAll_ThrowsExceptionForInvalidTypes() {
		$spec = new Spec();
		$this->assertThrowsException('\spectrum\core\Exception', 'Unknown type "aaa" is passed to "\spectrum\core\ContextModifiers::getAll" method', function() use($spec) {
			$spec->getContextModifiers()->getAll('aaa');
		});
	}
	
/**/
	
	public function testGetAll_TypeIsNull_ReturnsAllRowsWithAnyType() {
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		$function4 = function(){};
		
		$spec = new Spec();
		$spec->getContextModifiers()->add($function1, 'before');
		$spec->getContextModifiers()->add($function2, 'after');
		$spec->getContextModifiers()->add($function3, 'before');
		$spec->getContextModifiers()->add($function4, 'after');
		
		$this->assertSame(array(
			array('function' => $function1, 'type' => 'before'),
			array('function' => $function2, 'type' => 'after'),
			array('function' => $function3, 'type' => 'before'),
			array('function' => $function4, 'type' => 'after'),
		), $spec->getContextModifiers()->getAll());
	}
	
	public function testGetAll_TypeIsNull_ReturnsEmptyArrayWhenNoRows() {
		$spec = new Spec();
		$this->assertSame(array(), $spec->getContextModifiers()->getAll());
		
		$spec->getContextModifiers()->add(function(){}, 'before');
		$spec->getContextModifiers()->remove(0);
		$this->assertSame(array(), $spec->getContextModifiers()->getAll());
		
		$spec->getContextModifiers()->add(function(){}, 'before');
		$spec->getContextModifiers()->removeAll();
		$this->assertSame(array(), $spec->getContextModifiers()->getAll());
	}
	
/**/
	
	public function testGetAll_TypeIsBefore_ReturnsAllRowsWithSameType() {
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->getContextModifiers()->add($function1, 'before');
		$spec->getContextModifiers()->add(function(){}, 'after');
		$spec->getContextModifiers()->add($function2, 'before');
		$spec->getContextModifiers()->add(function(){}, 'after');
		
		$this->assertSame(array(
			0 => array('function' => $function1, 'type' => 'before'),
			2 => array('function' => $function2, 'type' => 'before'),
		), $spec->getContextModifiers()->getAll('before'));
	}
	
	public function testGetAll_TypeIsBefore_PreservesIndexes() {
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		
		$spec = new Spec();
		$spec->getContextModifiers()->add($function1, 'before');
		$spec->getContextModifiers()->add(function(){}, 'after');
		$spec->getContextModifiers()->add($function2, 'before');
		$spec->getContextModifiers()->add(function(){}, 'after');
		$spec->getContextModifiers()->add(function(){}, 'after');
		$spec->getContextModifiers()->add($function3, 'before');
		
		$this->assertSame(array(
			0 => array('function' => $function1, 'type' => 'before'),
			2 => array('function' => $function2, 'type' => 'before'),
			5 => array('function' => $function3, 'type' => 'before'),
		), $spec->getContextModifiers()->getAll('before'));
	}
	
	public function testGetAll_TypeIsBefore_IgnoresTypeCase() {
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->getContextModifiers()->add($function1, 'before');
		$spec->getContextModifiers()->add($function2, 'before');
		
		$this->assertSame(array(
			0 => array('function' => $function1, 'type' => 'before'),
			1 => array('function' => $function2, 'type' => 'before'),
		), $spec->getContextModifiers()->getAll('BEFOre'));
	}
	
	public function testGetAll_TypeIsBefore_ReturnsEmptyArrayWhenNoRows() {
		$spec = new Spec();
		$this->assertSame(array(), $spec->getContextModifiers()->getAll('before'));
		
		$spec->getContextModifiers()->add(function(){}, 'before');
		$spec->getContextModifiers()->remove(0);
		$this->assertSame(array(), $spec->getContextModifiers()->getAll('before'));
		
		$spec->getContextModifiers()->add(function(){}, 'before');
		$spec->getContextModifiers()->removeAll();
		$this->assertSame(array(), $spec->getContextModifiers()->getAll('before'));
	}
	
/**/
	
	public function testGetAll_TypeIsAfter_ReturnsAllRowsWithSameType() {
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->getContextModifiers()->add($function1, 'after');
		$spec->getContextModifiers()->add(function(){}, 'before');
		$spec->getContextModifiers()->add($function2, 'after');
		$spec->getContextModifiers()->add(function(){}, 'before');
		
		$this->assertSame(array(
			0 => array('function' => $function1, 'type' => 'after'),
			2 => array('function' => $function2, 'type' => 'after'),
		), $spec->getContextModifiers()->getAll('after'));
	}
	
	public function testGetAll_TypeIsAfter_PreservesIndexes() {
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		
		$spec = new Spec();
		$spec->getContextModifiers()->add($function1, 'after');
		$spec->getContextModifiers()->add(function(){}, 'before');
		$spec->getContextModifiers()->add($function2, 'after');
		$spec->getContextModifiers()->add(function(){}, 'before');
		$spec->getContextModifiers()->add(function(){}, 'before');
		$spec->getContextModifiers()->add($function3, 'after');
		
		$this->assertSame(array(
			0 => array('function' => $function1, 'type' => 'after'),
			2 => array('function' => $function2, 'type' => 'after'),
			5 => array('function' => $function3, 'type' => 'after'),
		), $spec->getContextModifiers()->getAll('after'));
	}
	
	public function testGetAll_TypeIsAfter_IgnoresTypeCase() {
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->getContextModifiers()->add($function1, 'after');
		$spec->getContextModifiers()->add($function2, 'after');
		
		$this->assertSame(array(
			0 => array('function' => $function1, 'type' => 'after'),
			1 => array('function' => $function2, 'type' => 'after'),
		), $spec->getContextModifiers()->getAll('AFTer'));
	}
	
	public function testGetAll_TypeIsAfter_ReturnsEmptyArrayWhenNoRows() {
		$spec = new Spec();
		$this->assertSame(array(), $spec->getContextModifiers()->getAll('after'));
		
		$spec->getContextModifiers()->add(function(){}, 'after');
		$spec->getContextModifiers()->remove(0);
		$this->assertSame(array(), $spec->getContextModifiers()->getAll('after'));
		
		$spec->getContextModifiers()->add(function(){}, 'after');
		$spec->getContextModifiers()->removeAll();
		$this->assertSame(array(), $spec->getContextModifiers()->getAll('after'));
	}
	
/**/
	
	public function testGetAllThroughRunningAncestors_ThrowsExceptionForInvalidTypes() {
		$spec = new Spec();
		$this->assertThrowsException('\spectrum\core\Exception', 'Unknown type "aaa" is passed to "\spectrum\core\ContextModifiers::getAllThroughRunningAncestors" method', function() use($spec) {
			$spec->getContextModifiers()->getAllThroughRunningAncestors('aaa');
		});
	}
	
/**/
	
	public function testGetAllThroughRunningAncestors_TypeIsBefore_ReturnsAllRowsWithSameType() {
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->getContextModifiers()->add($function1, 'before');
		$spec->getContextModifiers()->add(function(){}, 'after');
		$spec->getContextModifiers()->add($function2, 'before');
		$spec->getContextModifiers()->add(function(){}, 'after');
		
		$this->assertSame(array(
			array('function' => $function1, 'type' => 'before'),
			array('function' => $function2, 'type' => 'before'),
		), $spec->getContextModifiers()->getAllThroughRunningAncestors('before'));
	}
	
	public function testGetAllThroughRunningAncestors_TypeIsBefore_DoesNotPreserveIndexes() {
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		
		$spec = new Spec();
		$spec->getContextModifiers()->add($function1, 'before');
		$spec->getContextModifiers()->add(function(){}, 'after');
		$spec->getContextModifiers()->add($function2, 'before');
		$spec->getContextModifiers()->add(function(){}, 'after');
		$spec->getContextModifiers()->add(function(){}, 'after');
		$spec->getContextModifiers()->add($function3, 'before');
		
		$this->assertSame(array(
			array('function' => $function1, 'type' => 'before'),
			array('function' => $function2, 'type' => 'before'),
			array('function' => $function3, 'type' => 'before'),
		), $spec->getContextModifiers()->getAllThroughRunningAncestors('before'));
	}
	
	public function testGetAllThroughRunningAncestors_TypeIsBefore_ReturnsRowsInOrderFromRootRunningAncestorToSelf() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
			->Spec
			->->Spec(checkpoint)
		', array(1 => 'checkpoint'));
		
		$returnValues = array();
		\spectrum\core\config::registerEventListener('onEndingSpecExecuteBefore', function(SpecInterface $spec) use($specs, &$returnValues) {
			if ($spec === $specs["checkpoint"]) {
				$returnValues[] = $spec->getContextModifiers()->getAllThroughRunningAncestors("before");
			}
		});
		
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		$function4 = function(){};
		$function5 = function(){};
		$function6 = function(){};
		$function7 = function(){};
		$function8 = function(){};
		
		$specs[0]->getContextModifiers()->add($function1, 'before');
		$specs[0]->getContextModifiers()->add($function2, 'before');
		$specs[1]->getContextModifiers()->add($function3, 'before');
		$specs[1]->getContextModifiers()->add($function4, 'before');
		$specs[2]->getContextModifiers()->add($function5, 'before');
		$specs[2]->getContextModifiers()->add($function6, 'before');
		$specs['checkpoint']->getContextModifiers()->add($function7, 'before');
		$specs['checkpoint']->getContextModifiers()->add($function8, 'before');
		
		$specs[0]->run();
		$this->assertSame(array(
			array(
				array('function' => $function1, 'type' => 'before'),
				array('function' => $function2, 'type' => 'before'),
				array('function' => $function3, 'type' => 'before'),
				array('function' => $function4, 'type' => 'before'),
				array('function' => $function7, 'type' => 'before'),
				array('function' => $function8, 'type' => 'before'),
			),
			array(
				array('function' => $function1, 'type' => 'before'),
				array('function' => $function2, 'type' => 'before'),
				array('function' => $function5, 'type' => 'before'),
				array('function' => $function6, 'type' => 'before'),
				array('function' => $function7, 'type' => 'before'),
				array('function' => $function8, 'type' => 'before'),
			),
		), $returnValues);
	}
	
	public function testGetAllThroughRunningAncestors_TypeIsBefore_IgnoresTypeCase() {
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->getContextModifiers()->add($function1, 'before');
		$spec->getContextModifiers()->add($function2, 'before');
		
		$this->assertSame(array(
			array('function' => $function1, 'type' => 'before'),
			array('function' => $function2, 'type' => 'before'),
		), $spec->getContextModifiers()->getAllThroughRunningAncestors('BEFOre'));
	}
	
	public function testGetAllThroughRunningAncestors_TypeIsBefore_ReturnsEmptyArrayWhenNoRows() {
		$spec = new Spec();
		$this->assertSame(array(), $spec->getContextModifiers()->getAllThroughRunningAncestors('before'));
		
		$spec->getContextModifiers()->add(function(){}, 'before');
		$spec->getContextModifiers()->remove(0);
		$this->assertSame(array(), $spec->getContextModifiers()->getAllThroughRunningAncestors('before'));
		
		$spec->getContextModifiers()->add(function(){}, 'before');
		$spec->getContextModifiers()->removeAll();
		$this->assertSame(array(), $spec->getContextModifiers()->getAllThroughRunningAncestors('before'));
	}
	
/**/
	
	public function testGetAllThroughRunningAncestors_TypeIsAfter_ReturnsAllRowsWithSameType() {
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->getContextModifiers()->add($function1, 'after');
		$spec->getContextModifiers()->add(function(){}, 'before');
		$spec->getContextModifiers()->add($function2, 'after');
		$spec->getContextModifiers()->add(function(){}, 'before');
		
		$this->assertSame(array(
			array('function' => $function2, 'type' => 'after'),
			array('function' => $function1, 'type' => 'after'),
		), $spec->getContextModifiers()->getAllThroughRunningAncestors('after'));
	}
	
	public function testGetAllThroughRunningAncestors_TypeIsAfter_DoesNotPreserveIndexes() {
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		
		$spec = new Spec();
		$spec->getContextModifiers()->add($function1, 'after');
		$spec->getContextModifiers()->add(function(){}, 'before');
		$spec->getContextModifiers()->add($function2, 'after');
		$spec->getContextModifiers()->add(function(){}, 'before');
		$spec->getContextModifiers()->add(function(){}, 'before');
		$spec->getContextModifiers()->add($function3, 'after');
		
		$this->assertSame(array(
			array('function' => $function3, 'type' => 'after'),
			array('function' => $function2, 'type' => 'after'),
			array('function' => $function1, 'type' => 'after'),
		), $spec->getContextModifiers()->getAllThroughRunningAncestors('after'));
	}
	
	public function testGetAllThroughRunningAncestors_TypeIsAfter_ReturnsRowsInOrderFromSelfToRootRunningAncestor() {
		$specs = \spectrum\tests\_testware\tools::createSpecsByListPattern('
			Spec
			->Spec
			->Spec
			->->Spec(checkpoint)
		', array(1 => 'checkpoint'));
		
		$returnValues = array();
		\spectrum\core\config::registerEventListener('onEndingSpecExecuteBefore', function(SpecInterface $spec) use($specs, &$returnValues) {
			if ($spec === $specs["checkpoint"]) {
				$returnValues[] = $spec->getContextModifiers()->getAllThroughRunningAncestors("after");
			}
		});
		
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		$function4 = function(){};
		$function5 = function(){};
		$function6 = function(){};
		$function7 = function(){};
		$function8 = function(){};
		
		$specs[0]->getContextModifiers()->add($function1, 'after');
		$specs[0]->getContextModifiers()->add($function2, 'after');
		$specs[1]->getContextModifiers()->add($function3, 'after');
		$specs[1]->getContextModifiers()->add($function4, 'after');
		$specs[2]->getContextModifiers()->add($function5, 'after');
		$specs[2]->getContextModifiers()->add($function6, 'after');
		$specs['checkpoint']->getContextModifiers()->add($function7, 'after');
		$specs['checkpoint']->getContextModifiers()->add($function8, 'after');
		
		$specs[0]->run();
		$this->assertSame(
			array(
				array(
					array('function' => $function8, 'type' => 'after'),
					array('function' => $function7, 'type' => 'after'),
					array('function' => $function4, 'type' => 'after'),
					array('function' => $function3, 'type' => 'after'),
					array('function' => $function2, 'type' => 'after'),
					array('function' => $function1, 'type' => 'after'),
					
				),
				array(
					array('function' => $function8, 'type' => 'after'),
					array('function' => $function7, 'type' => 'after'),
					array('function' => $function6, 'type' => 'after'),
					array('function' => $function5, 'type' => 'after'),
					array('function' => $function2, 'type' => 'after'),
					array('function' => $function1, 'type' => 'after'),
				),
			),
			$returnValues
		);
	}
	
	public function testGetAllThroughRunningAncestors_TypeIsAfter_IgnoresTypeCase() {
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->getContextModifiers()->add($function1, 'after');
		$spec->getContextModifiers()->add($function2, 'after');
		
		$this->assertSame(array(
			array('function' => $function2, 'type' => 'after'),
			array('function' => $function1, 'type' => 'after'),
		), $spec->getContextModifiers()->getAllThroughRunningAncestors('AFTer'));
	}
	
	public function testGetAllThroughRunningAncestors_TypeIsAfter_ReturnsEmptyArrayWhenNoRows() {
		$spec = new Spec();
		$this->assertSame(array(), $spec->getContextModifiers()->getAllThroughRunningAncestors('after'));
		
		$spec->getContextModifiers()->add(function(){}, 'after');
		$spec->getContextModifiers()->remove(0);
		$this->assertSame(array(), $spec->getContextModifiers()->getAllThroughRunningAncestors('after'));
		
		$spec->getContextModifiers()->add(function(){}, 'after');
		$spec->getContextModifiers()->removeAll();
		$this->assertSame(array(), $spec->getContextModifiers()->getAllThroughRunningAncestors('after'));
	}
	
/**/
	
	public function testRemove_RemovesValueByIndex() {
		$spec = new Spec();
		$spec->getContextModifiers()->add(function(){}, 'before');
		$spec->getContextModifiers()->add(function(){}, 'after');
		$spec->getContextModifiers()->remove(0);
		$spec->getContextModifiers()->remove(1);
		$this->assertSame(array(), $spec->getContextModifiers()->getAll());
	}

	public function testRemove_PreventsIndexes() {
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		$function4 = function(){};
		
		$spec = new Spec();
		$spec->getContextModifiers()->add($function1, 'before');
		$spec->getContextModifiers()->add($function2, 'after');
		$spec->getContextModifiers()->add($function3, 'before');
		
		$spec->getContextModifiers()->remove(1);
		$this->assertSame(array(
			0 => array('function' => $function1, 'type' => 'before'),
			2 => array('function' => $function3, 'type' => 'before'),
		), $spec->getContextModifiers()->getAll());
		
		$spec->getContextModifiers()->remove(0);
		$this->assertSame(array(
			2 => array('function' => $function3, 'type' => 'before'),
		), $spec->getContextModifiers()->getAll());
		
		$spec->getContextModifiers()->remove(2);
		$this->assertSame(array(), $spec->getContextModifiers()->getAll());
		
		$spec->getContextModifiers()->add($function4, 'before');
		$this->assertSame(array(
			3 => array('function' => $function4, 'type' => 'before'),
		), $spec->getContextModifiers()->getAll());
	}
	
	public function testRemove_CallOnRun_ThrowsExceptionAndDoesNotRemoveValue() {
		\spectrum\core\config::registerEventListener('onEndingSpecExecuteBefore', function(SpecInterface $spec) use(&$exception) {
			try {
				$spec->getContextModifiers()->remove(0);
			} catch (\Exception $e) {
				$exception = $e;
			}
		});
		
		$function1 = function(){};

		$spec = new Spec();
		$spec->getContextModifiers()->add($function1, 'before');
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\core\Exception', $exception);
		$this->assertSame('Call of "\spectrum\core\ContextModifiers::remove" method is forbidden on run', $exception->getMessage());
		$this->assertSame(array(
			array('function' => $function1, 'type' => 'before'), 
		), $spec->getContextModifiers()->getAll());
	}
	
/**/
	
	public function testRemoveAll_RemovesAllValues() {
		$spec = new Spec();
		$spec->getContextModifiers()->add(function(){}, 'before');
		$spec->getContextModifiers()->add(function(){}, 'after');
		$spec->getContextModifiers()->removeAll();
		$this->assertSame(array(), $spec->getContextModifiers()->getAll());
	}

	public function testRemoveAll_DoesNotPreventIndexes() {
		$spec = new Spec();
		
		$spec->getContextModifiers()->add(function(){}, 'before');
		$spec->getContextModifiers()->add(function(){}, 'after');
		$spec->getContextModifiers()->removeAll();
		$this->assertSame(array(), $spec->getContextModifiers()->getAll());
		
		$function1 = function(){};
		$spec->getContextModifiers()->add($function1, 'before');
		$this->assertSame(array(
			0 => array('function' => $function1, 'type' => 'before'),
		), $spec->getContextModifiers()->getAll());
	}
	
	public function testRemoveAll_CallOnRun_ThrowsExceptionAndDoesNotRemoveValues() {
		\spectrum\core\config::registerEventListener('onEndingSpecExecuteBefore', function(SpecInterface $spec) use(&$exception) {
			try {
				$spec->getContextModifiers()->removeAll();
			} catch (\Exception $e) {
				$exception = $e;
			}
		});
		
		$function1 = function(){};

		$spec = new Spec();
		$spec->getContextModifiers()->add($function1, 'before');
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\core\Exception', $exception);
		$this->assertSame('Call of "\spectrum\core\ContextModifiers::removeAll" method is forbidden on run', $exception->getMessage());
		$this->assertSame(array(
			array('function' => $function1, 'type' => 'before'), 
		), $spec->getContextModifiers()->getAll());
	}
}