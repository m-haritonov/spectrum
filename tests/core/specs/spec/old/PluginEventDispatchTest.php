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

class PluginEventDispatchTest extends \spectrum\tests\core\Test
{
	public function setUp()
	{
		parent::setUp();
		Spec::unregisterAllPlugins();
	}

	public function testGetAccessNamesForEventPlugins_ShouldBeReturnAllPluginsWhichImplementsEventInterface()
	{
		Spec::registerPlugin('foo', '\spectrum\tests\testHelpers\emptyStubs\core\plugins\events\OnRunAfter');
		Spec::registerPlugin('bar', '\spectrum\tests\testHelpers\emptyStubs\core\plugins\events\OnRunBefore');
		Spec::registerPlugin('baz', '\spectrum\core\plugins\Plugin');

		$this->assertSame(array('foo'), Spec::getAccessNamesForEventPlugins('OnRunAfter'));
		$this->assertSame(array('bar'), Spec::getAccessNamesForEventPlugins('onRunBefore'));
	}

	public function testGetAccessNamesForEventPlugins_ShouldBeThrowExceptionIfEventNameNotExists()
	{
		$this->assertThrowsException('\spectrum\core\plugins\Exception', '"onFooBar"', function() {
			Spec::getAccessNamesForEventPlugins('onFooBar');
		});
	}
}