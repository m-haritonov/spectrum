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

namespace spectrum\core\asserts\assert\accessToUndefinedProperty;
use spectrum\core\asserts\Assert;

require_once dirname(__FILE__) . '/../../../../init.php';

class Test extends \spectrum\core\Test
{
	public function testBreakOnFirstMatcherFailDisabled_CatchExceptionsDisabled_ShouldBeThrowException()
	{
		$it = new \spectrum\core\SpecItemIt();
		$it->errorHandling->setBreakOnFirstMatcherFail(false);
		$it->errorHandling->setCatchExceptions(false);

		$it->setTestCallback(function(){
			$assert = new Assert('');
			$assert->foo;
		});

		$this->assertThrowException('\spectrum\core\asserts\Exception', 'Undefined property "Assert->foo"', function() use($it){
			$it->run();
		});
	}

	public function testBreakOnFirstMatcherFailDisabled_CatchExceptionsDisabled_ShouldNotBeAddResultToRunResultsBuffer()
	{
		$it = new \spectrum\core\SpecItemIt();
		$it->errorHandling->setBreakOnFirstMatcherFail(false);
		$it->errorHandling->setCatchExceptions(false);

		$it->setTestCallback(function() use(&$runResultsBuffer, $it){
			$runResultsBuffer = $it->getRunResultsBuffer();
			$assert = new Assert('');
			$assert->foo;
		});

		try
		{
			$it->run();
		}
		catch (\Exception $e){}

		$this->assertSame(array(), $runResultsBuffer->getResults());
	}

/**/

	public function testBreakOnFirstMatcherFailDisabled_CatchExceptionsEnabled_ShouldNotBeThrowException()
	{
		$it = new \spectrum\core\SpecItemIt();
		$it->errorHandling->setBreakOnFirstMatcherFail(false);
		$it->errorHandling->setCatchExceptions(true);

		$it->setTestCallback(function(){
			$assert = new Assert('');
			$assert->foo;
		});

		$it->run();
	}

	public function testBreakOnFirstMatcherFailDisabled_CatchExceptionsEnabled_ShouldBeAddFalseResultWithExceptionToRunResultsBuffer()
	{
		$it = new \spectrum\core\SpecItemIt();
		$it->errorHandling->setBreakOnFirstMatcherFail(false);
		$it->errorHandling->setCatchExceptions(true);

		$it->setTestCallback(function() use(&$runResultsBuffer, $it){
			$runResultsBuffer = $it->getRunResultsBuffer();
			$assert = new Assert('');
			$assert->foo;
		});

		$it->run();

		$results = $runResultsBuffer->getResults();
		$this->assertEquals(1, count($results));
		$this->assertSame(false, $results[0]['result']);
		$this->assertTrue($results[0]['details'] instanceof \spectrum\core\asserts\Exception);
		$this->assertEquals('Undefined property "Assert->foo"', $results[0]['details']->getMessage());
	}

	public function testBreakOnFirstMatcherFailDisabled_CatchExceptionsEnabled_ShouldBeReturnAssertInstance()
	{
		$it = new \spectrum\core\SpecItemIt();
		$it->errorHandling->setBreakOnFirstMatcherFail(false);
		$it->errorHandling->setCatchExceptions(true);

		$it->setTestCallback(function() use(&$assert, &$return){
			$assert = new Assert('');
			$return = $assert->foo;
		});

		$it->run();

		$this->assertTrue($return instanceof \spectrum\core\asserts\Assert);
		$this->assertSame($assert, $return);
	}

/**/

	public function testBreakOnFirstMatcherFailEnabled_CatchExceptionsDisabled_ShouldBeThrowException()
	{
		$it = new \spectrum\core\SpecItemIt();
		$it->errorHandling->setBreakOnFirstMatcherFail(true);
		$it->errorHandling->setCatchExceptions(false);

		$it->setTestCallback(function(){
			$assert = new Assert('');
			$assert->foo;
		});

		$this->assertThrowException('\spectrum\core\asserts\Exception', 'Undefined property "Assert->foo"', function() use($it){
			$it->run();
		});
	}

	public function testBreakOnFirstMatcherFailEnabled_CatchExceptionsDisabled_ShouldNotBeAddResultToRunResultsBuffer()
	{
		$it = new \spectrum\core\SpecItemIt();
		$it->errorHandling->setBreakOnFirstMatcherFail(true);
		$it->errorHandling->setCatchExceptions(false);

		$it->setTestCallback(function() use(&$runResultsBuffer, $it){
			$runResultsBuffer = $it->getRunResultsBuffer();
			$assert = new Assert('');
			$assert->foo;
		});

		try
		{
			$it->run();
		}
		catch (\Exception $e){}

		$this->assertSame(array(), $runResultsBuffer->getResults());
	}

/**/

	public function testBreakOnFirstMatcherFailEnabled_CatchExceptionsEnabled_ShouldBeAddFalseResultWithExceptionToRunResultsBuffer()
	{
		$it = new \spectrum\core\SpecItemIt();
		$it->errorHandling->setBreakOnFirstMatcherFail(true);
		$it->errorHandling->setCatchExceptions(true);

		$it->setTestCallback(function() use(&$runResultsBuffer, $it){
			$runResultsBuffer = $it->getRunResultsBuffer();
			$assert = new Assert('');
			$assert->foo;
		});

		$it->run();

		$results = $runResultsBuffer->getResults();
		$this->assertEquals(1, count($results));
		$this->assertSame(false, $results[0]['result']);
		$this->assertTrue($results[0]['details'] instanceof \spectrum\core\asserts\Exception);
		$this->assertEquals('Undefined property "Assert->foo"', $results[0]['details']->getMessage());
	}

	public function testBreakOnFirstMatcherFailEnabled_CatchExceptionsEnabled_ShouldBeThrowBreakException()
	{
		$it = new \spectrum\core\SpecItemIt();
		$it->errorHandling->setBreakOnFirstMatcherFail(true);
		$it->errorHandling->setCatchExceptions(true);

		$it->setTestCallback(function() use(&$thrownException){
			$assert = new Assert('');
			try {
				$assert->foo;
			}
			catch (\Exception $e)
			{
				$thrownException = $e;
			}
		});

		$it->run();

		$this->assertTrue($thrownException instanceof \spectrum\core\ExceptionBreak);
	}
}