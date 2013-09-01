<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core\specItemIt\errorHandling\catchPhpErrors\enabled\breakOnFirstPhpError;
require_once __DIR__ . '/../../../../../../init.php';

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

	public function testShouldBeAddFalseAndPhpErrorExceptionToResultBufferOnce()
	{
		$it = $this->it;
		$it->setTestCallback(function() use(&$resultBuffer, $it)
		{
			$resultBuffer = $it->getResultBuffer();
			trigger_error('');
		});

		$it->run();

		$results = $resultBuffer->getResults();

		$this->assertEquals(1, count($results));

		$this->assertFalse($results[0]['result']);
		$this->assertTrue($results[0]['details'] instanceof \spectrum\core\ExceptionPhpError);
	}

	public function testShouldBeProvideErrorMessageAndSeverityToErrorException()
	{
		$it = $this->it;
		$it->setTestCallback(function() use(&$resultBuffer, $it)
		{
			$resultBuffer = $it->getResultBuffer();
			trigger_error('Foo is not bar', \E_USER_NOTICE);
		});

		$it->run();

		$results = $resultBuffer->getResults();
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