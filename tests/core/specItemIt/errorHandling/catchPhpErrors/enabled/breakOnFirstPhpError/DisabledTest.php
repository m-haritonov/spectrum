<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\specItemIt\errorHandling\catchPhpErrors\enabled\breakOnFirstPhpError;
require_once dirname(__FILE__) . '/../../../../../../init.php';

class DisabledTest extends Test
{
	protected function setUp()
	{
		parent::setUp();
		$this->it->errorHandling->setBreakOnFirstPhpError(false);
	}

	public function testShouldNotBeBreakExecution()
	{
		$it = $this->it;
		$it->setTestCallback(function() use(&$isExecuted)
		{
			trigger_error('');
			$isExecuted = true;
		});

		$it->run();

		$this->assertTrue($isExecuted);
	}

	public function testShouldBeAddFalseAndPhpErrorExceptionToRunResultsBufferForEachError()
	{
		$it = $this->it;
		$it->setTestCallback(function() use(&$runResultsBuffer, $it)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();
			trigger_error('');
			trigger_error('');
			trigger_error('');
		});

		$it->run();

		$results = $runResultsBuffer->getResults();

		$this->assertEquals(3, count($results));

		$this->assertFalse($results[0]['result']);
		$this->assertTrue($results[0]['details'] instanceof \spectrum\core\ExceptionPhpError);

		$this->assertFalse($results[1]['result']);
		$this->assertTrue($results[1]['details'] instanceof \spectrum\core\ExceptionPhpError);

		$this->assertFalse($results[2]['result']);
		$this->assertTrue($results[2]['details'] instanceof \spectrum\core\ExceptionPhpError);

		$this->assertNotSame($results[0]['details'], $results[1]['details']);
		$this->assertNotSame($results[0]['details'], $results[2]['details']);

		$this->assertNotSame($results[1]['details'], $results[0]['details']);
		$this->assertNotSame($results[1]['details'], $results[2]['details']);

		$this->assertNotSame($results[2]['details'], $results[0]['details']);
		$this->assertNotSame($results[2]['details'], $results[1]['details']);
	}

	public function testShouldBeProvideErrorMessageAndSeverityToErrorExceptionForEachError()
	{
		$it = $this->it;
		$it->setTestCallback(function() use(&$runResultsBuffer, $it)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();
			trigger_error('Foo is not bar', \E_USER_NOTICE);
			trigger_error('bar', \E_USER_WARNING);
			trigger_error('baz', \E_USER_ERROR);
		});

		$it->run();
		$results = $runResultsBuffer->getResults();

		$this->assertEquals('Foo is not bar', $results[0]['details']->getMessage());
		$this->assertEquals(0, $results[0]['details']->getCode());
		$this->assertEquals(\E_USER_NOTICE, $results[0]['details']->getSeverity());

		$this->assertEquals('bar', $results[1]['details']->getMessage());
		$this->assertEquals(0, $results[1]['details']->getCode());
		$this->assertEquals(\E_USER_WARNING, $results[1]['details']->getSeverity());

		$this->assertEquals('baz', $results[2]['details']->getMessage());
		$this->assertEquals(0, $results[2]['details']->getCode());
		$this->assertEquals(\E_USER_ERROR, $results[2]['details']->getSeverity());
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