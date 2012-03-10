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

namespace net\mkharitonov\spectrum\reports;
require_once dirname(__FILE__) . '/../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
abstract class Test extends \net\mkharitonov\spectrum\Test
{
	protected function setUp()
	{
		parent::setUp();
		\net\mkharitonov\spectrum\core\plugins\Manager::registerPlugin('reports', '\net\mkharitonov\spectrum\reports\Plugin', 'whenCallOnce');
	}

	protected function tearDown()
	{
		parent::tearDown();
		\net\mkharitonov\spectrum\core\plugins\Manager::unregisterPlugin('reports');
	}
}