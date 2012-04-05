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

namespace spectrum\core\specItemIt\errorHandling\catchExceptions;
require_once dirname(__FILE__) . '/../../../../init.php';

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
			$it->getRunResultsBuffer()->addResult(true);
			throw new \Exception();
		});

		$this->assertFalse($it->run());
	}

	public function testShouldBeAddFalseAndThrownExceptionToRunResultsBufferOnce()
	{
		$it = $this->it;
		$it->setTestCallback(function() use(&$runResultsBuffer, $it)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();
			throw new \Exception('Foo is not bar', 123);
		});

		$it->run();

		$results = $runResultsBuffer->getResults();

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
		$it->setTestCallback(function() use(&$runResultsBuffer, $it)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();
			throw new \Exception('foo');
		});

		$it->run();

		$results = $runResultsBuffer->getResults();
		$this->assertEquals('foo', $results[0]['details']->getMessage());
	}

	public function testShouldBeCatchSubclassExceptions()
	{
		$it = $this->it;
		$it->setTestCallback(function() use(&$runResultsBuffer, $it)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();
			throw new \spectrum\core\Exception('foo');
		});

		$it->run();

		$results = $runResultsBuffer->getResults();
		$this->assertEquals('foo', $results[0]['details']->getMessage());
	}

	public function testShouldBeCatchPhpErrorException()
	{
		$it = $this->it;
		$it->setTestCallback(function() use(&$runResultsBuffer, $it)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();
			throw new \spectrum\core\ExceptionPhpError('foo');
		});

		$it->run();

		$results = $runResultsBuffer->getResults();
		$this->assertEquals('foo', $results[0]['details']->getMessage());
	}
}