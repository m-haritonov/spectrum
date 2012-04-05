<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins;
require_once dirname(__FILE__) . '/../../init.php';

use spectrum\core\SpecContainerDescribe;
use spectrum\core\Config;

class ManagerTest extends \spectrum\core\Test
{
	public function setUp()
	{
		parent::setUp();
		Manager::unregisterAllPlugins();
	}

	public function testShouldBeHaveRegisteredBasePluginsByDefault()
	{
		$this->restoreStaticProperties('\spectrum\core\plugins\Manager');

		$this->assertSame(array(
			'matchers' => array('class' => '\spectrum\core\plugins\basePlugins\Matchers', 'activateMoment' => 'whenCallOnce'),
			'builders' => array('class' => '\spectrum\core\plugins\basePlugins\worldCreators\Builders', 'activateMoment' => 'whenCallOnce'),
			'destroyers' => array('class' => '\spectrum\core\plugins\basePlugins\worldCreators\Destroyers', 'activateMoment' => 'whenCallOnce'),
			'selector' => array('class' => '\spectrum\core\plugins\basePlugins\Selector', 'activateMoment' => 'whenCallOnce'),
			'identify' => array('class' => '\spectrum\core\plugins\basePlugins\Identify', 'activateMoment' => 'whenCallOnce'),
			'errorHandling' => array('class' => '\spectrum\core\plugins\basePlugins\ErrorHandling', 'activateMoment' => 'whenCallOnce'),
			'output' => array('class' => '\spectrum\core\plugins\basePlugins\Output', 'activateMoment' => 'whenCallOnce'),
			'messages' => array('class' => '\spectrum\core\plugins\basePlugins\Messages', 'activateMoment' => 'whenCallOnce'),
			'patterns' => array('class' => '\spectrum\core\plugins\basePlugins\Patterns', 'activateMoment' => 'whenCallOnce'),
		), Manager::getRegisteredPlugins());
	}

/**/

	public function testRegisterPlugin_ShouldBeCollectPlugins()
	{
		$this->assertSame(array(), Manager::getRegisteredPlugins());

		Manager::registerPlugin('foo', '\spectrum\core\plugins\Plugin', 'whenConstructOnce');
		$this->assertSame(array(
			'foo' => array('class' => '\spectrum\core\plugins\Plugin', 'activateMoment' => 'whenConstructOnce'),
		), Manager::getRegisteredPlugins());

		Manager::registerPlugin('bar', '\spectrum\core\plugins\basePlugins\stack\Indexed', 'whenCallOnce');
		$this->assertSame(array(
			'foo' => array('class' => '\spectrum\core\plugins\Plugin', 'activateMoment' => 'whenConstructOnce'),
			'bar' => array('class' => '\spectrum\core\plugins\basePlugins\stack\Indexed', 'activateMoment' => 'whenCallOnce'),
		), Manager::getRegisteredPlugins());

		Manager::registerPlugin('baz', '\spectrum\core\plugins\basePlugins\Matchers', 'whenCallAlways');
		$this->assertSame(array(
			'foo' => array('class' => '\spectrum\core\plugins\Plugin', 'activateMoment' => 'whenConstructOnce'),
			'bar' => array('class' => '\spectrum\core\plugins\basePlugins\stack\Indexed', 'activateMoment' => 'whenCallOnce'),
			'baz' => array('class' => '\spectrum\core\plugins\basePlugins\Matchers', 'activateMoment' => 'whenCallAlways'),
		), Manager::getRegisteredPlugins());
	}

	public function testRegisterPlugin_ShouldBeReplaceExistsPlugin()
	{
		Manager::registerPlugin('foo', '\spectrum\core\plugins\Plugin', 'whenCallAlways');
		Manager::registerPlugin('foo', '\spectrum\core\plugins\basePlugins\stack\Indexed', 'whenConstructOnce');

		$this->assertSame(
			array('foo' => array('class' => '\spectrum\core\plugins\basePlugins\stack\Indexed', 'activateMoment' => 'whenConstructOnce'))
			, Manager::getRegisteredPlugins()
		);
	}

	public function testRegisterPlugin_ShouldBeSetStackIndexedClassAndWhenCallOnceActivatedMomentByDefault()
	{
		Manager::registerPlugin('foo');

		$this->assertSame(
			array('foo' => array('class' => '\spectrum\core\plugins\basePlugins\stack\Indexed', 'activateMoment' => 'whenCallOnce'))
			, Manager::getRegisteredPlugins()
		);
	}

	public function testRegisterPlugin_ShouldThrowExceptionIfPluginNotImplementInterface()
	{
		$this->assertThrowException('\spectrum\core\plugins\Exception', function() {
			Manager::registerPlugin('foo', '\spectrum\core\testEnv\NotPlugin');
		});
	}

	public function testRegisterPlugin_ShouldThrowExceptionIfSetIncorrectActivateMoment()
	{
		$this->assertThrowException('\spectrum\core\plugins\Exception', function() {
			Manager::registerPlugin('foo', '\spectrum\core\plugins\Plugin', 'foo');
		});
	}

	public function testRegisterPlugin_ShouldAcceptAllowedActivateMoments()
	{
		Manager::registerPlugin('foo', '\spectrum\core\plugins\Plugin', 'whenConstructOnce');
		Manager::registerPlugin('foo', '\spectrum\core\plugins\Plugin', 'whenCallOnce');
		Manager::registerPlugin('foo', '\spectrum\core\plugins\Plugin', 'whenCallAlways');
	}

	public function testRegisterPlugin_ShouldBeThrowExceptionIfNotAllowPluginsRegistration()
	{
		Config::setAllowPluginsRegistration(false);
		$this->assertThrowException('\spectrum\core\plugins\Exception', 'Plugins registration deny', function(){
			Manager::registerPlugin('foo', '\spectrum\core\plugins\Plugin');
		});
	}

	public function testRegisterPlugin_ShouldBeThrowExceptionIfPluginExistsAndNotAllowPluginsOverride()
	{
		Config::setAllowPluginsOverride(false);
		Manager::registerPlugin('foo', '\spectrum\core\plugins\Plugin');
		$this->assertThrowException('\spectrum\core\plugins\Exception', 'Plugins override deny', function(){
			Manager::registerPlugin('foo', '\spectrum\core\plugins\Plugin');
		});
	}

/**/

	public function testRegisterPlugins_ShouldBeSubstituteDefaultClassAndActivateMoment()
	{
		Manager::registerPlugins(array(
			'foo' => array('class' => '\spectrum\core\plugins\Plugin', 'activateMoment' => 'whenCallAlways'),
			'bar' => array('class' => '\spectrum\core\plugins\Plugin'),
			'baz' => array(),
			'qux' => null,
		));

		$this->assertSame(array(
			'foo' => array('class' => '\spectrum\core\plugins\Plugin', 'activateMoment' => 'whenCallAlways'),
			'bar' => array('class' => '\spectrum\core\plugins\Plugin', 'activateMoment' => 'whenCallOnce'),
			'baz' => array('class' => '\spectrum\core\plugins\basePlugins\stack\Indexed', 'activateMoment' => 'whenCallOnce'),
			'qux' => array('class' => '\spectrum\core\plugins\basePlugins\stack\Indexed', 'activateMoment' => 'whenCallOnce'),
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
		$this->assertThrowException('\spectrum\core\plugins\Exception', 'Plugins override deny', function(){
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
		$this->assertThrowException('\spectrum\core\plugins\Exception', 'Plugins override deny', function(){
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
		Manager::registerPlugin('foo', '\spectrum\core\testEnv\PluginEventOnRunStub');
		Manager::registerPlugin('bar', '\spectrum\core\testEnv\PluginEventOnRunStub');
		Manager::registerPlugin('baz', '\spectrum\core\plugins\Plugin');

		$this->assertSame(array('foo', 'bar'), Manager::getAccessNamesForEventPlugins('onRunBefore'));
	}

	public function testGetAccessNamesForEventPlugins_ShouldBeThrowExceptionIfEventNameNotExists()
	{
		$this->assertThrowException('\spectrum\core\plugins\Exception', '"onFooBar"', function() {
			Manager::getAccessNamesForEventPlugins('onFooBar');
		});
	}

/**/

	public function testGetRegisteredPlugin()
	{
		Manager::registerPlugin('foo', '\spectrum\core\plugins\Plugin', 'whenCallAlways');

		$this->assertSame(
			array('class' => '\spectrum\core\plugins\Plugin', 'activateMoment' => 'whenCallAlways')
			, Manager::getRegisteredPlugin('foo')
		);
	}

	public function testGetRegisteredPlugin_ShouldBeThrowExceptionIfPluginWithAccessNameNotExists()
	{
		$this->assertThrowException('\spectrum\core\plugins\Exception', function() {
			Manager::getRegisteredPlugin('foo');
		});
	}

/**/

	public function testCreatePluginInstance_ShouldBeReturnRespectivePluginInstance()
	{
		Manager::registerPlugin('foo', '\spectrum\core\plugins\Plugin');
		Manager::registerPlugin('bar', '\spectrum\core\plugins\basePlugins\stack\Indexed');

		$this->assertEquals('\spectrum\core\plugins\Plugin', '\\' . get_class(Manager::createPluginInstance(new SpecContainerDescribe(), 'foo')));
		$this->assertEquals('\spectrum\core\plugins\basePlugins\stack\Indexed', '\\' . get_class(Manager::createPluginInstance(new SpecContainerDescribe(), 'bar')));
	}

	public function testCreatePluginInstance_ShouldBeSetAccessNameAndOwnerToPluginInstance()
	{
		Manager::registerPlugin('foo', '\spectrum\core\plugins\Plugin');

		$spec = new SpecContainerDescribe();
		$plugin = Manager::createPluginInstance($spec, 'foo');

		$this->assertEquals('foo', $plugin->getAccessName());
		$this->assertSame($spec, $plugin->getOwnerSpec());
	}

	public function testCreatePluginInstance_ShouldBeReturnNewInstanceAlways()
	{
		Manager::registerPlugin('foo', '\spectrum\core\plugins\Plugin');
		Manager::registerPlugin('bar', '\spectrum\core\plugins\Plugin');

		$spec = new SpecContainerDescribe();
		$this->assertNotSame(
			Manager::createPluginInstance($spec, 'foo'),
			Manager::createPluginInstance($spec, 'bar')
		);
	}

	public function testCreatePluginInstance_ShouldBeThrowExceptionIfPluginWithAccessNameNotExists()
	{
		$this->assertThrowException('\spectrum\core\plugins\Exception', function() {
			Manager::createPluginInstance(new SpecContainerDescribe(), 'foo');
		});
	}
}