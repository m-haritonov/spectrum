<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\reports;
require_once dirname(__FILE__) . '/../init.php';

abstract class Test extends \spectrum\Test
{
	protected function setUp()
	{
		parent::setUp();
		\spectrum\core\plugins\Manager::registerPlugin('reports', '\spectrum\reports\Plugin', 'whenFirstAccess');
	}

	protected function tearDown()
	{
		parent::tearDown();
		\spectrum\core\plugins\Manager::unregisterPlugin('reports');
	}
}