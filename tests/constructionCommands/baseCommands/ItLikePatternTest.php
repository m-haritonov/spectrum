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
class ItLikePatternTest extends \spectrum\constructionCommands\baseCommands\Test
{
	public function testShouldBeThrowExceptionIfPatternNotExists()
	{
		$this->assertThrowException('\spectrum\core\plugins\Exception', '"foo" not exists in plugin with access name "patterns"', function()
		{
			Manager::itLikePattern('foo');
		});
	}

	public function testShouldBeAllowToCallAtDeclaringState()
	{
		Manager::describe('', function() use(&$return){
			Manager::addPattern('Car', function(){});
			$return = Manager::itLikePattern('Car');
		});
		$this->assertTrue($return instanceof \spectrum\core\SpecContainerPattern);
	}

	public function testShouldBeThrowExceptionIfCalledAtRunningState()
	{
		$this->assertThrowException('\spectrum\constructionCommands\Exception', '"itLikePattern" should be call only at declaring state', function()
		{
			$it = new \spectrum\core\SpecItemIt();
			$it->errorHandling->setCatchExceptions(false);
			$it->setTestCallback(function(){
				Manager::itLikePattern('');
			});
			$it->run();
		});
	}

	public function testShouldBeReturnNewSpecContainerPatternInstance()
	{
		Manager::describe('', function() use(&$return1, &$return2){
			Manager::addPattern('Car', function(){});
			$return1 = Manager::itLikePattern('Car');
			$return2 = Manager::itLikePattern('Car');
		});

		$this->assertTrue($return1 instanceof \spectrum\core\SpecContainerPattern);
		$this->assertTrue($return2 instanceof \spectrum\core\SpecContainerPattern);
		$this->assertNotSame($return1, $return2);
	}

	public function testShouldBeSetPatternNameToSpecInstance()
	{
		Manager::describe('', function() use(&$return){
			Manager::addPattern('Car', function(){});
			$return = Manager::itLikePattern('Car');
		});

		$this->assertEquals('Car', $return->getName());
	}

	public function testShouldBeSetPatternArgumentsToSpecInstance()
	{
		Manager::describe('', function() use(&$return){
			Manager::addPattern('Car', function(){});
			$return = Manager::itLikePattern('Car', 4, 'foo', array());
		});

		$this->assertSame(array(4, 'foo', array()), $return->getArguments());
	}

	public function testShouldBeCallCallbackDuringCall()
	{
		Manager::describe('', function() use(&$isCalled){
			Manager::addPattern('Car', function() use(&$isCalled){ $isCalled = true; });
			Manager::itLikePattern('Car');
		});

		$this->assertTrue($isCalled);
	}

	public function testShouldBePassArgumentsToCallback()
	{
		Manager::describe('', function() use(&$passedArgs){
			Manager::addPattern('Car', function($arg1, $arg2, $arg3) use(&$passedArgs){
				$passedArgs = func_get_args();
			});
			Manager::itLikePattern('Car', 'foo', 'bar', 110);
		});

		$this->assertSame(array('foo', 'bar', 110), $passedArgs);
	}


/**/

	public function testShouldBeReturnInstanceWithChildrenFromCallback()
	{
		Manager::describe('', function() use(&$spec){
			Manager::addPattern('Car', function(){
				Manager::it('foo');
				Manager::it('bar');
			});

			$spec = Manager::itLikePattern('Car');
		});

		$children = $spec->getSpecs();
		$this->assertEquals(2, count($children));

		$this->assertTrue($children[0] instanceof \spectrum\core\SpecItemIt);
		$this->assertEquals('foo', $children[0]->getName());

		$this->assertTrue($children[1] instanceof \spectrum\core\SpecItemIt);
		$this->assertEquals('bar', $children[1]->getName());
	}

	public function testShouldBeFindPatternInAncestors()
	{
		Manager::addPattern('Car', function(){});
		Manager::describe('', function() use(&$spec){
			$spec = Manager::itLikePattern('Car');
		});

		$this->assertTrue($spec instanceof \spectrum\core\SpecContainerPattern);
		$this->assertEquals('Car', $spec->getName());
	}

/**/

	public function testFirstLevelContainer_ShouldBeAddInstanceToRootDescribe()
	{
		Manager::addPattern('foo', function(){});
		Manager::addPattern('bar', function(){});
		Manager::addPattern('baz', function(){});

		Manager::itLikePattern('foo');
		Manager::itLikePattern('bar');
		Manager::itLikePattern('baz');

		$rootSpecs = \spectrum\RootDescribe::getOnceInstance()->getSpecs();

		$this->assertEquals(3, count($rootSpecs));
		$this->assertEquals('foo', $rootSpecs[0]->getName());
		$this->assertEquals('bar', $rootSpecs[1]->getName());
		$this->assertEquals('baz', $rootSpecs[2]->getName());
	}

	public function testFirstLevelContainer_ShouldNotBeAddInstanceToPreviousContainer()
	{
		Manager::addPattern('foo', function(){});
		$spec1 = Manager::itLikePattern('foo');
		Manager::itLikePattern('foo');

		$this->assertSame(array(), $spec1->getSpecs());
	}

/**/

	public function testSecondLevelContainer_ShouldBeAddInstanceToParentContainer()
	{
		$describe = Manager::describe('', function()
		{
			Manager::addPattern('foo', function(){});
			Manager::addPattern('bar', function(){});
			Manager::addPattern('baz', function(){});

			Manager::itLikePattern('foo');
			Manager::itLikePattern('bar');
			Manager::itLikePattern('baz');
		});

		$specs = $describe->getSpecs();

		$this->assertEquals(3, count($specs));
		$this->assertEquals('foo', $specs[0]->getName());
		$this->assertEquals('bar', $specs[1]->getName());
		$this->assertEquals('baz', $specs[2]->getName());
	}

	public function testSecondLevelContainer_ShouldNotBeAddInstanceToRootDescribe()
	{
		Manager::describe('foo', function(){
			Manager::addPattern('bar', function(){});

			Manager::itLikePattern('bar');
		});

		$rootSpecs = \spectrum\RootDescribe::getOnceInstance()->getSpecs();

		$this->assertEquals(1, count($rootSpecs));
		$this->assertEquals('foo', $rootSpecs[0]->getName());
	}

	public function testSecondLevelContainer_ShouldNotBeAddInstanceToPreviousContainer()
	{
		Manager::describe('', function() use(&$spec1)
		{
			Manager::addPattern('foo', function(){});

			$spec1 = Manager::itLikePattern('foo');
			Manager::itLikePattern('foo');
		});

		$this->assertSame(array(), $spec1->getSpecs());
	}

/**/

	public function testThirdLevelContainer_ShouldBeAddInstanceToParentContainer()
	{
		Manager::describe('', function() use(&$describe)
		{
			$describe = Manager::describe('', function()
			{
				Manager::addPattern('foo', function(){});
				Manager::addPattern('bar', function(){});
				Manager::addPattern('baz', function(){});

				Manager::itLikePattern('foo');
				Manager::itLikePattern('bar');
				Manager::itLikePattern('baz');
			});
		});

		$specs = $describe->getSpecs();

		$this->assertEquals(3, count($specs));
		$this->assertEquals('foo', $specs[0]->getName());
		$this->assertEquals('bar', $specs[1]->getName());
		$this->assertEquals('baz', $specs[2]->getName());
	}

	public function testThirdLevelContainer_ShouldNotBeAddInstanceToRootDescribe()
	{
		Manager::describe('foo', function(){
			Manager::describe('', function(){
				Manager::addPattern('bar', function(){});
				Manager::itLikePattern('bar');
			});
		});

		$rootSpecs = \spectrum\RootDescribe::getOnceInstance()->getSpecs();

		$this->assertEquals(1, count($rootSpecs));
		$this->assertEquals('foo', $rootSpecs[0]->getName());
	}

	public function testThirdLevelContainer_ShouldNotBeAddInstanceToAncestorContainer()
	{
		$describe = Manager::describe('', function(){
			Manager::describe('foo', function(){
				Manager::addPattern('bar', function(){});
				Manager::itLikePattern('bar');
			});
		});

		$specs = $describe->getSpecs();

		$this->assertEquals(1, count($specs));
		$this->assertEquals('foo', $specs[0]->getName());
	}

	public function testThirdLevelContainer_ShouldNotBeAddInstanceToPreviousContainer()
	{
		Manager::describe('', function() use(&$spec1)
		{
			Manager::describe('', function() use(&$spec1)
			{
				Manager::addPattern('foo', function(){});

				$spec1 = Manager::itLikePattern('foo');
				Manager::itLikePattern('foo');
			});
		});

		$this->assertSame(array(), $spec1->getSpecs());
	}
}