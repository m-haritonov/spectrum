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

namespace spectrum\core\specItemIt\errorHandling;
require_once dirname(__FILE__) . '/../../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
abstract class Test extends \spectrum\core\specItemIt\Test
{
	/**
	 * @var \spectrum\core\SpecItemIt
	 */
	protected $it;

	protected function setUp()
	{
		parent::setUp();

		$this->it = new \spectrum\core\SpecItemIt();
		$this->it->errorHandling->setCatchExceptions(false);
		$this->it->errorHandling->setCatchPhpErrors(false);
		$this->it->errorHandling->setBreakOnFirstPhpError(false);
		$this->it->errorHandling->setBreakOnFirstMatcherFail(false);
	}

	public function testShouldBeIgnoreAndSuppressBreakException()
	{
		$it = $this->it;
		$it->setTestCallback(function() use(&$runResultsBuffer, $it)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();
			throw new \spectrum\core\ExceptionBreak();
		});

		$it->run();

		$this->assertEquals(array(), $runResultsBuffer->getResults());
	}
}