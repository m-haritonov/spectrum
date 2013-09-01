<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core\plugins\basePlugins\stack\named;
use spectrum\core\plugins\basePlugins\stack\Named;

require_once __DIR__ . '/../../../../../init.php';

/**
 * @see IndexedTest
 */
abstract class Test extends \spectrum\core\plugins\basePlugins\stack\Test
{
	protected function setUp()
	{
		parent::setUp();
		\spectrum\core\plugins\manager::registerPlugin('testPlugin', '\spectrum\core\plugins\basePlugins\stack\Named');
	}

	protected function tearDown()
	{
		\spectrum\core\plugins\manager::unregisterPlugin('testPlugin');
		parent::tearDown();
	}
}