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