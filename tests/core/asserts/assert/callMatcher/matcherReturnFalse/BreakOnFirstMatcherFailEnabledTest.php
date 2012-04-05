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

class BreakOnFirstMatcherFailEnabledTest extends \spectrum\core\asserts\assert\callMatcher\Test
{
	public function testShouldBeBreakExecution()
	{
		$this->runInTestCallback(function($test, $it) use(&$isCalled)
		{
			$isCalled = true;

			$assert = new Assert(true);
			$assert->false();

			$test->fail('Should be break');
		});

		$this->assertTrue($isCalled);
	}

	public function testShouldBeAddFalseWithDetailsToRunResultsBufferOnce()
	{
		$this->runInTestCallback(function($test, $it) use(&$runResultsBuffer)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();

			$assert = new Assert(true);
			$assert->false();
			$assert->false();

			$test->fail('Should be break');
		});

		$results = $runResultsBuffer->getResults();

		$this->assertEquals(1, count($results));
		$this->assertFalse($results[0]['result']);
		$this->assertTrue($results[0]['details'] instanceof \spectrum\core\asserts\MatcherCallDetails);
	}

	public function testShouldBeProvidePropertiesToDetailsOnce()
	{
		$this->runInTestCallback(function($test, $it) use(&$runResultsBuffer)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();

			$assert = new Assert('foo');
			$assert->eq('bar');

			$test->fail('Should be break');
		});

		$results = $runResultsBuffer->getResults();

		$details = $results[0]['details'];
		$this->assertTrue($details instanceof \spectrum\core\asserts\MatcherCallDetails);
		$this->assertSame('foo', $details->getActualValue());
		$this->assertSame(false, $details->getIsNot());
		$this->assertSame('eq', $details->getMatcherName());
		$this->assertSame(array('bar'), $details->getMatcherArgs());
		$this->assertSame(false, $details->getMatcherReturnValue());
		$this->assertSame(null, $details->getException());
	}

/**/

	public function testWithNot_ShouldBeBreakExecution()
	{
		$this->runInTestCallback(function($test, $it) use(&$isCalled)
		{
			$isCalled = true;

			$assert = new Assert(true);
			$assert->not->true();

			$test->fail('Should be break');
		});

		$this->assertTrue($isCalled);
	}

	public function testWithNot_ShouldBeAddFalseWithDetailsToRunResultsBufferOnce()
	{
		$this->runInTestCallback(function($test, $it) use(&$runResultsBuffer)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();

			$assert = new Assert(true);
			$assert->not->true();
			$assert->not->true();

			$test->fail('Should be break');
		});

		$results = $runResultsBuffer->getResults();

		$this->assertEquals(1, count($results));
		$this->assertFalse($results[0]['result']);
		$this->assertTrue($results[0]['details'] instanceof \spectrum\core\asserts\MatcherCallDetails);
	}

	public function testWithNot_ShouldBeProvidePropertiesToDetailsOnce()
	{
		$this->runInTestCallback(function($test, $it) use(&$runResultsBuffer)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();

			$assert = new Assert('foo');
			$assert->not->eq('foo');

			$test->fail('Should be break');
		});

		$results = $runResultsBuffer->getResults();

		$details = $results[0]['details'];
		$this->assertTrue($details instanceof \spectrum\core\asserts\MatcherCallDetails);
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

/*** Test ware ***/

	protected function createItWithMatchers()
	{
		$it = parent::createItWithMatchers();
		$it->errorHandling->setBreakOnFirstMatcherFail(true);
		return $it;
	}
}