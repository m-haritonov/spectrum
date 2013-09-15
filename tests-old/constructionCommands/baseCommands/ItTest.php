<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\constructionCommands\commands;
use spectrum\constructionCommands\manager;
use \spectrum\core\SpecContainerArgumentsProvider;
use \spectrum\core\SpecItemIt;

require_once __DIR__ . '/../../init.php';

class ItTest extends \spectrum\constructionCommands\commands\Test
{
	public function testParamsVariants_ShouldBeAcceptName()
	{
		$it = manager::it('foo');
		$this->assertTrue($it instanceof SpecItemIt);
		$this->assertEquals('foo', $it->getName());
	}

	public function testParamsVariants_ShouldBeAcceptNameAndSettingsString()
	{
		$it = manager::it('foo', 'koi-8');
		$this->assertTrue($it instanceof SpecItemIt);
		$this->assertEquals('foo', $it->getName());
		$this->assertEquals('koi-8', $it->output->getInputEncoding());
	}

	public function testParamsVariants_ShouldBeAcceptNameAndSettingsInteger()
	{
		$it = manager::it('foo', 2);
		$this->assertTrue($it instanceof SpecItemIt);
		$this->assertEquals('foo', $it->getName());
		$this->assertEquals(2, $it->errorHandling->getCatchPhpErrors());
	}

	public function testParamsVariants_ShouldBeAcceptNameAndSettingsBoolean()
	{
		$it = manager::it('foo', true);
		$this->assertTrue($it instanceof SpecItemIt);
		$this->assertEquals('foo', $it->getName());
		$this->assertEquals(-1, $it->errorHandling->getCatchPhpErrors());
	}

	public function testParamsVariants_ShouldBeAcceptNameAndSettingsArray()
	{
		$it = manager::it('foo', array('inputEncoding' => 'koi-8'));
		$this->assertTrue($it instanceof SpecItemIt);
		$this->assertEquals('foo', $it->getName());
		$this->assertEquals('koi-8', $it->output->getInputEncoding());
	}
	
/**/

	public function testParamsVariants_ShouldBeAcceptNameAndTestCallback()
	{
		$testCallback = function(){};
		$it = manager::it('foo', $testCallback);
		$this->assertTrue($it instanceof SpecItemIt);
		$this->assertEquals('foo', $it->getName());
		$this->assertSame($testCallback, $it->getTestCallback());
	}
	
	public function testParamsVariants_ShouldBeAcceptNameAndTestCallbackAndSettingsString()
	{
		$testCallback = function(){};
		$it = manager::it('foo', $testCallback, 'koi-8');
		$this->assertTrue($it instanceof SpecItemIt);
		$this->assertEquals('foo', $it->getName());
		$this->assertSame($testCallback, $it->getTestCallback());
		$this->assertEquals('koi-8', $it->output->getInputEncoding());
	}
	
	public function testParamsVariants_ShouldBeAcceptNameAndTestCallbackAndSettingsInteger()
	{
		$testCallback = function(){};
		$it = manager::it('foo', $testCallback, 2);
		$this->assertTrue($it instanceof SpecItemIt);
		$this->assertEquals('foo', $it->getName());
		$this->assertSame($testCallback, $it->getTestCallback());
		$this->assertEquals(2, $it->errorHandling->getCatchPhpErrors());
	}
	
	public function testParamsVariants_ShouldBeAcceptNameAndTestCallbackAndSettingsBoolean()
	{
		$testCallback = function(){};
		$it = manager::it('foo', $testCallback, true);
		$this->assertTrue($it instanceof SpecItemIt);
		$this->assertEquals('foo', $it->getName());
		$this->assertSame($testCallback, $it->getTestCallback());
		$this->assertEquals(-1, $it->errorHandling->getCatchPhpErrors());
	}
	
	public function testParamsVariants_ShouldBeAcceptNameAndTestCallbackAndSettingsArray()
	{
		$testCallback = function(){};
		$it = manager::it('foo', $testCallback, array('inputEncoding' => 'koi-8'));
		$this->assertTrue($it instanceof SpecItemIt);
		$this->assertEquals('foo', $it->getName());
		$this->assertSame($testCallback, $it->getTestCallback());
		$this->assertEquals('koi-8', $it->output->getInputEncoding());
	}

/**/

	public function testParamsVariants_ShouldBeAcceptNameAndArgumentsProviderAndTestCallback()
	{
		$testCallback = function(){};
		$spec = manager::it('foo', array('bar'), $testCallback);
		$this->assertTrue($spec instanceof SpecContainerArgumentsProvider);
		$this->assertEquals(1, count($spec->getSpecs()));
		$this->assertEquals('foo', $spec->getName());
	}

	public function testParamsVariants_ShouldBeAcceptNameAndArgumentsProviderAndTestCallbackAndSettingsString()
	{
		$testCallback = function(){};
		$spec = manager::it('foo', array('bar'), $testCallback, 'koi-8');
		$this->assertTrue($spec instanceof SpecContainerArgumentsProvider);
		$this->assertEquals(1, count($spec->getSpecs()));
		$this->assertEquals('foo', $spec->getName());
		$this->assertEquals('koi-8', $spec->output->getInputEncoding());
	}

	public function testParamsVariants_ShouldBeAcceptNameAndArgumentsProviderAndTestCallbackAndSettingsInteger()
	{
		$testCallback = function(){};
		$spec = manager::it('foo', array('bar'), $testCallback, 2);
		$this->assertTrue($spec instanceof SpecContainerArgumentsProvider);
		$this->assertEquals(1, count($spec->getSpecs()));
		$this->assertEquals('foo', $spec->getName());
		$this->assertEquals(2, $spec->errorHandling->getCatchPhpErrors());
	}

	public function testParamsVariants_ShouldBeAcceptNameAndArgumentsProviderAndTestCallbackAndSettingsBoolean()
	{
		$testCallback = function(){};
		$spec = manager::it('foo', array('bar'), $testCallback, true);
		$this->assertTrue($spec instanceof SpecContainerArgumentsProvider);
		$this->assertEquals(1, count($spec->getSpecs()));
		$this->assertEquals('foo', $spec->getName());
		$this->assertEquals(-1, $spec->errorHandling->getCatchPhpErrors());
	}

	public function testParamsVariants_ShouldBeAcceptNameAndArgumentsProviderAndTestCallbackAndSettingsArray()
	{
		$testCallback = function(){};
		$spec = manager::it('foo', array('bar'), $testCallback, array('inputEncoding' => 'koi-8'));
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
				manager::it('', function(){});
			});
			$it->run();
		});
	}

	public function testShouldBeThrowExceptionIfArgumentsProviderNotArray()
	{
		$this->assertThrowException('\spectrum\constructionCommands\Exception', '"it" should be accept array as $argumentsProvider', function()
		{
			manager::it('foo', 'bar', function(){});
		});
	}

/**/

	public function testShouldNotBeCallTestCallbackDuringDeclaringState()
	{
		manager::it('foo', function() use(&$isCalled){ $isCalled = true; });
		$this->assertNull($isCalled);
	}

/**/

	public function testNoParentCommand_ShouldBeAddInstanceToRootDescribe()
	{
		$it = manager::it('foo');
		$this->assertSame(array($it), \spectrum\RootSpec::getOnceInstance()->getSpecs());
	}

	public function testInsideDescribeCommand_ShouldBeAddInstanceToParentDescribe()
	{
		$describe = manager::describe('', function() use(&$it) {
			$it = manager::it('foo');
		});

		$this->assertSame(array($it), $describe->getSpecs());
	}

	public function testInsideContextCommand_ShouldBeAddInstanceToParentContext()
	{
		$context = manager::context('', function() use(&$it) {
			$it = manager::it('foo');
		});

		$this->assertSame(array($it), $context->getSpecs());
	}
}