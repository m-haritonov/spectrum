<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\baseCommands;
use spectrum\constructionCommands\Manager;

require_once dirname(__FILE__) . '/../../init.php';

class MessageTest extends \spectrum\constructionCommands\baseCommands\Test
{
	public function testShouldBeAddMessageToMessagesPlugin()
	{
		$it = Manager::it('foo', function(){
			Manager::message('bar baz');
			Manager::message('foooo');
		});

		$this->assertSame(array(), $it->messages->getAll());
		$it->run();
		$this->assertSame(array('bar baz', 'foooo'), $it->messages->getAll());
	}
}