<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\core\plugins;

use spectrum\core\Spec;

require_once __DIR__ . '/../../init.php';

class ContextModifiersTest extends \spectrum\tests\Test {
	public function testAdd_AddsFunctionAndType() {
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		$function4 = function(){};
		
		$spec = new Spec();
		$spec->contextModifiers->add($function1, 'before');
		$spec->contextModifiers->add($function2, 'after');
		$spec->contextModifiers->add($function3, 'before');
		$spec->contextModifiers->add($function4, 'after');
		
		$this->assertSame(array(
			array('function' => $function1, 'type' => 'before'),
			array('function' => $function2, 'type' => 'after'),
			array('function' => $function3, 'type' => 'before'),
			array('function' => $function4, 'type' => 'after'),
		), $spec->contextModifiers->getAll());
	}
	
	public function testAdd_ConvertsTypeToLowercase() {
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->contextModifiers->add($function1, 'beFOre');
		$spec->contextModifiers->add($function2, 'AFTer');
		
		$this->assertSame(array(
			array('function' => $function1, 'type' => 'before'),
			array('function' => $function2, 'type' => 'after'),
		), $spec->contextModifiers->getAll());
	}
	
	public function testAdd_CallOnRun_ThrowsExceptionAndDoesNotAddValue() {
		\spectrum\tests\Test::$temp["exception"] = null;
		
		$this->registerPluginWithCodeInEvent('
			try {
				$this->getOwnerSpec()->contextModifiers->add(function(){}, "before");
			} catch (\Exception $e) {
				\spectrum\tests\Test::$temp["exception"] = $e;
			}
		');
		

		$spec = new Spec();
		$spec->run();
		$this->assertInstanceOf('\spectrum\Exception', \spectrum\tests\Test::$temp["exception"]);
		$this->assertSame('Call of "\spectrum\core\plugins\ContextModifiers::add" method is forbidden on run', \spectrum\tests\Test::$temp["exception"]->getMessage());
		$this->assertSame(array(), $spec->contextModifiers->getAll());
	}
	
	public function testAdd_ThrowsExceptionForInvalidTypes() {
		$spec = new Spec();
		$this->assertThrowsException('\spectrum\Exception', 'Unknown type "aaa" in plugin "contextModifiers"', function() use($spec) {
			$spec->contextModifiers->add(function(){}, 'aaa');
		});
	}

/**/
	
	public function testGetAll_ThrowsExceptionForInvalidTypes() {
		$spec = new Spec();
		$this->assertThrowsException('\spectrum\Exception', 'Unknown type "aaa" in plugin "contextModifiers"', function() use($spec) {
			$spec->contextModifiers->getAll('aaa');
		});
	}
	
/**/
	
	public function testGetAll_TypeIsNull_ReturnsAllRowsWithAnyType() {
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		$function4 = function(){};
		
		$spec = new Spec();
		$spec->contextModifiers->add($function1, 'before');
		$spec->contextModifiers->add($function2, 'after');
		$spec->contextModifiers->add($function3, 'before');
		$spec->contextModifiers->add($function4, 'after');
		
		$this->assertSame(array(
			array('function' => $function1, 'type' => 'before'),
			array('function' => $function2, 'type' => 'after'),
			array('function' => $function3, 'type' => 'before'),
			array('function' => $function4, 'type' => 'after'),
		), $spec->contextModifiers->getAll());
	}
	
	public function testGetAll_TypeIsNull_ReturnsEmptyArrayWhenNoRows() {
		$spec = new Spec();
		$this->assertSame(array(), $spec->contextModifiers->getAll());
		
		$spec->contextModifiers->add(function(){}, 'before');
		$spec->contextModifiers->remove(0);
		$this->assertSame(array(), $spec->contextModifiers->getAll());
		
		$spec->contextModifiers->add(function(){}, 'before');
		$spec->contextModifiers->removeAll();
		$this->assertSame(array(), $spec->contextModifiers->getAll());
	}
	
/**/
	
	public function testGetAll_TypeIsBefore_ReturnsAllRowsWithSameType() {
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->contextModifiers->add($function1, 'before');
		$spec->contextModifiers->add(function(){}, 'after');
		$spec->contextModifiers->add($function2, 'before');
		$spec->contextModifiers->add(function(){}, 'after');
		
		$this->assertSame(array(
			0 => array('function' => $function1, 'type' => 'before'),
			2 => array('function' => $function2, 'type' => 'before'),
		), $spec->contextModifiers->getAll('before'));
	}
	
	public function testGetAll_TypeIsBefore_PreservesIndexes() {
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		
		$spec = new Spec();
		$spec->contextModifiers->add($function1, 'before');
		$spec->contextModifiers->add(function(){}, 'after');
		$spec->contextModifiers->add($function2, 'before');
		$spec->contextModifiers->add(function(){}, 'after');
		$spec->contextModifiers->add(function(){}, 'after');
		$spec->contextModifiers->add($function3, 'before');
		
		$this->assertSame(array(
			0 => array('function' => $function1, 'type' => 'before'),
			2 => array('function' => $function2, 'type' => 'before'),
			5 => array('function' => $function3, 'type' => 'before'),
		), $spec->contextModifiers->getAll('before'));
	}
	
	public function testGetAll_TypeIsBefore_IgnoresTypeCase() {
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->contextModifiers->add($function1, 'before');
		$spec->contextModifiers->add($function2, 'before');
		
		$this->assertSame(array(
			0 => array('function' => $function1, 'type' => 'before'),
			1 => array('function' => $function2, 'type' => 'before'),
		), $spec->contextModifiers->getAll('BEFOre'));
	}
	
	public function testGetAll_TypeIsBefore_ReturnsEmptyArrayWhenNoRows() {
		$spec = new Spec();
		$this->assertSame(array(), $spec->contextModifiers->getAll('before'));
		
		$spec->contextModifiers->add(function(){}, 'before');
		$spec->contextModifiers->remove(0);
		$this->assertSame(array(), $spec->contextModifiers->getAll('before'));
		
		$spec->contextModifiers->add(function(){}, 'before');
		$spec->contextModifiers->removeAll();
		$this->assertSame(array(), $spec->contextModifiers->getAll('before'));
	}
	
/**/
	
	public function testGetAll_TypeIsAfter_ReturnsAllRowsWithSameType() {
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->contextModifiers->add($function1, 'after');
		$spec->contextModifiers->add(function(){}, 'before');
		$spec->contextModifiers->add($function2, 'after');
		$spec->contextModifiers->add(function(){}, 'before');
		
		$this->assertSame(array(
			0 => array('function' => $function1, 'type' => 'after'),
			2 => array('function' => $function2, 'type' => 'after'),
		), $spec->contextModifiers->getAll('after'));
	}
	
	public function testGetAll_TypeIsAfter_PreservesIndexes() {
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		
		$spec = new Spec();
		$spec->contextModifiers->add($function1, 'after');
		$spec->contextModifiers->add(function(){}, 'before');
		$spec->contextModifiers->add($function2, 'after');
		$spec->contextModifiers->add(function(){}, 'before');
		$spec->contextModifiers->add(function(){}, 'before');
		$spec->contextModifiers->add($function3, 'after');
		
		$this->assertSame(array(
			0 => array('function' => $function1, 'type' => 'after'),
			2 => array('function' => $function2, 'type' => 'after'),
			5 => array('function' => $function3, 'type' => 'after'),
		), $spec->contextModifiers->getAll('after'));
	}
	
	public function testGetAll_TypeIsAfter_IgnoresTypeCase() {
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->contextModifiers->add($function1, 'after');
		$spec->contextModifiers->add($function2, 'after');
		
		$this->assertSame(array(
			0 => array('function' => $function1, 'type' => 'after'),
			1 => array('function' => $function2, 'type' => 'after'),
		), $spec->contextModifiers->getAll('AFTer'));
	}
	
	public function testGetAll_TypeIsAfter_ReturnsEmptyArrayWhenNoRows() {
		$spec = new Spec();
		$this->assertSame(array(), $spec->contextModifiers->getAll('after'));
		
		$spec->contextModifiers->add(function(){}, 'after');
		$spec->contextModifiers->remove(0);
		$this->assertSame(array(), $spec->contextModifiers->getAll('after'));
		
		$spec->contextModifiers->add(function(){}, 'after');
		$spec->contextModifiers->removeAll();
		$this->assertSame(array(), $spec->contextModifiers->getAll('after'));
	}
	
/**/
	
	public function testGetAllThroughRunningAncestors_ThrowsExceptionForInvalidTypes() {
		$spec = new Spec();
		$this->assertThrowsException('\spectrum\Exception', 'Unknown type "aaa" in plugin "contextModifiers"', function() use($spec) {
			$spec->contextModifiers->getAllThroughRunningAncestors('aaa');
		});
	}
	
/**/
	
	public function testGetAllThroughRunningAncestors_TypeIsBefore_ReturnsAllRowsWithSameType() {
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->contextModifiers->add($function1, 'before');
		$spec->contextModifiers->add(function(){}, 'after');
		$spec->contextModifiers->add($function2, 'before');
		$spec->contextModifiers->add(function(){}, 'after');
		
		$this->assertSame(array(
			array('function' => $function1, 'type' => 'before'),
			array('function' => $function2, 'type' => 'before'),
		), $spec->contextModifiers->getAllThroughRunningAncestors('before'));
	}
	
	public function testGetAllThroughRunningAncestors_TypeIsBefore_DoesNotPreserveIndexes() {
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		
		$spec = new Spec();
		$spec->contextModifiers->add($function1, 'before');
		$spec->contextModifiers->add(function(){}, 'after');
		$spec->contextModifiers->add($function2, 'before');
		$spec->contextModifiers->add(function(){}, 'after');
		$spec->contextModifiers->add(function(){}, 'after');
		$spec->contextModifiers->add($function3, 'before');
		
		$this->assertSame(array(
			array('function' => $function1, 'type' => 'before'),
			array('function' => $function2, 'type' => 'before'),
			array('function' => $function3, 'type' => 'before'),
		), $spec->contextModifiers->getAllThroughRunningAncestors('before'));
	}
	
	public function testGetAllThroughRunningAncestors_TypeIsBefore_ReturnsRowsInOrderFromRootRunningAncestorToSelf() {
		\spectrum\tests\Test::$temp["returnValues"] = array();
		
		$this->registerPluginWithCodeInEvent('
			if ($this->getOwnerSpec() === \spectrum\tests\Test::$temp["specs"]["checkpoint"]) {
				\spectrum\tests\Test::$temp["returnValues"][] = $this->getOwnerSpec()->contextModifiers->getAllThroughRunningAncestors("before");
			}
		');
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsByListPattern('
			Spec
			->Spec
			->Spec
			->->Spec(checkpoint)
		', array(1 => 'checkpoint'));
		
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		$function4 = function(){};
		$function5 = function(){};
		$function6 = function(){};
		$function7 = function(){};
		$function8 = function(){};
		
		\spectrum\tests\Test::$temp["specs"][0]->contextModifiers->add($function1, 'before');
		\spectrum\tests\Test::$temp["specs"][0]->contextModifiers->add($function2, 'before');
		\spectrum\tests\Test::$temp["specs"][1]->contextModifiers->add($function3, 'before');
		\spectrum\tests\Test::$temp["specs"][1]->contextModifiers->add($function4, 'before');
		\spectrum\tests\Test::$temp["specs"][2]->contextModifiers->add($function5, 'before');
		\spectrum\tests\Test::$temp["specs"][2]->contextModifiers->add($function6, 'before');
		\spectrum\tests\Test::$temp["specs"]['checkpoint']->contextModifiers->add($function7, 'before');
		\spectrum\tests\Test::$temp["specs"]['checkpoint']->contextModifiers->add($function8, 'before');
		
		\spectrum\tests\Test::$temp["specs"][0]->run();
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
		), \spectrum\tests\Test::$temp["returnValues"]);
	}
	
	public function testGetAllThroughRunningAncestors_TypeIsBefore_IgnoresTypeCase() {
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->contextModifiers->add($function1, 'before');
		$spec->contextModifiers->add($function2, 'before');
		
		$this->assertSame(array(
			array('function' => $function1, 'type' => 'before'),
			array('function' => $function2, 'type' => 'before'),
		), $spec->contextModifiers->getAllThroughRunningAncestors('BEFOre'));
	}
	
	public function testGetAllThroughRunningAncestors_TypeIsBefore_ReturnsEmptyArrayWhenNoRows() {
		$spec = new Spec();
		$this->assertSame(array(), $spec->contextModifiers->getAllThroughRunningAncestors('before'));
		
		$spec->contextModifiers->add(function(){}, 'before');
		$spec->contextModifiers->remove(0);
		$this->assertSame(array(), $spec->contextModifiers->getAllThroughRunningAncestors('before'));
		
		$spec->contextModifiers->add(function(){}, 'before');
		$spec->contextModifiers->removeAll();
		$this->assertSame(array(), $spec->contextModifiers->getAllThroughRunningAncestors('before'));
	}
	
/**/
	
	public function testGetAllThroughRunningAncestors_TypeIsAfter_ReturnsAllRowsWithSameType() {
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->contextModifiers->add($function1, 'after');
		$spec->contextModifiers->add(function(){}, 'before');
		$spec->contextModifiers->add($function2, 'after');
		$spec->contextModifiers->add(function(){}, 'before');
		
		$this->assertSame(array(
			array('function' => $function2, 'type' => 'after'),
			array('function' => $function1, 'type' => 'after'),
		), $spec->contextModifiers->getAllThroughRunningAncestors('after'));
	}
	
	public function testGetAllThroughRunningAncestors_TypeIsAfter_DoesNotPreserveIndexes() {
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		
		$spec = new Spec();
		$spec->contextModifiers->add($function1, 'after');
		$spec->contextModifiers->add(function(){}, 'before');
		$spec->contextModifiers->add($function2, 'after');
		$spec->contextModifiers->add(function(){}, 'before');
		$spec->contextModifiers->add(function(){}, 'before');
		$spec->contextModifiers->add($function3, 'after');
		
		$this->assertSame(array(
			array('function' => $function3, 'type' => 'after'),
			array('function' => $function2, 'type' => 'after'),
			array('function' => $function1, 'type' => 'after'),
		), $spec->contextModifiers->getAllThroughRunningAncestors('after'));
	}
	
	public function testGetAllThroughRunningAncestors_TypeIsAfter_ReturnsRowsInOrderFromSelfToRootRunningAncestor() {
		\spectrum\tests\Test::$temp["returnValues"] = array();
		
		$this->registerPluginWithCodeInEvent('
			if ($this->getOwnerSpec() === \spectrum\tests\Test::$temp["specs"]["checkpoint"]) {
				\spectrum\tests\Test::$temp["returnValues"][] = $this->getOwnerSpec()->contextModifiers->getAllThroughRunningAncestors("after");
			}
		');
		
		\spectrum\tests\Test::$temp["specs"] = $this->createSpecsByListPattern('
			Spec
			->Spec
			->Spec
			->->Spec(checkpoint)
		', array(1 => 'checkpoint'));
		
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		$function4 = function(){};
		$function5 = function(){};
		$function6 = function(){};
		$function7 = function(){};
		$function8 = function(){};
		
		\spectrum\tests\Test::$temp["specs"][0]->contextModifiers->add($function1, 'after');
		\spectrum\tests\Test::$temp["specs"][0]->contextModifiers->add($function2, 'after');
		\spectrum\tests\Test::$temp["specs"][1]->contextModifiers->add($function3, 'after');
		\spectrum\tests\Test::$temp["specs"][1]->contextModifiers->add($function4, 'after');
		\spectrum\tests\Test::$temp["specs"][2]->contextModifiers->add($function5, 'after');
		\spectrum\tests\Test::$temp["specs"][2]->contextModifiers->add($function6, 'after');
		\spectrum\tests\Test::$temp["specs"]['checkpoint']->contextModifiers->add($function7, 'after');
		\spectrum\tests\Test::$temp["specs"]['checkpoint']->contextModifiers->add($function8, 'after');
		
		\spectrum\tests\Test::$temp["specs"][0]->run();
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
			\spectrum\tests\Test::$temp["returnValues"]
		);
	}
	
	public function testGetAllThroughRunningAncestors_TypeIsAfter_IgnoresTypeCase() {
		$function1 = function(){};
		$function2 = function(){};
		
		$spec = new Spec();
		$spec->contextModifiers->add($function1, 'after');
		$spec->contextModifiers->add($function2, 'after');
		
		$this->assertSame(array(
			array('function' => $function2, 'type' => 'after'),
			array('function' => $function1, 'type' => 'after'),
		), $spec->contextModifiers->getAllThroughRunningAncestors('AFTer'));
	}
	
	public function testGetAllThroughRunningAncestors_TypeIsAfter_ReturnsEmptyArrayWhenNoRows() {
		$spec = new Spec();
		$this->assertSame(array(), $spec->contextModifiers->getAllThroughRunningAncestors('after'));
		
		$spec->contextModifiers->add(function(){}, 'after');
		$spec->contextModifiers->remove(0);
		$this->assertSame(array(), $spec->contextModifiers->getAllThroughRunningAncestors('after'));
		
		$spec->contextModifiers->add(function(){}, 'after');
		$spec->contextModifiers->removeAll();
		$this->assertSame(array(), $spec->contextModifiers->getAllThroughRunningAncestors('after'));
	}
	
/**/
	
	public function testRemove_RemovesValueByIndex() {
		$spec = new Spec();
		$spec->contextModifiers->add(function(){}, 'before');
		$spec->contextModifiers->add(function(){}, 'after');
		$spec->contextModifiers->remove(0);
		$spec->contextModifiers->remove(1);
		$this->assertSame(array(), $spec->contextModifiers->getAll());
	}

	public function testRemove_PreventsIndexes() {
		$function1 = function(){};
		$function2 = function(){};
		$function3 = function(){};
		$function4 = function(){};
		
		$spec = new Spec();
		$spec->contextModifiers->add($function1, 'before');
		$spec->contextModifiers->add($function2, 'after');
		$spec->contextModifiers->add($function3, 'before');
		
		$spec->contextModifiers->remove(1);
		$this->assertSame(array(
			0 => array('function' => $function1, 'type' => 'before'),
			2 => array('function' => $function3, 'type' => 'before'),
		), $spec->contextModifiers->getAll());
		
		$spec->contextModifiers->remove(0);
		$this->assertSame(array(
			2 => array('function' => $function3, 'type' => 'before'),
		), $spec->contextModifiers->getAll());
		
		$spec->contextModifiers->remove(2);
		$this->assertSame(array(), $spec->contextModifiers->getAll());
		
		$spec->contextModifiers->add($function4, 'before');
		$this->assertSame(array(
			3 => array('function' => $function4, 'type' => 'before'),
		), $spec->contextModifiers->getAll());
	}
	
	public function testRemove_CallOnRun_ThrowsExceptionAndDoesNotRemoveValue() {
		\spectrum\tests\Test::$temp["exception"] = null;
		
		$this->registerPluginWithCodeInEvent('
			try {
				$this->getOwnerSpec()->contextModifiers->remove(0);
			} catch (\Exception $e) {
				\spectrum\tests\Test::$temp["exception"] = $e;
			}
		');
		
		$function1 = function(){};

		$spec = new Spec();
		$spec->contextModifiers->add($function1, 'before');
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\Exception', \spectrum\tests\Test::$temp["exception"]);
		$this->assertSame('Call of "\spectrum\core\plugins\ContextModifiers::remove" method is forbidden on run', \spectrum\tests\Test::$temp["exception"]->getMessage());
		$this->assertSame(array(
			array('function' => $function1, 'type' => 'before'), 
		), $spec->contextModifiers->getAll());
	}
	
/**/
	
	public function testRemoveAll_RemovesAllValues() {
		$spec = new Spec();
		$spec->contextModifiers->add(function(){}, 'before');
		$spec->contextModifiers->add(function(){}, 'after');
		$spec->contextModifiers->removeAll();
		$this->assertSame(array(), $spec->contextModifiers->getAll());
	}

	public function testRemoveAll_DoesNotPreventIndexes() {
		$spec = new Spec();
		
		$spec->contextModifiers->add(function(){}, 'before');
		$spec->contextModifiers->add(function(){}, 'after');
		$spec->contextModifiers->removeAll();
		$this->assertSame(array(), $spec->contextModifiers->getAll());
		
		$function1 = function(){};
		$spec->contextModifiers->add($function1, 'before');
		$this->assertSame(array(
			0 => array('function' => $function1, 'type' => 'before'),
		), $spec->contextModifiers->getAll());
	}
	
	public function testRemoveAll_CallOnRun_ThrowsExceptionAndDoesNotRemoveValues() {
		\spectrum\tests\Test::$temp["exception"] = null;
		
		$this->registerPluginWithCodeInEvent('
			try {
				$this->getOwnerSpec()->contextModifiers->removeAll();
			} catch (\Exception $e) {
				\spectrum\tests\Test::$temp["exception"] = $e;
			}
		');
		
		$function1 = function(){};

		$spec = new Spec();
		$spec->contextModifiers->add($function1, 'before');
		$spec->run();
		
		$this->assertInstanceOf('\spectrum\Exception', \spectrum\tests\Test::$temp["exception"]);
		$this->assertSame('Call of "\spectrum\core\plugins\ContextModifiers::removeAll" method is forbidden on run', \spectrum\tests\Test::$temp["exception"]->getMessage());
		$this->assertSame(array(
			array('function' => $function1, 'type' => 'before'), 
		), $spec->contextModifiers->getAll());
	}
}