<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\basePlugins;
require_once dirname(__FILE__) . '/../../../init.php';

use \spectrum\core\SpecItemIt;

class MessagesTest extends Test
{
	public function testGetAll_ShouldBeReturnEmptyArrayByDefault()
	{
		$it = new SpecItemIt();
		$this->assertSame(array(), $it->messages->getAll());
	}

	public function testGetAll_ShouldBeReturnAllMessages()
	{
		$it = new SpecItemIt();
		$it->messages->add('foo bar');
		$it->messages->add('bazz');
		$this->assertSame(array('foo bar', 'bazz'), $it->messages->getAll());
	}

	public function testClear_ShouldBeRemoveAllMessages()
	{
		$it = new SpecItemIt();
		$it->messages->add('foo bar');
		$it->messages->add('bazz');
		$it->messages->clear();
		$this->assertSame(array(), $it->messages->getAll());
	}

	public function testSpecItemIt_ShouldBeClearMessagesBeforeRun()
	{
		$it = new SpecItemIt();
		$it->messages->add('foo');
		$it->setTestCallback(function() use($it){
			$it->messages->add('bar');
		});

		$it->run();
		$this->assertSame(array('bar'), $it->messages->getAll());
	}

	public function testSpecItemIt_ShouldNotBeClearMessagesAfterRun()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function() use($it){
			$it->messages->add('foo');
			$it->messages->add('bar');
		});

		$it->run();
		$this->assertSame(array('foo', 'bar'), $it->messages->getAll());
	}
}