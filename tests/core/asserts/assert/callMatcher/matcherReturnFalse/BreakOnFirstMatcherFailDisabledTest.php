<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\asserts\assert\callMatcher\matcherReturnFalse;
use spectrum\core\asserts\Assert;

require_once dirname(__FILE__) . '/../../../../../init.php';

class BreakOnFirstMatcherFailDisabledTest extends \spectrum\core\asserts\assert\callMatcher\Test
{
	public function testShouldNotBeBreakExecution()
	{
		$this->runInTestCallback(function($test, $it) use(&$isExecuted)
		{
			$assert = new Assert(true);
			$assert->false();
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
			$assert->false();
			$assert->false();

			$assert = new Assert(true);
			$assert->false();
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
			$assert->false();
			$assert->returnSecondArg(0, 'bar');

			$assert = new Assert('foo');
			$assert->eq('bar');
		});

		$results = $runResultsBuffer->getResults();

		$details = $results[0]['details'];
		$this->assertSame(true, $details->getActualValue());
		$this->assertSame(false, $details->getIsNot());
		$this->assertSame('false', $details->getMatcherName());
		$this->assertSame(array(), $details->getMatcherArgs());
		$this->assertSame(false, $details->getMatcherReturnValue());
		$this->assertSame(null, $details->getException());

		$details = $results[1]['details'];
		$this->assertSame(true, $details->getActualValue());
		$this->assertSame(false, $details->getIsNot());
		$this->assertSame('returnSecondArg', $details->getMatcherName());
		$this->assertSame(array(0, 'bar'), $details->getMatcherArgs());
		$this->assertSame('bar', $details->getMatcherReturnValue());
		$this->assertSame(null, $details->getException());

		$details = $results[2]['details'];
		$this->assertSame('foo', $details->getActualValue());
		$this->assertSame(false, $details->getIsNot());
		$this->assertSame('eq', $details->getMatcherName());
		$this->assertSame(array('bar'), $details->getMatcherArgs());
		$this->assertSame(false, $details->getMatcherReturnValue());
		$this->assertSame(null, $details->getException());
	}

	public function testShouldBeReturnCurrentAssertObject()
	{
		$this->runInTestCallback(function($test, $it) use(&$isCalled)
		{
			$assert = new Assert(true);
			$test->assertSame($assert, $assert->false());
			$test->assertSame($assert, $assert->false()->false());
			$test->assertSame($assert, $assert->false()->true()->false());

			$isCalled = true;
		});

		$this->assertTrue($isCalled);
	}

/**/

	public function testWithNot_ShouldNotBeBreakExecution()
	{
		$this->runInTestCallback(function($test, $it) use(&$isExecuted)
		{
			$assert = new Assert(true);
			$assert->not->false();
			$isExecuted = true;
		});

		$this->assertTrue($isExecuted);
	}

	public function testWithNot_ShouldBeAddToRunResultsBufferInvertedResult()
	{
		$this->runInTestCallback(function($test, $it) use(&$runResultsBuffer)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();
			$assert = new Assert(true);
			$assert->not->true();
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
			$assert->not->true();
			$assert->not->true();

			$assert = new Assert(true);
			$assert->not->true();
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
			$assert->not->true();
			$assert->not->returnSecondArg(1, 'bar');

			$assert = new Assert('foo');
			$assert->not->eq('foo');
		});

		$results = $runResultsBuffer->getResults();

		$details = $results[0]['details'];
		$this->assertSame(true, $details->getActualValue());
		$this->assertSame(true, $details->getIsNot());
		$this->assertSame('true', $details->getMatcherName());
		$this->assertSame(array(), $details->getMatcherArgs());
		$this->assertSame(true, $details->getMatcherReturnValue());
		$this->assertSame(null, $details->getException());

		$details = $results[1]['details'];
		$this->assertSame(true, $details->getActualValue());
		$this->assertSame(true, $details->getIsNot());
		$this->assertSame('returnSecondArg', $details->getMatcherName());
		$this->assertSame(array(1, 'bar'), $details->getMatcherArgs());
		$this->assertSame('bar', $details->getMatcherReturnValue());
		$this->assertSame(null, $details->getException());

		$details = $results[2]['details'];
		$this->assertSame('foo', $details->getActualValue());
		$this->assertSame(true, $details->getIsNot());
		$this->assertSame('eq', $details->getMatcherName());
		$this->assertSame(array('foo'), $details->getMatcherArgs());
		$this->assertSame(true, $details->getMatcherReturnValue());
		$this->assertSame(null, $details->getException());
	}

	public function testWithNot_ShouldBeProvideNotInvertedMatcherReturnValue()
	{
		$this->runInTestCallback(function($test, $it) use(&$runResultsBuffer)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();

			$assert = new Assert(true);
			$assert->not->true();
		});

		$results = $runResultsBuffer->getResults();
		$this->assertSame(true, $results[0]['details']->getMatcherReturnValue());
	}

	public function testWithNot_ShouldBeResetNotAfterCall()
	{
		$this->runInTestCallback(function($test, $it) use(&$isCalled)
		{
			$assert = new Assert(true);

			$assert->not->true();
			$test->assertFalse($assert->isNot());

			$assert->false();
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
			$test->assertSame($assert, $assert->not->false());
			$test->assertSame($assert, $assert->not->false()->not->false());
			$test->assertSame($assert, $assert->not->false()->not->true()->not->false());

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