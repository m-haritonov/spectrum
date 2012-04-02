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

namespace net\mkharitonov\spectrum\core\plugins;
require_once dirname(__FILE__) . '/../../init.php';

use net\mkharitonov\spectrum\core\SpecContainerDescribe;
use net\mkharitonov\spectrum\core\Config;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class ManagerTest extends \net\mkharitonov\spectrum\core\Test
{
	public function setUp()
	{
		parent::setUp();
		Manager::unregisterAllPlugins();
	}

	public function testShouldBeHaveRegisteredBasePluginsByDefault()
	{
		$this->restoreStaticProperties('\net\mkharitonov\spectrum\core\plugins\Manager');

		$this->assertSame(array(
			'matchers' => array('class' => '\net\mkharitonov\spectrum\core\plugins\basePlugins\Matchers', 'activateMoment' => 'whenCallOnce'),
			'builders' => array('class' => '\net\mkharitonov\spectrum\core\plugins\basePlugins\worldCreators\Builders', 'activateMoment' => 'whenCallOnce'),
			'destroyers' => array('class' => '\net\mkharitonov\spectrum\core\plugins\basePlugins\worldCreators\Destroyers', 'activateMoment' => 'whenCallOnce'),
			'selector' => array('class' => '\net\mkharitonov\spectrum\core\plugins\basePlugins\Selector', 'activateMoment' => 'whenCallOnce'),
			'identify' => array('class' => '\net\mkharitonov\spectrum\core\plugins\basePlugins\Identify', 'activateMoment' => 'whenCallOnce'),
			'errorHandling' => array('class' => '\net\mkharitonov\spectrum\core\plugins\basePlugins\ErrorHandling', 'activateMoment' => 'whenCallOnce'),
			'output' => array('class' => '\net\mkharitonov\spectrum\core\plugins\basePlugins\Output', 'activateMoment' => 'whenCallOnce'),
			'messages' => array('class' => '\net\mkharitonov\spectrum\core\plugins\basePlugins\Messages', 'activateMoment' => 'whenCallOnce'),
			'patterns' => array('class' => '\net\mkharitonov\spectrum\core\plugins\basePlugins\Patterns', 'activateMoment' => 'whenCallOnce'),
		), Manager::getRegisteredPlugins());
	}

/**/

	public function testRegisterPlugin_ShouldBeCollectPlugins()
	{
		$this->assertSame(array(), Manager::getRegisteredPlugins());

		Manager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\plugins\Plugin', 'whenConstructOnce');
		$this->assertSame(array(
			'foo' => array('class' => '\net\mkharitonov\spectrum\core\plugins\Plugin', 'activateMoment' => 'whenConstructOnce'),
		), Manager::getRegisteredPlugins());

		Manager::registerPlugin('bar', '\net\mkharitonov\spectrum\core\plugins\basePlugins\stack\Indexed', 'whenCallOnce');
		$this->assertSame(array(
			'foo' => array('class' => '\net\mkharitonov\spectrum\core\plugins\Plugin', 'activateMoment' => 'whenConstructOnce'),
			'bar' => array('class' => '\net\mkharitonov\spectrum\core\plugins\basePlugins\stack\Indexed', 'activateMoment' => 'whenCallOnce'),
		), Manager::getRegisteredPlugins());

		Manager::registerPlugin('baz', '\net\mkharitonov\spectrum\core\plugins\basePlugins\Matchers', 'whenCallAlways');
		$this->assertSame(array(
			'foo' => array('class' => '\net\mkharitonov\spectrum\core\plugins\Plugin', 'activateMoment' => 'whenConstructOnce'),
			'bar' => array('class' => '\net\mkharitonov\spectrum\core\plugins\basePlugins\stack\Indexed', 'activateMoment' => 'whenCallOnce'),
			'baz' => array('class' => '\net\mkharitonov\spectrum\core\plugins\basePlugins\Matchers', 'activateMoment' => 'whenCallAlways'),
		), Manager::getRegisteredPlugins());
	}

	public function testRegisterPlugin_ShouldBeReplaceExistsPlugin()
	{
		Manager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\plugins\Plugin', 'whenCallAlways');
		Manager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\plugins\basePlugins\stack\Indexed', 'whenConstructOnce');

		$this->assertSame(
			array('foo' => array('class' => '\net\mkharitonov\spectrum\core\plugins\basePlugins\stack\Indexed', 'activateMoment' => 'whenConstructOnce'))
			, Manager::getRegisteredPlugins()
		);
	}

	public function testRegisterPlugin_ShouldBeSetStackIndexedClassAndWhenCallOnceActivatedMomentByDefault()
	{
		Manager::registerPlugin('foo');

		$this->assertSame(
			array('foo' => array('class' => '\net\mkharitonov\spectrum\core\plugins\basePlugins\stack\Indexed', 'activateMoment' => 'whenCallOnce'))
			, Manager::getRegisteredPlugins()
		);
	}

	public function testRegisterPlugin_ShouldThrowExceptionIfPluginNotImplementInterface()
	{
		$this->assertThrowException('\net\mkharitonov\spectrum\core\plugins\Exception', function() {
			Manager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\NotPlugin');
		});
	}

	public function testRegisterPlugin_ShouldThrowExceptionIfSetIncorrectActivateMoment()
	{
		$this->assertThrowException('\net\mkharitonov\spectrum\core\plugins\Exception', function() {
			Manager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\plugins\Plugin', 'foo');
		});
	}

	public function testRegisterPlugin_ShouldAcceptAllowedActivateMoments()
	{
		Manager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\plugins\Plugin', 'whenConstructOnce');
		Manager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\plugins\Plugin', 'whenCallOnce');
		Manager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\plugins\Plugin', 'whenCallAlways');
	}

	public function testRegisterPlugin_ShouldBeThrowExceptionIfNotAllowPluginsRegistration()
	{
		Config::setAllowPluginsRegistration(false);
		$this->assertThrowException('\net\mkharitonov\spectrum\core\plugins\Exception', 'Plugins registration deny', function(){
			Manager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\plugins\Plugin');
		});
	}

	public function testRegisterPlugin_ShouldBeThrowExceptionIfPluginExistsAndNotAllowPluginsOverride()
	{
		Config::setAllowPluginsOverride(false);
		Manager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\plugins\Plugin');
		$this->assertThrowException('\net\mkharitonov\spectrum\core\plugins\Exception', 'Plugins override deny', function(){
			Manager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\plugins\Plugin');
		});
	}

/**/

	public function testRegisterPlugins_ShouldBeSubstituteDefaultClassAndActivateMoment()
	{
		Manager::registerPlugins(array(
			'foo' => array('class' => '\net\mkharitonov\spectrum\core\plugins\Plugin', 'activateMoment' => 'whenCallAlways'),
			'bar' => array('class' => '\net\mkharitonov\spectrum\core\plugins\Plugin'),
			'baz' => array(),
			'qux' => null,
		));

		$this->assertSame(array(
			'foo' => array('class' => '\net\mkharitonov\spectrum\core\plugins\Plugin', 'activateMoment' => 'whenCallAlways'),
			'bar' => array('class' => '\net\mkharitonov\spectrum\core\plugins\Plugin', 'activateMoment' => 'whenCallOnce'),
			'baz' => array('class' => '\net\mkharitonov\spectrum\core\plugins\basePlugins\stack\Indexed', 'activateMoment' => 'whenCallOnce'),
			'qux' => array('class' => '\net\mkharitonov\spectrum\core\plugins\basePlugins\stack\Indexed', 'activateMoment' => 'whenCallOnce'),
		), Manager::getRegisteredPlugins());
	}

/**/

	public function testUnregisterPlugin()
	{
		Manager::registerPlugin('foo');
		Manager::unregisterPlugin('foo');

		$this->assertFalse(Manager::hasRegisteredPlugin('foo'));
		$this->assertSame(array(), Manager::getRegisteredPlugins());
	}

	public function testUnregisterPlugin_ShouldBeThrowExceptionIfNotAllowPluginsOverride()
	{
		Config::setAllowPluginsOverride(false);
		$this->assertThrowException('\net\mkharitonov\spectrum\core\plugins\Exception', 'Plugins override deny', function(){
			Manager::unregisterPlugin('foo');
		});
	}

/**/

	public function testUnregisterAllPlugins_ShouldBeLeaveEmptyArray()
	{
		$this->assertSame(array(), Manager::getRegisteredPlugins());
	}

	public function testUnregisterAllPlugins_ShouldBeThrowExceptionIfNotAllowPluginsOverride()
	{
		Config::setAllowPluginsOverride(false);
		$this->assertThrowException('\net\mkharitonov\spectrum\core\plugins\Exception', 'Plugins override deny', function(){
			Manager::unregisterAllPlugins();
		});
	}

/**/

	public function testHasRegisteredPlugin_ShouldBeReturnTrueIfPluginExists()
	{
		Manager::registerPlugin('foo');
		$this->assertTrue(Manager::hasRegisteredPlugin('foo'));
	}

	public function testHasRegisteredPlugin_ShouldBeReturnFalseIfPluginNotExists()
	{
		$this->assertFalse(Manager::hasRegisteredPlugin('foo'));
	}

/**/

	public function testGetAccessNamesForEventPlugins_ShouldBeReturnAllPluginsWhichImplementsEventInterface()
	{
		Manager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnRunStub');
		Manager::registerPlugin('bar', '\net\mkharitonov\spectrum\core\testEnv\PluginEventOnRunStub');
		Manager::registerPlugin('baz', '\net\mkharitonov\spectrum\core\plugins\Plugin');

		$this->assertSame(array('foo', 'bar'), Manager::getAccessNamesForEventPlugins('onRunBefore'));
	}

	public function testGetAccessNamesForEventPlugins_ShouldBeThrowExceptionIfEventNameNotExists()
	{
		$this->assertThrowException('\net\mkharitonov\spectrum\core\plugins\Exception', '"onFooBar"', function() {
			Manager::getAccessNamesForEventPlugins('onFooBar');
		});
	}

/**/

	public function testGetRegisteredPlugin()
	{
		Manager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\plugins\Plugin', 'whenCallAlways');

		$this->assertSame(
			array('class' => '\net\mkharitonov\spectrum\core\plugins\Plugin', 'activateMoment' => 'whenCallAlways')
			, Manager::getRegisteredPlugin('foo')
		);
	}

	public function testGetRegisteredPlugin_ShouldBeThrowExceptionIfPluginWithAccessNameNotExists()
	{
		$this->assertThrowException('\net\mkharitonov\spectrum\core\plugins\Exception', function() {
			Manager::getRegisteredPlugin('foo');
		});
	}

/**/

	public function testCreatePluginInstance_ShouldBeReturnRespectivePluginInstance()
	{
		Manager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\plugins\Plugin');
		Manager::registerPlugin('bar', '\net\mkharitonov\spectrum\core\plugins\basePlugins\stack\Indexed');

		$this->assertEquals('\net\mkharitonov\spectrum\core\plugins\Plugin', '\\' . get_class(Manager::createPluginInstance(new SpecContainerDescribe(), 'foo')));
		$this->assertEquals('\net\mkharitonov\spectrum\core\plugins\basePlugins\stack\Indexed', '\\' . get_class(Manager::createPluginInstance(new SpecContainerDescribe(), 'bar')));
	}

	public function testCreatePluginInstance_ShouldBeSetAccessNameAndOwnerToPluginInstance()
	{
		Manager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\plugins\Plugin');

		$spec = new SpecContainerDescribe();
		$plugin = Manager::createPluginInstance($spec, 'foo');

		$this->assertEquals('foo', $plugin->getAccessName());
		$this->assertSame($spec, $plugin->getOwnerSpec());
	}

	public function testCreatePluginInstance_ShouldBeReturnNewInstanceAlways()
	{
		Manager::registerPlugin('foo', '\net\mkharitonov\spectrum\core\plugins\Plugin');
		Manager::registerPlugin('bar', '\net\mkharitonov\spectrum\core\plugins\Plugin');

		$spec = new SpecContainerDescribe();
		$this->assertNotSame(
			Manager::createPluginInstance($spec, 'foo'),
			Manager::createPluginInstance($spec, 'bar')
		);
	}

	public function testCreatePluginInstance_ShouldBeThrowExceptionIfPluginWithAccessNameNotExists()
	{
		$this->assertThrowException('\net\mkharitonov\spectrum\core\plugins\Exception', function() {
			Manager::createPluginInstance(new SpecContainerDescribe(), 'foo');
		});
	}
}