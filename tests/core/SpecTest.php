<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core;
require_once dirname(__FILE__) . '/../init.php';

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
		\spectrum\core\plugins\Manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginStub', 'whenConstructOnce');

		$spec = $this->createCurrentSpec();
		$this->assertEquals(1, \spectrum\core\testEnv\PluginStub::getActivationsCount());

		$this->assertSame(\spectrum\core\testEnv\PluginStub::getLastInstance(), $spec->callPlugin('foo'));
		$this->assertEquals(1, \spectrum\core\testEnv\PluginStub::getActivationsCount());

		$this->assertSame(\spectrum\core\testEnv\PluginStub::getLastInstance(), $spec->callPlugin('foo'));
		$this->assertEquals(1, \spectrum\core\testEnv\PluginStub::getActivationsCount());
	}

	public function testCallPlugin_WhenCallOnce_ShouldBeCreatePluginOnlyWhenFirstCallAndReturnCreatedInstanceLater()
	{
		\spectrum\core\plugins\Manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginStub', 'whenCallOnce');

		$spec = $this->createCurrentSpec();
		$this->assertEquals(0, \spectrum\core\testEnv\PluginStub::getActivationsCount());

		$activatedPlugin = $spec->callPlugin('foo');

		$this->assertSame(\spectrum\core\testEnv\PluginStub::getLastInstance(), $activatedPlugin);
		$this->assertEquals(1, \spectrum\core\testEnv\PluginStub::getActivationsCount());

		$this->assertSame(\spectrum\core\testEnv\PluginStub::getLastInstance(), $spec->callPlugin('foo'));
		$this->assertEquals(1, \spectrum\core\testEnv\PluginStub::getActivationsCount());
	}

	public function testCallPlugin_WhenCallAlways_ShouldBeCreatePluginWhenCallAlwaysAndReturnNewInstanceLater()
	{
		\spectrum\core\plugins\Manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginStub', 'whenCallAlways');

		$spec = $this->createCurrentSpec();
		$this->assertEquals(0, \spectrum\core\testEnv\PluginStub::getActivationsCount());

		$prevActivatedPlugin = $spec->callPlugin('foo');

		$this->assertSame(\spectrum\core\testEnv\PluginStub::getLastInstance(), $prevActivatedPlugin);
		$this->assertEquals(1, \spectrum\core\testEnv\PluginStub::getActivationsCount());

		$activatedPlugin = $spec->callPlugin('foo');
		$this->assertSame(\spectrum\core\testEnv\PluginStub::getLastInstance(), $activatedPlugin);
		$this->assertNotSame($prevActivatedPlugin, $activatedPlugin);
		$this->assertEquals(2, \spectrum\core\testEnv\PluginStub::getActivationsCount());
	}

	public function testCallPlugin_ShouldBeSupportAccessThroughMagicGetProperty()
	{
		\spectrum\core\plugins\Manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginStub', 'whenCallAlways');

		$spec = $this->createCurrentSpec();
		$activatedPlugin = $spec->foo;
		$this->assertSame(\spectrum\core\testEnv\PluginStub::getLastInstance(), $activatedPlugin);
	}

	public function testCallPlugin_ShouldBeThrowExceptionIfPluginWithAccessNameNotExists()
	{
		\spectrum\core\plugins\Manager::registerPlugin('foo');

		$spec = $this->createCurrentSpec();
		$this->assertThrowException('\spectrum\core\plugins\Exception', function() use($spec) {
			$spec->callPlugin('bar');
		});

		$this->assertThrowException('\spectrum\core\plugins\Exception', function() use($spec) {
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
			if (!in_array($spec, (array) \spectrum\Test::$tmp['uniqueCallsCount'], true))
				\spectrum\Test::$tmp['uniqueCallsCount'][] = $spec;

			\spectrum\Test::$tmp['asserts'][] = array(true, true);
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
			\spectrum\Test::$tmp['asserts'][] = array(false, true);
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
			\spectrum\Test::$tmp['asserts'][] = array(false, true);
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