<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core\asserts\assert\callMatcher\matcherThrowException;
use spectrum\core\Assert;

require_once __DIR__ . '/../../../../../init.php';

class BreakOnFirstMatcherFailEnabledTest extends \spectrum\core\assert\callMatcher\Test
{
	public function testCatchExceptionsDisabled_ShouldNotBeCatchExceptions()
	{
		$this->runInTestCallback(function($test, $it) use(&$isCalled)
		{
			$it->errorHandling->setCatchExceptions(false);
			$test->assertThrowException('\Exception', 'I am bad matcher', function()
			{
				$assert = new Assert(true);
				$assert->bad();
			});

			$isCalled = true;
		});

		$this->assertTrue($isCalled);
	}

	public function testShouldBeBreakExecution()
	{
		$this->runInTestCallback(function($test, $it) use(&$isCalled)
		{
			$isCalled = true;

			$assert = new Assert(true);
			$assert->bad();

			$test->fail('Should be break');
		});

		$this->assertTrue($isCalled);
	}

	public function testShouldBeAddFalseWithDetailsToResultBufferOnce()
	{
		$this->runInTestCallback(function($test, $it) use(&$resultBuffer)
		{
			$resultBuffer = $it->getResultBuffer();

			$assert = new Assert(true);
			$assert->bad();
			$assert->bad();

			$test->fail('Should be break');
		});

		$results = $resultBuffer->getResults();

		$this->assertEquals(1, count($results));
		$this->assertFalse($results[0]['result']);
		$this->assertTrue($results[0]['details'] instanceof \spectrum\core\MatcherCallDetails);
	}

	public function testShouldBeProvidePropertiesToDetailsOnce()
	{
		$this->runInTestCallback(function($test, $it) use(&$resultBuffer)
		{
			$resultBuffer = $it->getResultBuffer();

			$assert = new Assert('foo');
			$assert->bad(0, 'bar');

			$test->fail('Should be break');
		});

		$results = $resultBuffer->getResults();

		$details = $results[0]['details'];
		$this->assertTrue($details instanceof \spectrum\core\MatcherCallDetails);
		$this->assertSame('foo', $details->getActualValue());
		$this->assertSame(false, $details->getNot());
		$this->assertSame('bad', $details->getMatcherName());
		$this->assertSame(array(0, 'bar'), $details->getMatcherArgs());
		$this->assertSame(null, $details->getMatcherReturnValue());
		$this->assertTrue($details->getException() instanceof \Exception);
		$this->assertSame('I am bad matcher', $details->getException()->getMessage());
	}

/**/

	public function testWithNot_CatchExceptionsDisabled_ShouldNotBeCatchExceptions()
	{
		$this->runInTestCallback(function($test, $it) use(&$isCalled)
		{
			$it->errorHandling->setCatchExceptions(false);
			$test->assertThrowException('\Exception', 'I am bad matcher', function()
			{
				$assert = new Assert(true);
				$assert->not->bad();
			});

			$isCalled = true;
		});

		$this->assertTrue($isCalled);
	}

	public function testWithNot_ShouldBeBreakExecution()
	{
		$this->runInTestCallback(function($test, $it) use(&$isCalled)
		{
			$isCalled = true;

			$assert = new Assert(true);
			$assert->not->bad();

			$test->fail('Should be break');
		});

		$this->assertTrue($isCalled);
	}

	public function testWithNot_ShouldBeAddTrueWithDetailsToResultBuffer()
	{
		$this->runInTestCallback(function($test, $it) use(&$resultBuffer)
		{
			$resultBuffer = $it->getResultBuffer();

			$assert = new Assert(true);
			$assert->not->bad();
			$assert->bad();

			$test->fail('Should be break');
		});

		$results = $resultBuffer->getResults();

		$this->assertEquals(2, count($results));
		$this->assertTrue($results[0]['result']);
		$this->assertTrue($results[0]['details'] instanceof \spectrum\core\MatcherCallDetails);
	}

	public function testWithNot_ShouldBeProvidePropertiesToDetailsOnce()
	{
		$this->runInTestCallback(function($test, $it) use(&$resultBuffer)
		{
			$resultBuffer = $it->getResultBuffer();

			$assert = new Assert('foo');
			$assert->not->bad(0, 'bar');

			$test->fail('Should be break');
		});

		$results = $resultBuffer->getResults();

		$details = $results[0]['details'];
		$this->assertTrue($details instanceof \spectrum\core\MatcherCallDetails);
		$this->assertSame('foo', $details->getActualValue());
		$this->assertSame(true, $details->getNot());
		$this->assertSame('bad', $details->getMatcherName());
		$this->assertSame(array(0, 'bar'), $details->getMatcherArgs());
		$this->assertSame(null, $details->getMatcherReturnValue());
		$this->assertTrue($details->getException() instanceof \Exception);
		$this->assertSame('I am bad matcher', $details->getException()->getMessage());
	}

/*** Test ware ***/

	protected function createItWithMatchers()
	{
		$it = parent::createItWithMatchers();
		$it->errorHandling->setBreakOnFirstMatcherFail(true);
		return $it;
	}
}