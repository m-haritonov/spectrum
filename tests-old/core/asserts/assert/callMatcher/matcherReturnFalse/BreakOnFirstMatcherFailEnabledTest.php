<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core\asserts\assert\callMatcher\matcherReturnFalse;
use spectrum\core\Assert;

require_once __DIR__ . '/../../../../../init.php';

class BreakOnFirstMatcherFailEnabledTest extends \spectrum\core\assert\callMatcher\Test
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

	public function testShouldBeAddFalseWithDetailsToResultBufferOnce()
	{
		$this->runInTestCallback(function($test, $it) use(&$resultBuffer)
		{
			$resultBuffer = $it->getResultBuffer();

			$assert = new Assert(true);
			$assert->false();
			$assert->false();

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
			$assert->eq('bar');

			$test->fail('Should be break');
		});

		$results = $resultBuffer->getResults();

		$details = $results[0]['details'];
		$this->assertTrue($details instanceof \spectrum\core\MatcherCallDetails);
		$this->assertSame('foo', $details->getActualValue());
		$this->assertSame(false, $details->getNot());
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

	public function testWithNot_ShouldBeAddFalseWithDetailsToResultBufferOnce()
	{
		$this->runInTestCallback(function($test, $it) use(&$resultBuffer)
		{
			$resultBuffer = $it->getResultBuffer();

			$assert = new Assert(true);
			$assert->not->true();
			$assert->not->true();

			$test->fail('Should be break');
		});

		$results = $resultBuffer->getResults();

		$this->assertEquals(1, count($results));
		$this->assertFalse($results[0]['result']);
		$this->assertTrue($results[0]['details'] instanceof \spectrum\core\MatcherCallDetails);
	}

	public function testWithNot_ShouldBeProvidePropertiesToDetailsOnce()
	{
		$this->runInTestCallback(function($test, $it) use(&$resultBuffer)
		{
			$resultBuffer = $it->getResultBuffer();

			$assert = new Assert('foo');
			$assert->not->eq('foo');

			$test->fail('Should be break');
		});

		$results = $resultBuffer->getResults();

		$details = $results[0]['details'];
		$this->assertTrue($details instanceof \spectrum\core\MatcherCallDetails);
		$this->assertSame('foo', $details->getActualValue());
		$this->assertSame(true, $details->getNot());
		$this->assertSame('eq', $details->getMatcherName());
		$this->assertSame(array('foo'), $details->getMatcherArgs());
		$this->assertSame(true, $details->getMatcherReturnValue());
		$this->assertSame(null, $details->getException());
	}

	public function testWithNot_ShouldBeProvideNotInvertedMatcherReturnValue()
	{
		$this->runInTestCallback(function($test, $it) use(&$resultBuffer)
		{
			$resultBuffer = $it->getResultBuffer();

			$assert = new Assert(true);
			$assert->not->true();
		});

		$results = $resultBuffer->getResults();
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