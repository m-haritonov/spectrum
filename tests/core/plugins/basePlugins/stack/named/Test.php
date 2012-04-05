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

namespace spectrum\core\plugins\basePlugins\stack\named;
use spectrum\core\plugins\basePlugins\stack\Named;

require_once dirname(__FILE__) . '/../../../../../init.php';

/**
 * @see IndexedTest
 */
abstract class Test extends \spectrum\core\plugins\basePlugins\stack\Test
{
	protected function setUp()
	{
		parent::setUp();
		\spectrum\core\plugins\Manager::registerPlugin('testPlugin', '\spectrum\core\plugins\basePlugins\stack\Named');
	}

	protected function tearDown()
	{
		\spectrum\core\plugins\Manager::unregisterPlugin('testPlugin');
		parent::tearDown();
	}
}