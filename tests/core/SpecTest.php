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

namespace net\mkharitonov\spectrum\core;
require_once dirname(__FILE__) . '/../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
abstract class SpecTest extends Test
{
	public function testConstructor_ShouldBeCanAcceptNoArguments()
	{
		$spec = $this->createCurrentSpec();
		$this->assertNull($spec->getName());
	}

/**/
	public function testCallPlugin_WhenConstructOnce_ShouldBeCreatePluginInSpecConstructorAndReturnCreatedInstanceLater()
	{
		\net\mkharitonov\spectrum\core\plugins\Manager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginStub', 'whenConstructOnce');

		$spec = $this->createCurrentSpec();
		$this->assertEquals(1, \net\mkharitonov\spectrum\core\testEnv\PluginStub::getActivationsCount());

		$this->assertSame(\net\mkharitonov\spectrum\core\testEnv\PluginStub::getLastInstance(), $spec->callPlugin('foo'));
		$this->assertEquals(1, \net\mkharitonov\spectrum\core\testEnv\PluginStub::getActivationsCount());

		$this->assertSame(\net\mkharitonov\spectrum\core\testEnv\PluginStub::getLastInstance(), $spec->callPlugin('foo'));
		$this->assertEquals(1, \net\mkharitonov\spectrum\core\testEnv\PluginStub::getActivationsCount());
	}

	public function testCallPlugin_WhenCallOnce_ShouldBeCreatePluginOnlyWhenFirstCallAndReturnCreatedInstanceLater()
	{
		\net\mkharitonov\spectrum\core\plugins\Manager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginStub', 'whenCallOnce');

		$spec = $this->createCurrentSpec();
		$this->assertEquals(0, \net\mkharitonov\spectrum\core\testEnv\PluginStub::getActivationsCount());

		$activatedPlugin = $spec->callPlugin('foo');

		$this->assertSame(\net\mkharitonov\spectrum\core\testEnv\PluginStub::getLastInstance(), $activatedPlugin);
		$this->assertEquals(1, \net\mkharitonov\spectrum\core\testEnv\PluginStub::getActivationsCount());

		$this->assertSame(\net\mkharitonov\spectrum\core\testEnv\PluginStub::getLastInstance(), $spec->callPlugin('foo'));
		$this->assertEquals(1, \net\mkharitonov\spectrum\core\testEnv\PluginStub::getActivationsCount());
	}

	public function testCallPlugin_WhenCallAlways_ShouldBeCreatePluginWhenCallAlwaysAndReturnNewInstanceLater()
	{
		\net\mkharitonov\spectrum\core\plugins\Manager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginStub', 'whenCallAlways');

		$spec = $this->createCurrentSpec();
		$this->assertEquals(0, \net\mkharitonov\spectrum\core\testEnv\PluginStub::getActivationsCount());

		$prevActivatedPlugin = $spec->callPlugin('foo');

		$this->assertSame(\net\mkharitonov\spectrum\core\testEnv\PluginStub::getLastInstance(), $prevActivatedPlugin);
		$this->assertEquals(1, \net\mkharitonov\spectrum\core\testEnv\PluginStub::getActivationsCount());

		$activatedPlugin = $spec->callPlugin('foo');
		$this->assertSame(\net\mkharitonov\spectrum\core\testEnv\PluginStub::getLastInstance(), $activatedPlugin);
		$this->assertNotSame($prevActivatedPlugin, $activatedPlugin);
		$this->assertEquals(2, \net\mkharitonov\spectrum\core\testEnv\PluginStub::getActivationsCount());
	}

	public function testCallPlugin_ShouldBeSupportAccessThroughMagicGetProperty()
	{
		\net\mkharitonov\spectrum\core\plugins\Manager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginStub', 'whenCallAlways');

		$spec = $this->createCurrentSpec();
		$activatedPlugin = $spec->foo;
		$this->assertSame(\net\mkharitonov\spectrum\core\testEnv\PluginStub::getLastInstance(), $activatedPlugin);
	}

	public function testCallPlugin_ShouldBeThrowExceptionIfPluginWithAccessNameNotExists()
	{
		\net\mkharitonov\spectrum\core\plugins\Manager::registerPlugin('foo');

		$spec = $this->createCurrentSpec();
		$this->assertThrowException('\net\mkharitonov\spectrum\core\plugins\Exception', function() use($spec) {
			$spec->callPlugin('bar');
		});

		$this->assertThrowException('\net\mkharitonov\spectrum\core\plugins\Exception', function() use($spec) {
			$spec->bar;
		});
	}


/**/

	public function testSetGetName()
	{
		$spec = $this->createCurrentSpec();

		$spec->setName('foo');
		$this->assertEquals('foo', $spec->getName());

		$spec->setName('bar');
		$this->assertEquals('bar', $spec->getName());
	}

	public function testSetName_ShouldBeAcceptNull()
	{
		$spec = $this->createCurrentSpec();
		$spec->setName(null);
		$this->assertNull($spec->getName());
	}

/**/

	public function testSetGetParent()
	{
		$spec = $this->createCurrentSpec();

		$containerSpec = new SpecContainerDescribe();
		$spec->setParent($containerSpec);
		$this->assertSame($containerSpec, $spec->getParent());

		$containerSpec = new SpecContainerDescribe();
		$spec->setParent($containerSpec);
		$this->assertSame($containerSpec, $spec->getParent());
	}

	public function testGetParent_ShouldBeReturnNullByDefault()
	{
		$spec = $this->createCurrentSpec();
		$this->assertNull($spec->getParent());
	}

	public function testSetParent_ShouldBeAcceptNull()
	{
		$spec = $this->createCurrentSpec();
		$spec->setParent(null);
		$this->assertNull($spec->getParent());
	}

	public function testSetParent_ShouldBeAcceptOnlyContainerSpec()
	{
		$spec = $this->createCurrentSpec();
		$this->assertThrowException('\Exception', function() use($spec) {
			$spec->setParent(new SpecItemIt());
		});
	}

/**/

	public function testEnable_ShouldBeEnableSpec()
	{
		$spec = $this->createCurrentSpec();
		$spec->disable();
		$spec->enable();
		$this->assertTrue($spec->isEnabled());
	}

//	public function testEnable_ShouldBeResetTemporarilyValue()
//	{
//		$spec = $this->createCurrentSpec();
//		$spec->disable();
//		$spec->enableTemporarily();
//		$spec->enable();
//		$spec->run();
//		$this->assertTrue($spec->isEnabled());
//	}

	public function testDisable_ShouldBeEnableSpec()
	{
		$spec = $this->createCurrentSpec();
		$spec->enable();
		$spec->disable();
		$this->assertFalse($spec->isEnabled());
	}

//	public function testDisable_ShouldBeResetTemporarilyValue()
//	{
//		$spec = $this->createCurrentSpec();
//		$spec->disableTemporarily();
//		$spec->disable();
//		$spec->run();
//		$this->assertFalse($spec->isEnabled());
//	}
	
	public function testIsEnabled_ShouldBeTrueByDefault()
	{
		$this->assertTrue($this->createCurrentSpec()->isEnabled());
	}

	public function testIsEnabled_ShouldBeTrueByDefaultAfterRun()
	{
		$spec = $this->createCurrentSpec();
		$spec->run();
		$this->assertTrue($spec->isEnabled());
	}


/*** Test ware ***/

	/**
	 * @return Spec|SpecContainer
	 */
	public function createSpecWithAssertRun($specMockClass)
	{
		$spec = new $specMockClass();
		$spec->__injectFunctionToRun(function() use ($spec)
		{
			if (!in_array($spec, (array) \net\mkharitonov\spectrum\Test::$tmp['uniqueCallsCount'], true))
				\net\mkharitonov\spectrum\Test::$tmp['uniqueCallsCount'][] = $spec;

			\net\mkharitonov\spectrum\Test::$tmp['asserts'][] = array(true, true);
		});

		return $spec;
	}

	/**
	 * @return Spec|SpecContainer
	 */
	public function createSpecWithAssertNotRun($specMockClass)
	{
		$spec = new $specMockClass();
		$spec->__injectFunctionToRun(function(){
			\net\mkharitonov\spectrum\Test::$tmp['asserts'][] = array(false, true);
		});

		return $spec;
	}

	/**
	 * @return Spec|SpecContainer
	 */
	public function createDisabledSpecWithAssertNotRun($specMockClass)
	{
		$spec = new $specMockClass();
		$spec->__injectFunctionToRun(function(){
			\net\mkharitonov\spectrum\Test::$tmp['asserts'][] = array(false, true);
		});
		$spec->disable();

		return $spec;
	}

	protected function createContextWithRunResult($result)
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function() use($it, $result)
		{
			if ($result !== null)
				$it->getRunResultsBuffer()->addResult($result);
		});

		$spec = new SpecContainerContext();
		$spec->addSpec($it);

		return $spec;
	}

	protected function createItWithRunResult($result)
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function() use($it, $result)
		{
			if ($result !== null)
				$it->getRunResultsBuffer()->addResult($result);
		});

		return $it;
	}
}