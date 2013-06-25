<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core\asserts\assert\accessToUndefinedProperty;
use spectrum\core\asserts\Assert;

require_once __DIR__ . '/../../../../init.php';

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

	public function testBreakOnFirstMatcherFailDisabled_CatchExceptionsDisabled_ShouldNotBeAddResultToResultBuffer()
	{
		$it = new \spectrum\core\SpecItemIt();
		$it->errorHandling->setBreakOnFirstMatcherFail(false);
		$it->errorHandling->setCatchExceptions(false);

		$it->setTestCallback(function() use(&$resultBuffer, $it){
			$resultBuffer = $it->getResultBuffer();
			$assert = new Assert('');
			$assert->foo;
		});

		try
		{
			$it->run();
		}
		catch (\Exception $e){}

		$this->assertSame(array(), $resultBuffer->getResults());
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

	public function testBreakOnFirstMatcherFailDisabled_CatchExceptionsEnabled_ShouldBeAddFalseResultWithExceptionToResultBuffer()
	{
		$it = new \spectrum\core\SpecItemIt();
		$it->errorHandling->setBreakOnFirstMatcherFail(false);
		$it->errorHandling->setCatchExceptions(true);

		$it->setTestCallback(function() use(&$resultBuffer, $it){
			$resultBuffer = $it->getResultBuffer();
			$assert = new Assert('');
			$assert->foo;
		});

		$it->run();

		$results = $resultBuffer->getResults();
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

	public function testBreakOnFirstMatcherFailEnabled_CatchExceptionsDisabled_ShouldNotBeAddResultToResultBuffer()
	{
		$it = new \spectrum\core\SpecItemIt();
		$it->errorHandling->setBreakOnFirstMatcherFail(true);
		$it->errorHandling->setCatchExceptions(false);

		$it->setTestCallback(function() use(&$resultBuffer, $it){
			$resultBuffer = $it->getResultBuffer();
			$assert = new Assert('');
			$assert->foo;
		});

		try
		{
			$it->run();
		}
		catch (\Exception $e){}

		$this->assertSame(array(), $resultBuffer->getResults());
	}

/**/

	public function testBreakOnFirstMatcherFailEnabled_CatchExceptionsEnabled_ShouldBeAddFalseResultWithExceptionToResultBuffer()
	{
		$it = new \spectrum\core\SpecItemIt();
		$it->errorHandling->setBreakOnFirstMatcherFail(true);
		$it->errorHandling->setCatchExceptions(true);

		$it->setTestCallback(function() use(&$resultBuffer, $it){
			$resultBuffer = $it->getResultBuffer();
			$assert = new Assert('');
			$assert->foo;
		});

		$it->run();

		$results = $resultBuffer->getResults();
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