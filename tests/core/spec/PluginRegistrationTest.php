<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core\plugins;
require_once __DIR__ . '/../../init.php';

use spectrum\core\config;
use spectrum\core\Spec;

class PluginRegistrationTest extends \spectrum\tests\core\Test
{
	public function setUp()
	{
		parent::setUp();
		Spec::unregisterAllPlugins();
	}

	public function testShouldBeHaveRegisteredBasePluginsByDefault()
	{
		$this->restoreStaticProperties('\spectrum\core\plugins\manager');

		$this->assertSame(array(
			'reports',
			'contexts',
			'errorHandling',
			'testFunction',
			'matchers',
			'messages',
			'output',
		), array_keys(Spec::getAllRegisteredPlugins()));
	}

/**/

	public function testRegisterPlugin_ShouldBeCollectPlugins()
	{
		$this->assertSame(array(), Spec::getAllRegisteredPlugins());

		Spec::registerPlugin('foo', '\spectrum\core\plugins\Plugin', 'specConstruct');
		$this->assertSame(array(
			'foo' => array('class' => '\spectrum\core\plugins\Plugin', 'activateMoment' => 'specConstruct'),
		), Spec::getAllRegisteredPlugins());

		Spec::registerPlugin('bar', '\spectrum\tests\testHelpers\emptyStubs\core\plugins\Plugin', 'firstAccess');
		$this->assertSame(array(
			'foo' => array('class' => '\spectrum\core\plugins\Plugin', 'activateMoment' => 'specConstruct'),
			'bar' => array('class' => '\spectrum\tests\testHelpers\emptyStubs\core\plugins\Plugin', 'activateMoment' => 'firstAccess'),
		), Spec::getAllRegisteredPlugins());

		Spec::registerPlugin('baz', '\spectrum\core\plugins\basePlugins\Matchers', 'everyAccess');
		$this->assertSame(array(
			'foo' => array('class' => '\spectrum\core\plugins\Plugin', 'activateMoment' => 'specConstruct'),
			'bar' => array('class' => '\spectrum\tests\testHelpers\emptyStubs\core\plugins\Plugin', 'activateMoment' => 'firstAccess'),
			'baz' => array('class' => '\spectrum\core\plugins\basePlugins\Matchers', 'activateMoment' => 'everyAccess'),
		), Spec::getAllRegisteredPlugins());
	}

	public function testRegisterPlugin_ShouldBeReplaceExistsPlugin()
	{
		Spec::registerPlugin('foo', '\spectrum\core\plugins\Plugin', 'everyAccess');
		Spec::registerPlugin('foo', '\spectrum\tests\testHelpers\emptyStubs\core\plugins\Plugin', 'specConstruct');

		$this->assertSame(
			array('foo' => array('class' => '\spectrum\tests\testHelpers\emptyStubs\core\plugins\Plugin', 'activateMoment' => 'specConstruct'))
			, Spec::getAllRegisteredPlugins()
		);
	}

	public function testRegisterPlugin_ShouldBeSetStackIndexedClassAndFirstAccessActivatedMomentByDefault()
	{
		Spec::registerPlugin('foo', '\spectrum\tests\testHelpers\emptyStubs\core\plugins\Plugin');

		$this->assertSame(
			array('foo' => array('class' => '\spectrum\tests\testHelpers\emptyStubs\core\plugins\Plugin', 'activateMoment' => 'firstAccess'))
			, Spec::getAllRegisteredPlugins()
		);
	}

	public function testRegisterPlugin_ShouldThrowExceptionIfPluginNotImplementInterface()
	{
		$this->assertThrowsException('\spectrum\core\plugins\Exception', function() {
			Spec::registerPlugin('foo', '\spectrum\tests\testHelpers\emptyStubs\core\plugins\NotPlugin');
		});
	}

	public function testRegisterPlugin_ShouldThrowExceptionIfSetIncorrectActivateMoment()
	{
		$this->assertThrowsException('\spectrum\core\plugins\Exception', function() {
			Spec::registerPlugin('foo', '\spectrum\core\plugins\Plugin', 'foo');
		});
	}

	public function testRegisterPlugin_ShouldAcceptAllowedActivateMoments()
	{
		Spec::registerPlugin('foo', '\spectrum\core\plugins\Plugin', 'specConstruct');
		Spec::registerPlugin('foo', '\spectrum\core\plugins\Plugin', 'firstAccess');
		Spec::registerPlugin('foo', '\spectrum\core\plugins\Plugin', 'everyAccess');
	}

	public function testRegisterPlugin_ShouldBeThrowExceptionIfNotAllowPluginsRegistration()
	{
		config::setAllowSpecPluginsRegistration(false);
		$this->assertThrowsException('\spectrum\core\plugins\Exception', 'Plugins registration deny', function(){
			Spec::registerPlugin('foo', '\spectrum\core\plugins\Plugin');
		});
	}

	public function testRegisterPlugin_ShouldBeThrowExceptionIfPluginExistsAndNotAllowPluginsOverride()
	{
		config::setAllowSpecPluginsOverride(false);
		Spec::registerPlugin('foo', '\spectrum\core\plugins\Plugin');
		$this->assertThrowsException('\spectrum\core\plugins\Exception', 'Plugins override deny', function(){
			Spec::registerPlugin('foo', '\spectrum\core\plugins\Plugin');
		});
	}

/**/

	public function testUnregisterPlugin()
	{
		Spec::registerPlugin('foo', '\spectrum\tests\testHelpers\emptyStubs\core\plugins\Plugin');
		Spec::unregisterPlugin('foo');

		$this->assertFalse(Spec::hasRegisteredPlugin('foo'));
		$this->assertSame(array(), Spec::getAllRegisteredPlugins());
	}

	public function testUnregisterPlugin_ShouldBeThrowExceptionIfNotAllowPluginsOverride()
	{
		config::setAllowSpecPluginsOverride(false);
		$this->assertThrowsException('\spectrum\core\plugins\Exception', 'Plugins override deny', function(){
			Spec::unregisterPlugin('foo');
		});
	}

/**/

	public function testUnregisterAllPlugins_ShouldBeLeaveEmptyArray()
	{
		$this->assertSame(array(), Spec::getAllRegisteredPlugins());
	}

	public function testUnregisterAllPlugins_ShouldBeThrowExceptionIfNotAllowPluginsOverride()
	{
		config::setAllowSpecPluginsOverride(false);
		$this->assertThrowsException('\spectrum\core\plugins\Exception', 'Plugins override deny', function(){
			Spec::unregisterAllPlugins();
		});
	}

/**/

	public function testHasRegisteredPlugin_ShouldBeReturnTrueIfPluginExists()
	{
		Spec::registerPlugin('foo', '\spectrum\tests\testHelpers\emptyStubs\core\plugins\Plugin');
		$this->assertTrue(Spec::hasRegisteredPlugin('foo'));
	}

	public function testHasRegisteredPlugin_ShouldBeReturnFalseIfPluginNotExists()
	{
		$this->assertFalse(Spec::hasRegisteredPlugin('foo'));
	}

/**/

	public function testGetRegisteredPlugin()
	{
		Spec::registerPlugin('foo', '\spectrum\core\plugins\Plugin', 'everyAccess');

		$this->assertSame(
			array('class' => '\spectrum\core\plugins\Plugin', 'activateMoment' => 'everyAccess')
			, Spec::getRegisteredPlugin('foo')
		);
	}

	public function testGetRegisteredPlugin_ShouldBeThrowExceptionIfPluginWithAccessNameNotExists()
	{
		$this->assertThrowsException('\spectrum\core\plugins\Exception', function() {
			Spec::getRegisteredPlugin('foo');
		});
	}
}