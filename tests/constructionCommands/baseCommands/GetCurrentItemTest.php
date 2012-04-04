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

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class GetCurrentItemTest extends \spectrum\constructionCommands\baseCommands\Test
{
	public function testShouldBeAllowToCallAtRunningState()
	{
		$it = Manager::it('', function() use(&$return) {
			$return = Manager::getCurrentItem('');
		});

		$it->run();
		$this->assertSame($it, $return);
	}

	public function testShouldBeThrowExceptionIfCalledAtDeclaringState()
	{
		$this->assertThrowException('\spectrum\constructionCommands\Exception', '"getCurrentItem"', function(){
			Manager::describe('', function(){
				Manager::getCurrentItem('');
			});
		});
	}
	
	public function testShouldBeReturnRunningSpecItemInstance()
	{
		$it = Manager::it('', function() use(&$return) {
			$return = Manager::getCurrentItem('');
		});

		$it->run();
		$this->assertSame($it, $return);
	}
}