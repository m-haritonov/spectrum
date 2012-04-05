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
use \spectrum\core\SpecContainerArgumentsProvider;
use \spectrum\core\SpecItemIt;

require_once dirname(__FILE__) . '/../../init.php';

class ItTest extends \spectrum\constructionCommands\baseCommands\Test
{
	public function testParamsVariants_ShouldBeAcceptName()
	{
		$it = Manager::it('foo');
		$this->assertTrue($it instanceof SpecItemIt);
		$this->assertEquals('foo', $it->getName());
	}

	public function testParamsVariants_ShouldBeAcceptNameAndSettingsString()
	{
		$it = Manager::it('foo', 'koi-8');
		$this->assertTrue($it instanceof SpecItemIt);
		$this->assertEquals('foo', $it->getName());
		$this->assertEquals('koi-8', $it->output->getInputEncoding());
	}

	public function testParamsVariants_ShouldBeAcceptNameAndSettingsInteger()
	{
		$it = Manager::it('foo', 2);
		$this->assertTrue($it instanceof SpecItemIt);
		$this->assertEquals('foo', $it->getName());
		$this->assertEquals(2, $it->errorHandling->getCatchPhpErrors());
	}

	public function testParamsVariants_ShouldBeAcceptNameAndSettingsBoolean()
	{
		$it = Manager::it('foo', true);
		$this->assertTrue($it instanceof SpecItemIt);
		$this->assertEquals('foo', $it->getName());
		$this->assertEquals(-1, $it->errorHandling->getCatchPhpErrors());
	}

	public function testParamsVariants_ShouldBeAcceptNameAndSettingsArray()
	{
		$it = Manager::it('foo', array('inputEncoding' => 'koi-8'));
		$this->assertTrue($it instanceof SpecItemIt);
		$this->assertEquals('foo', $it->getName());
		$this->assertEquals('koi-8', $it->output->getInputEncoding());
	}
	
/**/

	public function testParamsVariants_ShouldBeAcceptNameAndTestCallback()
	{
		$testCallback = function(){};
		$it = Manager::it('foo', $testCallback);
		$this->assertTrue($it instanceof SpecItemIt);
		$this->assertEquals('foo', $it->getName());
		$this->assertSame($testCallback, $it->getTestCallback());
	}
	
	public function testParamsVariants_ShouldBeAcceptNameAndTestCallbackAndSettingsString()
	{
		$testCallback = function(){};
		$it = Manager::it('foo', $testCallback, 'koi-8');
		$this->assertTrue($it instanceof SpecItemIt);
		$this->assertEquals('foo', $it->getName());
		$this->assertSame($testCallback, $it->getTestCallback());
		$this->assertEquals('koi-8', $it->output->getInputEncoding());
	}
	
	public function testParamsVariants_ShouldBeAcceptNameAndTestCallbackAndSettingsInteger()
	{
		$testCallback = function(){};
		$it = Manager::it('foo', $testCallback, 2);
		$this->assertTrue($it instanceof SpecItemIt);
		$this->assertEquals('foo', $it->getName());
		$this->assertSame($testCallback, $it->getTestCallback());
		$this->assertEquals(2, $it->errorHandling->getCatchPhpErrors());
	}
	
	public function testParamsVariants_ShouldBeAcceptNameAndTestCallbackAndSettingsBoolean()
	{
		$testCallback = function(){};
		$it = Manager::it('foo', $testCallback, true);
		$this->assertTrue($it instanceof SpecItemIt);
		$this->assertEquals('foo', $it->getName());
		$this->assertSame($testCallback, $it->getTestCallback());
		$this->assertEquals(-1, $it->errorHandling->getCatchPhpErrors());
	}
	
	public function testParamsVariants_ShouldBeAcceptNameAndTestCallbackAndSettingsArray()
	{
		$testCallback = function(){};
		$it = Manager::it('foo', $testCallback, array('inputEncoding' => 'koi-8'));
		$this->assertTrue($it instanceof SpecItemIt);
		$this->assertEquals('foo', $it->getName());
		$this->assertSame($testCallback, $it->getTestCallback());
		$this->assertEquals('koi-8', $it->output->getInputEncoding());
	}

/**/

	public function testParamsVariants_ShouldBeAcceptNameAndArgumentsProviderAndTestCallback()
	{
		$testCallback = function(){};
		$spec = Manager::it('foo', array('bar'), $testCallback);
		$this->assertTrue($spec instanceof SpecContainerArgumentsProvider);
		$this->assertEquals(1, count($spec->getSpecs()));
		$this->assertEquals('foo', $spec->getName());
	}

	public function testParamsVariants_ShouldBeAcceptNameAndArgumentsProviderAndTestCallbackAndSettingsString()
	{
		$testCallback = function(){};
		$spec = Manager::it('foo', array('bar'), $testCallback, 'koi-8');
		$this->assertTrue($spec instanceof SpecContainerArgumentsProvider);
		$this->assertEquals(1, count($spec->getSpecs()));
		$this->assertEquals('foo', $spec->getName());
		$this->assertEquals('koi-8', $spec->output->getInputEncoding());
	}

	public function testParamsVariants_ShouldBeAcceptNameAndArgumentsProviderAndTestCallbackAndSettingsInteger()
	{
		$testCallback = function(){};
		$spec = Manager::it('foo', array('bar'), $testCallback, 2);
		$this->assertTrue($spec instanceof SpecContainerArgumentsProvider);
		$this->assertEquals(1, count($spec->getSpecs()));
		$this->assertEquals('foo', $spec->getName());
		$this->assertEquals(2, $spec->errorHandling->getCatchPhpErrors());
	}

	public function testParamsVariants_ShouldBeAcceptNameAndArgumentsProviderAndTestCallbackAndSettingsBoolean()
	{
		$testCallback = function(){};
		$spec = Manager::it('foo', array('bar'), $testCallback, true);
		$this->assertTrue($spec instanceof SpecContainerArgumentsProvider);
		$this->assertEquals(1, count($spec->getSpecs()));
		$this->assertEquals('foo', $spec->getName());
		$this->assertEquals(-1, $spec->errorHandling->getCatchPhpErrors());
	}

	public function testParamsVariants_ShouldBeAcceptNameAndArgumentsProviderAndTestCallbackAndSettingsArray()
	{
		$testCallback = function(){};
		$spec = Manager::it('foo', array('bar'), $testCallback, array('inputEncoding' => 'koi-8'));
		$this->assertTrue($spec instanceof SpecContainerArgumentsProvider);
		$this->assertEquals(1, count($spec->getSpecs()));
		$this->assertEquals('foo', $spec->getName());
		$this->assertEquals('koi-8', $spec->output->getInputEncoding());
	}

/**/

	public function testShouldBeThrowExceptionIfCalledAtRunningState()
	{
		$this->assertThrowException('\spectrum\constructionCommands\Exception', '"it" should be call only at declaring state', function()
		{
			$it = new \spectrum\core\SpecItemIt();
			$it->errorHandling->setCatchExceptions(false);
			$it->setTestCallback(function(){
				Manager::it('', function(){});
			});
			$it->run();
		});
	}

	public function testShouldBeThrowExceptionIfArgumentsProviderNotArray()
	{
		$this->assertThrowException('\spectrum\constructionCommands\Exception', '"it" should be accept array as $argumentsProvider', function()
		{
			Manager::it('foo', 'bar', function(){});
		});
	}

/**/

	public function testShouldNotBeCallTestCallbackDuringDeclaringState()
	{
		Manager::it('foo', function() use(&$isCalled){ $isCalled = true; });
		$this->assertNull($isCalled);
	}

/**/

	public function testNoParentCommand_ShouldBeAddInstanceToRootDescribe()
	{
		$it = Manager::it('foo');
		$this->assertSame(array($it), \spectrum\RootDescribe::getOnceInstance()->getSpecs());
	}

	public function testInsideDescribeCommand_ShouldBeAddInstanceToParentDescribe()
	{
		$describe = Manager::describe('', function() use(&$it) {
			$it = Manager::it('foo');
		});

		$this->assertSame(array($it), $describe->getSpecs());
	}

	public function testInsideContextCommand_ShouldBeAddInstanceToParentContext()
	{
		$context = Manager::context('', function() use(&$it) {
			$it = Manager::it('foo');
		});

		$this->assertSame(array($it), $context->getSpecs());
	}
}