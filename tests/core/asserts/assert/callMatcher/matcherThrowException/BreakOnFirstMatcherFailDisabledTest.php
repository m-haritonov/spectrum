<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\asserts\assert\callMatcher\matcherThrowException;
use spectrum\core\asserts\Assert;

require_once dirname(__FILE__) . '/../../../../../init.php';

class BreakOnFirstMatcherFailDisabledTest extends \spectrum\core\asserts\assert\callMatcher\Test
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

	public function testShouldNotBeBreakExecution()
	{
		$this->runInTestCallback(function($test, $it) use(&$isExecuted)
		{
			$assert = new Assert(true);
			$assert->bad();
			$isExecuted = true;
		});

		$this->assertTrue($isExecuted);
	}

	public function testShouldBeAddFalseWithDetailsToRunResultsBufferForEachMatcher()
	{
		$this->runInTestCallback(function($test, $it) use(&$runResultsBuffer)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();

			$assert = new Assert(true);
			$assert->bad();
			$assert->bad();

			$assert = new Assert('foo');
			$assert->bad();
		});

		$results = $runResultsBuffer->getResults();

		$this->assertEquals(3, count($results));

		$this->assertFalse($results[0]['result']);
		$this->assertFalse($results[1]['result']);
		$this->assertFalse($results[2]['result']);

		$this->assertTrue($results[0]['details'] instanceof \spectrum\core\asserts\MatcherCallDetails);
		$this->assertTrue($results[1]['details'] instanceof \spectrum\core\asserts\MatcherCallDetails);
		$this->assertTrue($results[2]['details'] instanceof \spectrum\core\asserts\MatcherCallDetails);

		$this->assertAllResultsDetailsDifferent($results);
	}

	public function testShouldBeProvidePropertiesToDetailsForEachMatcher()
	{
		$this->runInTestCallback(function($test, $it) use(&$runResultsBuffer)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();

			$assert = new Assert(true);
			$assert->bad();
			$assert->badToo(0, 'bar');

			$assert = new Assert('foo');
			$assert->badToo('bar');
		});

		$results = $runResultsBuffer->getResults();

		$details = $results[0]['details'];
		$this->assertSame(true, $details->getActualValue());
		$this->assertSame(false, $details->getIsNot());
		$this->assertSame('bad', $details->getMatcherName());
		$this->assertSame(array(), $details->getMatcherArgs());
		$this->assertSame(null, $details->getMatcherReturnValue());
		$this->assertTrue($details->getException() instanceof \Exception);
		$this->assertSame('I am bad matcher', $details->getException()->getMessage());

		$details = $results[1]['details'];
		$this->assertSame(true, $details->getActualValue());
		$this->assertSame(false, $details->getIsNot());
		$this->assertSame('badToo', $details->getMatcherName());
		$this->assertSame(array(0, 'bar'), $details->getMatcherArgs());
		$this->assertSame(null, $details->getMatcherReturnValue());
		$this->assertTrue($details->getException() instanceof \Exception);
		$this->assertSame('I am bad matcher too', $details->getException()->getMessage());

		$details = $results[2]['details'];
		$this->assertSame('foo', $details->getActualValue());
		$this->assertSame(false, $details->getIsNot());
		$this->assertSame('badToo', $details->getMatcherName());
		$this->assertSame(array('bar'), $details->getMatcherArgs());
		$this->assertSame(null, $details->getMatcherReturnValue());
		$this->assertTrue($details->getException() instanceof \Exception);
		$this->assertSame('I am bad matcher too', $details->getException()->getMessage());
	}

	public function testShouldBeReturnCurrentAssertObject()
	{
		$this->runInTestCallback(function($test, $it) use(&$isCalled)
		{
			$assert = new Assert(true);
			$test->assertSame($assert, $assert->bad());
			$test->assertSame($assert, $assert->bad()->bad());
			$test->assertSame($assert, $assert->bad()->true()->bad());

			$isCalled = true;
		});

		$this->assertTrue($isCalled);
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

	public function testWithNot_ShouldNotBeBreakExecution()
	{
		$this->runInTestCallback(function($test, $it) use(&$isExecuted)
		{
			$assert = new Assert(true);
			$assert->not->bad();
			$isExecuted = true;
		});

		$this->assertTrue($isExecuted);
	}

	public function testWithNot_ShouldBeIgnoreNotAndAddToRunResultsBufferFalseAnyway()
	{
		$this->runInTestCallback(function($test, $it) use(&$runResultsBuffer)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();
			$assert = new Assert(true);
			$assert->not->bad();
		});

		$results = $runResultsBuffer->getResults();
		$this->assertEquals(1, count($results));
		$this->assertSame(false, $results[0]['result']);
		$this->assertTrue($results[0]['details'] instanceof \spectrum\core\asserts\MatcherCallDetails);
	}

	public function testWithNot_ShouldBeAddFalseWithDetailsToRunResultsBufferForEachMatcher()
	{
		$this->runInTestCallback(function($test, $it) use(&$runResultsBuffer)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();

			$assert = new Assert(true);
			$assert->not->bad();
			$assert->not->bad();

			$assert = new Assert('foo');
			$assert->not->bad();
		});

		$results = $runResultsBuffer->getResults();

		$this->assertEquals(3, count($results));

		$this->assertFalse($results[0]['result']);
		$this->assertFalse($results[1]['result']);
		$this->assertFalse($results[2]['result']);

		$this->assertTrue($results[0]['details'] instanceof \spectrum\core\asserts\MatcherCallDetails);
		$this->assertTrue($results[1]['details'] instanceof \spectrum\core\asserts\MatcherCallDetails);
		$this->assertTrue($results[2]['details'] instanceof \spectrum\core\asserts\MatcherCallDetails);

		$this->assertAllResultsDetailsDifferent($results);
	}

	public function testWithNot_ShouldBeProvidePropertiesToDetailsForEachMatcher()
	{
		$this->runInTestCallback(function($test, $it) use(&$runResultsBuffer)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();

			$assert = new Assert(true);
			$assert->not->bad();
			$assert->not->badToo(0, 'bar');

			$assert = new Assert('foo');
			$assert->not->badToo('bar');
		});

		$results = $runResultsBuffer->getResults();

		$details = $results[0]['details'];
		$this->assertSame(true, $details->getActualValue());
		$this->assertSame(true, $details->getIsNot());
		$this->assertSame('bad', $details->getMatcherName());
		$this->assertSame(array(), $details->getMatcherArgs());
		$this->assertSame(null, $details->getMatcherReturnValue());
		$this->assertTrue($details->getException() instanceof \Exception);
		$this->assertSame('I am bad matcher', $details->getException()->getMessage());

		$details = $results[1]['details'];
		$this->assertSame(true, $details->getActualValue());
		$this->assertSame(true, $details->getIsNot());
		$this->assertSame('badToo', $details->getMatcherName());
		$this->assertSame(array(0, 'bar'), $details->getMatcherArgs());
		$this->assertSame(null, $details->getMatcherReturnValue());
		$this->assertTrue($details->getException() instanceof \Exception);
		$this->assertSame('I am bad matcher too', $details->getException()->getMessage());

		$details = $results[2]['details'];
		$this->assertSame('foo', $details->getActualValue());
		$this->assertSame(true, $details->getIsNot());
		$this->assertSame('badToo', $details->getMatcherName());
		$this->assertSame(array('bar'), $details->getMatcherArgs());
		$this->assertSame(null, $details->getMatcherReturnValue());
		$this->assertTrue($details->getException() instanceof \Exception);
		$this->assertSame('I am bad matcher too', $details->getException()->getMessage());
	}

	public function testWithNot_ShouldBeResetNotAfterCall()
	{
		$this->runInTestCallback(function($test, $it) use(&$isCalled)
		{
			$assert = new Assert(true);

			$assert->not->bad();
			$test->assertFalse($assert->isNot());

			$assert->bad();
			$test->assertFalse($assert->isNot());

			$isCalled = true;
		});

		$this->assertTrue($isCalled);
	}

	public function testWithNot_ShouldBeReturnCurrentAssertObject()
	{
		$this->runInTestCallback(function($test, $it) use(&$isCalled)
		{
			$assert = new Assert(true);
			$test->assertSame($assert, $assert->not->bad());
			$test->assertSame($assert, $assert->not->bad()->not->bad());
			$test->assertSame($assert, $assert->not->bad()->not->true()->not->bad());

			$isCalled = true;
		});

		$this->assertTrue($isCalled);
	}

/*** Test ware ***/

	protected function createItWithMatchers()
	{
		$it = parent::createItWithMatchers();
		$it->errorHandling->setBreakOnFirstMatcherFail(false);
		return $it;
	}
}