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

namespace spectrum\core\specItemIt;
use spectrum\core\SpecItemIt;

require_once dirname(__FILE__) . '/../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class IsRunningTest extends Test
{
	public function testShouldBeReturnTrueDuringRunning()
	{
		$isRunning = null;
		$it = new SpecItemIt();
		$it->setTestCallback(function() use($it, &$isRunning) { $isRunning = $it->isRunning(); });

		$it->run();

		$this->assertTrue($isRunning);
	}

	public function testShouldBeReturnFalseIfSpecNotRunning()
	{
		$it = new SpecItemIt();
		$this->assertFalse($it->isRunning());
	}

	public function testShouldBeReturnFalseAfterRun()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function(){});

		$it->run();

		$this->assertFalse($it->isRunning());
	}
}