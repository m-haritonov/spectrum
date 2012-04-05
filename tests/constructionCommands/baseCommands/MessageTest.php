<?php
/*
 * Spectrum
 *
 * Copyright (c) 2011 Mikhail Kharitonov <mvkharitonov@gmail.com>
 * All rights reserved.
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
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