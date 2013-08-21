<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core\specItemIt\errorHandling\catchExceptions;
require_once __DIR__ . '/../../../../init.php';

class EnabledTest extends Test
{
	protected function setUp()
	{
		parent::setUp();
		$this->it->errorHandling->setCatchExceptions(true);
	}
	
	public function testShouldBeBreakExecution()
	{
		$it = $this->it;
		$it->setTestCallback(function() use(&$isExecuted)
		{
			throw new \Exception();
			$isExecuted = true;
		});

		$it->run();

		$this->assertNull($isExecuted);
	}

	public function testShouldBeReturnFalse()
	{
		$it = $this->it;
		$it->setTestCallback(function() use($it)
		{
			$it->getResultBuffer()->addResult(true);
			throw new \Exception();
		});

		$this->assertFalse($it->run());
	}

	public function testShouldBeAddFalseAndThrownExceptionToResultBufferOnce()
	{
		$it = $this->it;
		$it->setTestCallback(function() use(&$resultBuffer, $it)
		{
			$resultBuffer = $it->getResultBuffer();
			throw new \Exception('Foo is not bar', 123);
		});

		$it->run();

		$results = $resultBuffer->getResults();

		$this->assertEquals(1, count($results));

		$this->assertFalse($results[0]['result']);
		$this->assertTrue($results[0]['details'] instanceof \Exception);

		$this->assertEquals('Foo is not bar', $results[0]['details']->getMessage());
		$this->assertEquals(123, $results[0]['details']->getCode());
	}

	public function testShouldNotBeThrowExceptionAbove()
	{
		$it = $this->it;
		$it->setTestCallback(function(){
			throw new \Exception('foo');
		});

		$it->run(); // If exception thrown - test will fail
	}
	
	public function testShouldBeCatchBaseClassExceptions()
	{
		$it = $this->it;
		$it->setTestCallback(function() use(&$resultBuffer, $it)
		{
			$resultBuffer = $it->getResultBuffer();
			throw new \Exception('foo');
		});

		$it->run();

		$results = $resultBuffer->getResults();
		$this->assertEquals('foo', $results[0]['details']->getMessage());
	}

	public function testShouldBeCatchSubclassExceptions()
	{
		$it = $this->it;
		$it->setTestCallback(function() use(&$resultBuffer, $it)
		{
			$resultBuffer = $it->getResultBuffer();
			throw new \spectrum\core\Exception('foo');
		});

		$it->run();

		$results = $resultBuffer->getResults();
		$this->assertEquals('foo', $results[0]['details']->getMessage());
	}

	public function testShouldBeCatchPhpErrorException()
	{
		$it = $this->it;
		$it->setTestCallback(function() use(&$resultBuffer, $it)
		{
			$resultBuffer = $it->getResultBuffer();
			throw new \spectrum\core\ExceptionPhpError('foo');
		});

		$it->run();

		$results = $resultBuffer->getResults();
		$this->assertEquals('foo', $results[0]['details']->getMessage());
	}
}