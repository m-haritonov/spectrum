<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\specItemIt\errorHandling;
require_once __DIR__ . '/../../../init.php';

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