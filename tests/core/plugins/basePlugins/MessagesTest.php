<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core\plugins\basePlugins;
use spectrum\core\Spec;

require_once __DIR__ . '/../../../init.php';

class MessagesTest extends \spectrum\tests\Test
{
	public function testRemovesAllMessagesBeforeEachRun()
	{
		\spectrum\tests\Test::$temp["messages"] = null;
		
		$this->registerPluginWithCodeInEvent('
			\spectrum\tests\Test::$temp["messages"] = $this->getOwnerSpec()->messages->getAll();
		', 'onEndingSpecExecuteBefore');
		
		$spec = new Spec();
		
		$spec->messages->add('aaa aaa');
		$this->assertSame(array('aaa aaa'), $spec->messages->getAll());
		$spec->run();
		$this->assertSame(array(), $spec->messages->getAll());
		$this->assertSame(array(), \spectrum\tests\Test::$temp["messages"]);
		
		$spec->messages->add('bbb bbb');
		$this->assertSame(array('bbb bbb'), $spec->messages->getAll());
		$spec->run();
		$this->assertSame(array(), $spec->messages->getAll());
		$this->assertSame(array(), \spectrum\tests\Test::$temp["messages"]);
	}
	
	public function testDoesNotRemoveMessagesAfterRun()
	{
		$this->registerPluginWithCodeInEvent('
			$this->getOwnerSpec()->messages->add("aaa aaa");
		', 'onEndingSpecExecuteBefore');
		
		$spec = new Spec();
		$this->assertSame(array(), $spec->messages->getAll());
		
		$spec->run();
		$this->assertSame(array("aaa aaa"), $spec->messages->getAll());
		
		$spec->run();
		$this->assertSame(array("aaa aaa"), $spec->messages->getAll());
	}
	
/**/
	
	public function testAdd_AddsMessageToArray()
	{
		$spec = new Spec();
		
		$spec->messages->add('aaa aaa');
		$this->assertSame(array('aaa aaa'), $spec->messages->getAll());
		
		$spec->messages->add('bbb bbb');
		$this->assertSame(array('aaa aaa', 'bbb bbb'), $spec->messages->getAll());
		
		$spec->messages->add('ccc ccc');
		$this->assertSame(array('aaa aaa', 'bbb bbb', 'ccc ccc'), $spec->messages->getAll());
	}
	
	public function testAdd_SpecWithChildren_ThrowsExceptionAndDoesNotAddMessage()
	{
		$specs = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		$this->assertThrowsException('\spectrum\core\plugins\Exception', 'Messages::add() method available only on specs without children', function() use($specs){
			$specs[0]->messages->add('aaa aaa');
		});
		
		$this->assertSame(array(), $specs[0]->messages->getAll());
		$this->assertSame(array(), $specs[1]->messages->getAll());
	}
	
	public function testAdd_SpecWithoutChildren_DoesNotThrowsExceptionAndAddsMessage()
	{
		$specs = $this->createSpecsTree('
			Spec
			->Spec
		');
		
		$specs[1]->messages->add('aaa aaa');
		$this->assertSame(array('aaa aaa'), $specs[1]->messages->getAll());
	}
	
	public function testAdd_CallOnRun_DoesNotThrowsExceptionAndAddsMessage()
	{
		\spectrum\tests\Test::$temp["exception"] = null;
		
		$this->registerPluginWithCodeInEvent('
			try
			{
				$this->getOwnerSpec()->messages->add("aaa aaa");
			}
			catch (\Exception $e)
			{
				\spectrum\tests\Test::$temp["exception"] = $e;
			}
		');
		

		$spec = new Spec();
		$spec->run();
		
		$this->assertSame(null, \spectrum\tests\Test::$temp["exception"]);
		$this->assertSame(array('aaa aaa'), $spec->messages->getAll());
	}
	
/**/
	
	public function testGetAll_ReturnsArrayWithAllAddedMessages()
	{
		$spec = new Spec();
		$spec->messages->add('aaa aaa');
		$spec->messages->add('bbb bbb');
		$spec->messages->add('ccc ccc');
		
		$this->assertSame(array('aaa aaa', 'bbb bbb', 'ccc ccc'), $spec->messages->getAll());
	}
	
	public function testGetAll_ReturnsEmptyArrayByDefault()
	{
		$spec = new Spec();
		$this->assertSame(array(), $spec->messages->getAll());
	}
}