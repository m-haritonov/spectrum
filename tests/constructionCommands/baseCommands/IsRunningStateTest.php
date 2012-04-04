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
class IsRunningStateTest extends \spectrum\constructionCommands\baseCommands\Test
{
	public function testNoParentCommand_ShouldBeReturnFalse()
	{
		$this->assertFalse(Manager::isRunningState());
	}

	public function testInsideDescribeCommand_ShouldBeReturnFalse()
	{
		Manager::describe('', function() use(&$result){
			$result = Manager::isRunningState();
		});

		$this->assertFalse($result);
	}

	public function testInsideContextCommand_ShouldBeReturnFalse()
	{
		Manager::context('', function() use(&$result){
			$result = Manager::isRunningState();
		});

		$this->assertFalse($result);
	}

/**/

	public function testInsideAddMatcherCommand_ShouldBeReturnTrue()
	{
		Manager::addMatcher('foo', function() use(&$result){
			$result = Manager::isRunningState();
		});

		$it = Manager::it('', function(){
			be('')->foo();
		});

		$it->run();

		$this->assertTrue($result);
	}

	public function testInsideBeforeEachCommand_ShouldBeReturnTrue()
	{
		Manager::beforeEach(function() use(&$result){
			$result = Manager::isRunningState();
		});

		$it = Manager::it('', function(){});
		$it->run();

		$this->assertTrue($result);
	}

	public function testInsideAfterEachCommand_ShouldBeReturnTrue()
	{
		Manager::afterEach(function() use(&$result){
			$result = Manager::isRunningState();
		});

		$it = Manager::it('', function(){});
		$it->run();

		$this->assertTrue($result);
	}

	public function testInsideItCommand_ShouldBeReturnTrue()
	{
		$it = Manager::it('', function() use(&$result){
			$result = Manager::isRunningState();
		});
		$it->run();

		$this->assertTrue($result);
	}

	public function testInsideItCommand_InsideDescribeCommand_ShouldBeReturnTrue()
	{
		$describe = Manager::describe('', function() use(&$result)
		{
			Manager::it('', function() use(&$result){
				$result = Manager::isRunningState();
			});
		});
		$describe->run();

		$this->assertTrue($result);
	}
}