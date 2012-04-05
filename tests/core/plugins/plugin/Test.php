<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\plugins\plugin;
require_once dirname(__FILE__) . '/../../../init.php';

abstract class Test extends \spectrum\core\Test
{
	protected function setUp()
	{
		parent::setUp();
		\spectrum\core\plugins\Manager::registerPlugin('testPlugin', '\spectrum\core\testEnv\PluginPublicMock');
	}

	protected function tearDown()
	{
		\spectrum\core\plugins\Manager::unregisterPlugin('testPlugin');
		parent::tearDown();
	}
}