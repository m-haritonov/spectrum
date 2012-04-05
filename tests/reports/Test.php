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

namespace spectrum\reports;
require_once dirname(__FILE__) . '/../init.php';

abstract class Test extends \spectrum\Test
{
	protected function setUp()
	{
		parent::setUp();
		\spectrum\core\plugins\Manager::registerPlugin('reports', '\spectrum\reports\Plugin', 'whenCallOnce');
	}

	protected function tearDown()
	{
		parent::tearDown();
		\spectrum\core\plugins\Manager::unregisterPlugin('reports');
	}
}