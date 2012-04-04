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

namespace spectrum\core\specItemIt\errorHandling\catchPhpErrors\enabled\breakOnFirstPhpError;
require_once dirname(__FILE__) . '/../../../../../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class EnabledTest extends Test
{
	protected function setUp()
	{
		parent::setUp();
		$this->it->errorHandling->setBreakOnFirstPhpError(true);
	}
	
	public function testShouldBeBreakExecution()
	{
		$it = $this->it;
		$it->setTestCallback(function() use(&$isExecuted)
		{
			trigger_error('');
			$isExecuted = true;
		});

		$it->run();

		$this->assertNull($isExecuted);
	}

	public function testShouldBeAddFalseAndPhpErrorExceptionToRunResultsBufferOnce()
	{
		$it = $this->it;
		$it->setTestCallback(function() use(&$runResultsBuffer, $it)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();
			trigger_error('');
		});

		$it->run();

		$results = $runResultsBuffer->getResults();

		$this->assertEquals(1, count($results));

		$this->assertFalse($results[0]['result']);
		$this->assertTrue($results[0]['details'] instanceof \spectrum\core\ExceptionPhpError);
	}

	public function testShouldBeProvideErrorMessageAndSeverityToErrorException()
	{
		$it = $this->it;
		$it->setTestCallback(function() use(&$runResultsBuffer, $it)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();
			trigger_error('Foo is not bar', \E_USER_NOTICE);
		});

		$it->run();

		$results = $runResultsBuffer->getResults();
		$this->assertEquals('Foo is not bar', $results[0]['details']->getMessage());
		$this->assertEquals(0, $results[0]['details']->getCode());
		$this->assertEquals(\E_USER_NOTICE, $results[0]['details']->getSeverity());
	}

	public function testShouldNotBeCatchPhpErrorException()
	{
		$it = $this->it;
		$it->setTestCallback(function(){
			throw new \spectrum\core\ExceptionPhpError('foo');
		});

		$this->assertThrowException('\spectrum\core\ExceptionPhpError', 'foo', function() use($it){
			$it->run();
		});
	}
}