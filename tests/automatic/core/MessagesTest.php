<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core;

use spectrum\core\Spec;
use spectrum\core\SpecInterface;

require_once __DIR__ . '/../../init.php';

class MessagesTest extends \spectrum\tests\automatic\Test {
	public function testAdd_AddsMessageToArray() {
		$spec = new Spec();
		
		$spec->getMessages()->add('aaa aaa');
		$this->assertSame(array('aaa aaa'), $spec->getMessages()->getAll());
		
		$spec->getMessages()->add('bbb bbb');
		$this->assertSame(array('aaa aaa', 'bbb bbb'), $spec->getMessages()->getAll());
		
		$spec->getMessages()->add('ccc ccc');
		$this->assertSame(array('aaa aaa', 'bbb bbb', 'ccc ccc'), $spec->getMessages()->getAll());
	}
	
	public function testAdd_SpecWithChildren_ThrowsExceptionAndDoesNotAddMessage() {
		$specs = $this->createSpecsByListPattern('
			Spec
			->Spec
		');
		
		$this->assertThrowsException('\spectrum\Exception', 'Messages::add() method available only on specs without children', function() use($specs){
			$specs[0]->getMessages()->add('aaa aaa');
		});
		
		$this->assertSame(array(), $specs[0]->getMessages()->getAll());
		$this->assertSame(array(), $specs[1]->getMessages()->getAll());
	}
	
	public function testAdd_SpecWithoutChildren_DoesNotThrowsExceptionAndAddsMessage() {
		$specs = $this->createSpecsByListPattern('
			Spec
			->Spec
		');
		
		$specs[1]->getMessages()->add('aaa aaa');
		$this->assertSame(array('aaa aaa'), $specs[1]->getMessages()->getAll());
	}
	
	public function testAdd_CallOnRun_DoesNotThrowsExceptionAndAddsMessage() {
		$spec = new Spec();
		$spec->getExecutor()->setFunction(function() use(&$spec, &$messages, &$exception) {
			$messages = $spec->getMessages();
			
			try {
				$spec->getMessages()->add("aaa aaa");
			} catch (\Exception $e) {
				$exception = $e;
			}
		});
		$spec->run();
		
		$this->assertSame(null, $exception);
		$this->assertSame(array('aaa aaa'), $messages->getAll());
	}
	
/**/
	
	public function testGetAll_ReturnsArrayWithAllAddedMessages() {
		$spec = new Spec();
		$spec->getMessages()->add('aaa aaa');
		$spec->getMessages()->add('bbb bbb');
		$spec->getMessages()->add('ccc ccc');
		
		$this->assertSame(array('aaa aaa', 'bbb bbb', 'ccc ccc'), $spec->getMessages()->getAll());
	}
	
	public function testGetAll_ReturnsEmptyArrayByDefault() {
		$spec = new Spec();
		$this->assertSame(array(), $spec->getMessages()->getAll());
	}
}