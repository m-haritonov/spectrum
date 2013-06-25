<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\constructionCommands\commands;
use spectrum\constructionCommands\manager;

require_once __DIR__ . '/../../init.php';

class MessageTest extends \spectrum\constructionCommands\commands\Test
{
	public function testShouldBeAddMessageToMessagesPlugin()
	{
		$it = manager::it('foo', function(){
			manager::message('bar baz');
			manager::message('foooo');
		});

		$this->assertSame(array(), $it->messages->getAll());
		$it->run();
		$this->assertSame(array('bar baz', 'foooo'), $it->messages->getAll());
	}
}