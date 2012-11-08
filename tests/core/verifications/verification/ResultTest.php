<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\verifications\verification;
use spectrum\core\verifications\Verification;
use spectrum\core\verifications\CallDetails;

require_once __DIR__ . '/../../../init.php';

class ResultTest extends Test
{
	public function testResultIsTrue_BreakOnFirstMatcherFailDisabled_ShouldNotBeBreakExecution()
	{
		$it = $this->createSpecIt();
		$it->errorHandling->setBreakOnFirstMatcherFail(false);
		$this->runInTestCallback($it, function($test, $it) use(&$isExecuted)
		{
			new Verification('aaa', '==', 'aaa');
			$isExecuted = true;
		});

		$this->assertTrue($isExecuted);
	}
	
	public function testResultIsTrue_BreakOnFirstMatcherFailDisabled_ShouldBeAddTrueWithCallDetailsToRunResultsBufferForEachVerification()
	{
		$it = $this->createSpecIt();
		$it->errorHandling->setBreakOnFirstMatcherFail(false);
		$this->runInTestCallback($it, function($test, $it) use(&$runResultsBuffer)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();
			new Verification('aaa');
			new Verification('aaa', '==', 'aaa');
			new Verification('aaa', '!=', 'bbb');
		});
		
		$results = $runResultsBuffer->getResults();

		$this->assertEquals(3, count($results));

		$this->assertTrue($results[0]['result']);
		$this->assertTrue($results[1]['result']);
		$this->assertTrue($results[2]['result']);

		$this->assertTrue($results[0]['details'] instanceof \spectrum\core\verifications\CallDetails);
		$this->assertTrue($results[1]['details'] instanceof \spectrum\core\verifications\CallDetails);
		$this->assertTrue($results[2]['details'] instanceof \spectrum\core\verifications\CallDetails);

		$this->assertAllResultsDetailsDifferent($results);
		
		$details = $results[0]['details'];
		$this->assertSame('aaa', $details->getValue1());
		$this->assertSame('', $details->getValue1SourceCode());
		$this->assertSame(null, $details->getOperator());
		$this->assertSame(null, $details->getValue2());
		$this->assertSame('', $details->getValue2SourceCode());
		$this->assertSame(true, $details->getResult());
		$this->assertSame('verify', $details->getVerifyFunctionName());

		$details = $results[1]['details'];
		$this->assertSame('aaa', $details->getValue1());
		$this->assertSame('', $details->getValue1SourceCode());
		$this->assertSame('==', $details->getOperator());
		$this->assertSame('aaa', $details->getValue2());
		$this->assertSame('', $details->getValue2SourceCode());
		$this->assertSame(true, $details->getResult());
		$this->assertSame('verify', $details->getVerifyFunctionName());

		$details = $results[2]['details'];
		$this->assertSame('aaa', $details->getValue1());
		$this->assertSame('', $details->getValue1SourceCode());
		$this->assertSame('!=', $details->getOperator());
		$this->assertSame('bbb', $details->getValue2());
		$this->assertSame('', $details->getValue2SourceCode());
		$this->assertSame(true, $details->getResult());
		$this->assertSame('verify', $details->getVerifyFunctionName());
	}

/**/
	
	public function testResultIsTrue_BreakOnFirstMatcherFailEnabled_ShouldNotBeBreakExecution()
	{
		$it = $this->createSpecIt();
		$it->errorHandling->setBreakOnFirstMatcherFail(true);
		$this->runInTestCallback($it, function($test, $it) use(&$isExecuted)
		{
			new Verification('aaa', '==', 'aaa');
			$isExecuted = true;
		});

		$this->assertTrue($isExecuted);
	}
	
	public function testResultIsTrue_BreakOnFirstMatcherFailEnabled_ShouldBeAddTrueWithCallDetailsToRunResultsBufferForEachVerification()
	{
		$it = $this->createSpecIt();
		$it->errorHandling->setBreakOnFirstMatcherFail(true);
		$this->runInTestCallback($it, function($test, $it) use(&$runResultsBuffer)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();
			new Verification('aaa');
			new Verification('aaa', '==', 'aaa');
			new Verification('aaa', '!=', 'bbb');
		});
		
		$results = $runResultsBuffer->getResults();

		$this->assertEquals(3, count($results));

		$this->assertTrue($results[0]['result']);
		$this->assertTrue($results[1]['result']);
		$this->assertTrue($results[2]['result']);

		$this->assertTrue($results[0]['details'] instanceof \spectrum\core\verifications\CallDetails);
		$this->assertTrue($results[1]['details'] instanceof \spectrum\core\verifications\CallDetails);
		$this->assertTrue($results[2]['details'] instanceof \spectrum\core\verifications\CallDetails);

		$this->assertAllResultsDetailsDifferent($results);
		
		$details = $results[0]['details'];
		$this->assertSame('aaa', $details->getValue1());
		$this->assertSame('', $details->getValue1SourceCode());
		$this->assertSame(null, $details->getOperator());
		$this->assertSame(null, $details->getValue2());
		$this->assertSame('', $details->getValue2SourceCode());
		$this->assertSame(true, $details->getResult());
		$this->assertSame('verify', $details->getVerifyFunctionName());

		$details = $results[1]['details'];
		$this->assertSame('aaa', $details->getValue1());
		$this->assertSame('', $details->getValue1SourceCode());
		$this->assertSame('==', $details->getOperator());
		$this->assertSame('aaa', $details->getValue2());
		$this->assertSame('', $details->getValue2SourceCode());
		$this->assertSame(true, $details->getResult());
		$this->assertSame('verify', $details->getVerifyFunctionName());

		$details = $results[2]['details'];
		$this->assertSame('aaa', $details->getValue1());
		$this->assertSame('', $details->getValue1SourceCode());
		$this->assertSame('!=', $details->getOperator());
		$this->assertSame('bbb', $details->getValue2());
		$this->assertSame('', $details->getValue2SourceCode());
		$this->assertSame(true, $details->getResult());
		$this->assertSame('verify', $details->getVerifyFunctionName());
	}
	
/**/

	public function testResultIsFalse_BreakOnFirstMatcherFailDisabled_ShouldNotBeBreakExecution()
	{
		$it = $this->createSpecIt();
		$it->errorHandling->setBreakOnFirstMatcherFail(false);
		$this->runInTestCallback($it, function($test, $it) use(&$isExecuted)
		{
			new Verification('aaa', '==', 'bbb');
			$isExecuted = true;
		});

		$this->assertTrue($isExecuted);
	}
	
	public function testResultIsFalse_BreakOnFirstMatcherFailDisabled_ShouldBeAddFalseWithCallDetailsToRunResultsBufferForEachVerification()
	{
		$it = $this->createSpecIt();
		$it->errorHandling->setBreakOnFirstMatcherFail(false);
		$this->runInTestCallback($it, function($test, $it) use(&$runResultsBuffer)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();
			new Verification(0);
			new Verification('aaa', '!=', 'aaa');
			new Verification('aaa', '==', 'bbb');
		});
		
		$results = $runResultsBuffer->getResults();

		$this->assertEquals(3, count($results));

		$this->assertFalse($results[0]['result']);
		$this->assertFalse($results[1]['result']);
		$this->assertFalse($results[2]['result']);

		$this->assertTrue($results[0]['details'] instanceof \spectrum\core\verifications\CallDetails);
		$this->assertTrue($results[1]['details'] instanceof \spectrum\core\verifications\CallDetails);
		$this->assertTrue($results[2]['details'] instanceof \spectrum\core\verifications\CallDetails);

		$this->assertAllResultsDetailsDifferent($results);
		
		$details = $results[0]['details'];
		$this->assertSame(0, $details->getValue1());
		$this->assertSame('', $details->getValue1SourceCode());
		$this->assertSame(null, $details->getOperator());
		$this->assertSame(null, $details->getValue2());
		$this->assertSame('', $details->getValue2SourceCode());
		$this->assertSame(false, $details->getResult());
		$this->assertSame('verify', $details->getVerifyFunctionName());

		$details = $results[1]['details'];
		$this->assertSame('aaa', $details->getValue1());
		$this->assertSame('', $details->getValue1SourceCode());
		$this->assertSame('!=', $details->getOperator());
		$this->assertSame('aaa', $details->getValue2());
		$this->assertSame('', $details->getValue2SourceCode());
		$this->assertSame(false, $details->getResult());
		$this->assertSame('verify', $details->getVerifyFunctionName());

		$details = $results[2]['details'];
		$this->assertSame('aaa', $details->getValue1());
		$this->assertSame('', $details->getValue1SourceCode());
		$this->assertSame('==', $details->getOperator());
		$this->assertSame('bbb', $details->getValue2());
		$this->assertSame('', $details->getValue2SourceCode());
		$this->assertSame(false, $details->getResult());
		$this->assertSame('verify', $details->getVerifyFunctionName());
	}
	
/**/

	public function testResultIsFalse_BreakOnFirstMatcherFailEnabled_ShouldBeBreakExecution()
	{
		$it = $this->createSpecIt();
		$it->errorHandling->setBreakOnFirstMatcherFail(true);
		$this->runInTestCallback($it, function($test, $it) use(&$isCalled)
		{
			$isCalled = true;
			new Verification('aaa', '==', 'bbb');
			$test->fail('This code should not be executed');
		});

		$this->assertTrue($isCalled);
	}
	
	public function testResultIsFalse_BreakOnFirstMatcherFailEnabled_ShouldBeAddFalseWithCallDetailsToRunResultsBuffer()
	{
		$it = $this->createSpecIt();
		$it->errorHandling->setBreakOnFirstMatcherFail(true);
		$this->runInTestCallback($it, function($test, $it) use(&$runResultsBuffer)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();
			new Verification('aaa', '==', 'bbb');
		});
		
		$results = $runResultsBuffer->getResults();

		$this->assertEquals(1, count($results));
		$this->assertFalse($results[0]['result']);
		$this->assertTrue($results[0]['details'] instanceof \spectrum\core\verifications\CallDetails);
		
		$details = $results[0]['details'];
		$this->assertSame('aaa', $details->getValue1());
		$this->assertSame('', $details->getValue1SourceCode());
		$this->assertSame('==', $details->getOperator());
		$this->assertSame('bbb', $details->getValue2());
		$this->assertSame('', $details->getValue2SourceCode());
		$this->assertSame(false, $details->getResult());
		$this->assertSame('verify', $details->getVerifyFunctionName());
	}

/**/
	
	public function testThrowsException_BreakOnFirstMatcherFailDisabled_CatchExceptionsDisabled_ShouldNotBeCatchExceptionAndShouldBeBreakExecution()
	{
		$test = $this;
		$this->assertThrowException('\spectrum\core\verifications\Exception', function() use($test){
			$it = $test->createSpecIt();
			$it->errorHandling->setBreakOnFirstMatcherFail(false);
			$it->errorHandling->setCatchExceptions(false);
			$test->runInTestCallback($it, function($test, $it)
			{
				new Verification('aaa', '=======', 'bbb');
				$test->fail('This code should not be executed');
			});
		});
	}
	
	public function testThrowsException_BreakOnFirstMatcherFailDisabled_CatchExceptionsDisabled_ShouldNotBeAddResultToRunResultsBuffer()
	{
		try
		{
			$it = $this->createSpecIt();
			$it->errorHandling->setBreakOnFirstMatcherFail(false);
			$it->errorHandling->setCatchExceptions(false);
			$this->runInTestCallback($it, function($test, $it) use(&$runResultsBuffer)
			{
				$runResultsBuffer = $it->getRunResultsBuffer();
				new Verification('aaa', '=======', 'bbb');
			});
		}
		catch (\Exception $e)
		{
		}
		
		$this->assertEquals(0, count($runResultsBuffer->getResults()));
	}
	
/**/

	public function testThrowsException_BreakOnFirstMatcherFailDisabled_CatchExceptionsEnabled_ShouldNotBeBreakExecution()
	{
		$it = $this->createSpecIt();
		$it->errorHandling->setBreakOnFirstMatcherFail(false);
		$it->errorHandling->setCatchExceptions(true);
		$this->runInTestCallback($it, function($test, $it) use(&$isExecuted)
		{
			new Verification('aaa', '=======', 'bbb');
			$isExecuted = true;
		});

		$this->assertTrue($isExecuted);
	}
	
	public function testThrowsException_BreakOnFirstMatcherFailDisabled_CatchExceptionsEnabled_ShouldBeAddFalseWithExceptionAsDetailsToRunResultsBuffer()
	{
		$it = $this->createSpecIt();
		$it->errorHandling->setBreakOnFirstMatcherFail(false);
		$it->errorHandling->setCatchExceptions(true);
		$this->runInTestCallback($it, function($test, $it) use(&$runResultsBuffer)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();
			new Verification('aaa', '=======', 'bbb');
		});
		
		$results = $runResultsBuffer->getResults();

		$this->assertEquals(1, count($results));
		$this->assertFalse($results[0]['result']);
		$this->assertTrue($results[0]['details'] instanceof \spectrum\core\verifications\Exception);
	}
				
/**/

	public function testThrowsException_BreakOnFirstMatcherFailEnabled_CatchExceptionsDisabled_ShouldNotBeCatchExceptionAndShouldBeBreakExecution()
	{
		$test = $this;
		$this->assertThrowException('\spectrum\core\verifications\Exception', function() use($test){
			$it = $test->createSpecIt();
			$it->errorHandling->setBreakOnFirstMatcherFail(true);
			$it->errorHandling->setCatchExceptions(false);
			$test->runInTestCallback($it, function($test, $it)
			{
				new Verification('aaa', '=======', 'bbb');
				$test->fail('This code should not be executed');
			});
		});
	}
	
	public function testThrowsException_BreakOnFirstMatcherFailEnabled_CatchExceptionsDisabled_ShouldNotBeAddResultToRunResultsBuffer()
	{
		try
		{
			$it = $this->createSpecIt();
			$it->errorHandling->setBreakOnFirstMatcherFail(true);
			$it->errorHandling->setCatchExceptions(false);
			$this->runInTestCallback($it, function($test, $it) use(&$runResultsBuffer)
			{
				$runResultsBuffer = $it->getRunResultsBuffer();
				new Verification('aaa', '=======', 'bbb');
			});
		}
		catch (\Exception $e)
		{
		}
		
		$this->assertEquals(0, count($runResultsBuffer->getResults()));
	}

/**/

	public function testThrowsException_BreakOnFirstMatcherFailEnabled_CatchExceptionsEnabled_ShouldBeBreakExecution()
	{
		$it = $this->createSpecIt();
		$it->errorHandling->setBreakOnFirstMatcherFail(true);
		$it->errorHandling->setCatchExceptions(true);
		$this->runInTestCallback($it, function($test, $it)
		{
			new Verification('aaa', '=======', 'bbb');
			$test->fail('This code should not be executed');
		});
	}
	
	public function testThrowsException_BreakOnFirstMatcherFailEnabled_CatchExceptionsEnabled_ShouldBeAddFalseWithExceptionAsDetailsToRunResultsBuffer()
	{
		$it = $this->createSpecIt();
		$it->errorHandling->setBreakOnFirstMatcherFail(true);
		$it->errorHandling->setCatchExceptions(true);
		$this->runInTestCallback($it, function($test, $it) use(&$runResultsBuffer)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();
			new Verification('aaa', '=======', 'bbb');
		});
		
		$results = $runResultsBuffer->getResults();

		$this->assertEquals(1, count($results));
		$this->assertFalse($results[0]['result']);
		$this->assertTrue($results[0]['details'] instanceof \spectrum\core\verifications\Exception);
	}
}