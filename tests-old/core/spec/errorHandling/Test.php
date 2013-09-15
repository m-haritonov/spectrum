<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core\specItemIt\errorHandling;
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
		$it->setTestCallback(function() use(&$resultBuffer, $it)
		{
			$resultBuffer = $it->getResultBuffer();
			throw new \spectrum\core\BreakException();
		});

		$it->run();

		$this->assertEquals(array(), $resultBuffer->getResults());
	}
}