<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core\plugins;
require_once __DIR__ . '/../../init.php';

use spectrum\config;
use spectrum\core\Spec;

class PluginActivationTest extends \spectrum\tests\core\Test
{
	public function setUp()
	{
		parent::setUp();
		Spec::unregisterAllPlugins();
	}

	public function testCreatePluginInstance_ShouldBeReturnRespectivePluginInstance()
	{
		Spec::registerPlugin('foo', '\spectrum\core\plugins\Plugin');
		Spec::registerPlugin('bar', '\spectrum\tests\testHelpers\emptyStubs\core\plugins\Plugin');

		$this->assertEquals('\spectrum\core\plugins\Plugin', '\\' . get_class(Spec::createPluginInstance(new Spec(), 'foo')));
		$this->assertEquals('\spectrum\tests\testHelpers\emptyStubs\core\plugins\Plugin', '\\' . get_class(Spec::createPluginInstance(new Spec(), 'bar')));
	}

	public function testCreatePluginInstance_ShouldBeSetAccessNameAndOwnerToPluginInstance()
	{
		Spec::registerPlugin('foo', '\spectrum\core\plugins\Plugin');

		$spec = new Spec();
		$plugin = Spec::createPluginInstance($spec, 'foo');

		$this->assertEquals('foo', $plugin->getAccessName());
		$this->assertSame($spec, $plugin->getOwnerSpec());
	}

	public function testCreatePluginInstance_ShouldBeReturnNewInstanceAlways()
	{
		Spec::registerPlugin('foo', '\spectrum\core\plugins\Plugin');
		Spec::registerPlugin('bar', '\spectrum\core\plugins\Plugin');

		$spec = new Spec();
		$this->assertNotSame(
			Spec::createPluginInstance($spec, 'foo'),
			Spec::createPluginInstance($spec, 'bar')
		);
	}

	public function testCreatePluginInstance_ShouldBeThrowExceptionIfPluginWithAccessNameNotExists()
	{
		$this->assertThrowsException('\spectrum\core\plugins\Exception', function() {
			Spec::createPluginInstance(new Spec(), 'foo');
		});
	}
}