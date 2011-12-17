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

namespace net\mkharitonov\spectrum\core\asserts\assert\callMatcher\matcherReturnTrue;
use net\mkharitonov\spectrum\core\asserts\Assert;

require_once dirname(__FILE__) . '/../../../../../init.php';

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
class Test extends \net\mkharitonov\spectrum\core\asserts\assert\callMatcher\Test
{
	public function testShouldNotBeBreakExecution()
	{
		$this->runInTestCallback(function($test, $it) use(&$isExecuted)
		{
			$assert = new Assert(true);
			$assert->true();
			$isExecuted = true;
		});

		$this->assertTrue($isExecuted);
	}

	public function testShouldBeAddTrueWithDetailsToRunResultsBufferForEachMatcher()
	{
		$this->runInTestCallback(function($test, $it) use(&$runResultsBuffer)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();

			$assert = new Assert(true);
			$assert->true();
			$assert->true();

			$assert = new Assert(true);
			$assert->true();
		});

		$results = $runResultsBuffer->getResults();

		$this->assertEquals(3, count($results));

		$this->assertTrue($results[0]['result']);
		$this->assertTrue($results[1]['result']);
		$this->assertTrue($results[2]['result']);

		$this->assertTrue($results[0]['details'] instanceof \net\mkharitonov\spectrum\core\asserts\RunResultDetails);
		$this->assertTrue($results[1]['details'] instanceof \net\mkharitonov\spectrum\core\asserts\RunResultDetails);
		$this->assertTrue($results[2]['details'] instanceof \net\mkharitonov\spectrum\core\asserts\RunResultDetails);

		$this->assertAllResultsDetailsDifferent($results);
	}

	public function testShouldBeProvidePropertiesToDetailsForEachMatcher()
	{
		$this->runInTestCallback(function($test, $it) use(&$runResultsBuffer)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();

			$assert = new Assert(true);
			$assert->true();
			$assert->returnSecondArg(1, 'bar');

			$assert = new Assert('foo');
			$assert->eq('foo');
		});

		$results = $runResultsBuffer->getResults();

		$details = $results[0]['details'];
		$this->assertSame(true, $details->getActualValue());
		$this->assertSame(false, $details->getIsNot());
		$this->assertSame('true', $details->getMatcherName());
		$this->assertSame(array(), $details->getMatcherArgs());
		$this->assertSame(true, $details->getMatcherReturnValue());
		$this->assertSame(null, $details->getMatcherException());

		$details = $results[1]['details'];
		$this->assertSame(true, $details->getActualValue());
		$this->assertSame(false, $details->getIsNot());
		$this->assertSame('returnSecondArg', $details->getMatcherName());
		$this->assertSame(array(1, 'bar'), $details->getMatcherArgs());
		$this->assertSame('bar', $details->getMatcherReturnValue());
		$this->assertSame(null, $details->getMatcherException());

		$details = $results[2]['details'];
		$this->assertSame('foo', $details->getActualValue());
		$this->assertSame(false, $details->getIsNot());
		$this->assertSame('eq', $details->getMatcherName());
		$this->assertSame(array('foo'), $details->getMatcherArgs());
		$this->assertSame(true, $details->getMatcherReturnValue());
		$this->assertSame(null, $details->getMatcherException());
	}

	public function testShouldBeReturnCurrentAssertObject()
	{
		$this->runInTestCallback(function($test, $it) use(&$isCalled)
		{
			$assert = new Assert(true);
			$test->assertSame($assert, $assert->true());
			$test->assertSame($assert, $assert->true()->true());
			$test->assertSame($assert, $assert->true()->true()->true());

			$isCalled = true;
		});

		$this->assertTrue($isCalled);
	}

	// TODO: продублировать во всех тестах callMatcher
	public function testShouldBeSupportManyCalls()
	{
		$this->runInTestCallback(function($test, $it) use(&$isCalled, &$callsArgs)
		{
			$it->matchers->add('foo', function($actual, $arg) use(&$callsArgs){
				$callsArgs[] = $arg;
				return true;
			});

			$assert = new Assert(true);
			$assert->foo('foo')->foo('bar')->foo('baz');

			$isCalled = true;
		});

		$this->assertSame(array(
			'foo',
			'bar',
			'baz',
		), $callsArgs);

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
			$assert->not->false();
		});

		$results = $runResultsBuffer->getResults();
		$this->assertEquals(1, count($results));
		$this->assertSame(true, $results[0]['result']);
		$this->assertTrue($results[0]['details'] instanceof \net\mkharitonov\spectrum\core\asserts\RunResultDetails);
	}

	public function testWithNot_ShouldBeAddTrueWithDetailsToRunResultsBufferForEachMatcher()
	{
		$this->runInTestCallback(function($test, $it) use(&$runResultsBuffer)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();

			$assert = new Assert(true);
			$assert->not->false();
			$assert->not->false();

			$assert = new Assert(true);
			$assert->not->false();
		});

		$results = $runResultsBuffer->getResults();

		$this->assertEquals(3, count($results));

		$this->assertTrue($results[0]['result']);
		$this->assertTrue($results[1]['result']);
		$this->assertTrue($results[2]['result']);

		$this->assertTrue($results[0]['details'] instanceof \net\mkharitonov\spectrum\core\asserts\RunResultDetails);
		$this->assertTrue($results[1]['details'] instanceof \net\mkharitonov\spectrum\core\asserts\RunResultDetails);
		$this->assertTrue($results[2]['details'] instanceof \net\mkharitonov\spectrum\core\asserts\RunResultDetails);

		$this->assertAllResultsDetailsDifferent($results);
	}

	public function testWithNot_ShouldBeProvidePropertiesToDetailsForEachMatcher()
	{
		$this->runInTestCallback(function($test, $it) use(&$runResultsBuffer)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();

			$assert = new Assert(true);
			$assert->not->false();
			$assert->not->returnSecondArg('bar', 0);

			$assert = new Assert('foo');
			$assert->not->eq('bar');
		});

		$results = $runResultsBuffer->getResults();

		$details = $results[0]['details'];
		$this->assertSame(true, $details->getActualValue());
		$this->assertSame(true, $details->getIsNot());
		$this->assertSame('false', $details->getMatcherName());
		$this->assertSame(array(), $details->getMatcherArgs());
		$this->assertSame(false, $details->getMatcherReturnValue());
		$this->assertSame(null, $details->getMatcherException());

		$details = $results[1]['details'];
		$this->assertSame(true, $details->getActualValue());
		$this->assertSame(true, $details->getIsNot());
		$this->assertSame('returnSecondArg', $details->getMatcherName());
		$this->assertSame(array('bar', 0), $details->getMatcherArgs());
		$this->assertSame(0, $details->getMatcherReturnValue());
		$this->assertSame(null, $details->getMatcherException());

		$details = $results[2]['details'];
		$this->assertSame('foo', $details->getActualValue());
		$this->assertSame(true, $details->getIsNot());
		$this->assertSame('eq', $details->getMatcherName());
		$this->assertSame(array('bar'), $details->getMatcherArgs());
		$this->assertSame(false, $details->getMatcherReturnValue());
		$this->assertSame(null, $details->getMatcherException());
	}

	public function testWithNot_ShouldBeProvideNotInvertedMatcherReturnValue()
	{
		$this->runInTestCallback(function($test, $it) use(&$runResultsBuffer)
		{
			$runResultsBuffer = $it->getRunResultsBuffer();

			$assert = new Assert(true);
			$assert->not->false();
		});

		$results = $runResultsBuffer->getResults();
		$this->assertSame(false, $results[0]['details']->getMatcherReturnValue());
	}

	public function testWithNot_ShouldBeResetNotAfterCall()
	{
		$this->runInTestCallback(function($test, $it) use(&$isCalled)
		{
			$assert = new Assert(true);

			$assert->not->false();
			$test->assertFalse($assert->isNot());

			$assert->true();
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
			$test->assertSame($assert, $assert->not->false()->not->false()->not->false());

			$isCalled = true;
		});

		$this->assertTrue($isCalled);
	}
}