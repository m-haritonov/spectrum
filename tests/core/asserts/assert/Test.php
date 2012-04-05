<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\asserts\assert;
require_once dirname(__FILE__) . '/../../../init.php';

abstract class Test extends \spectrum\core\Test
{

/*** Test ware ***/

	protected function runInTestCallback($callback)
	{
		$it = $this->createItWithMatchers();

		$test = $this;
		$it->setTestCallback(function() use($callback, $test, $it, &$isCallbackCalled)
		{
			$isCallbackCalled = true;
			call_user_func($callback, $test, $it, func_get_args());

		});

		$it->run();
		$this->assertTrue($isCallbackCalled);
	}

	protected function createItWithMatchers()
	{
		$it = new \spectrum\core\SpecItemIt();
		$it->errorHandling->setCatchExceptions(false);
		$it->errorHandling->setCatchPhpErrors(false);
		$it->errorHandling->setBreakOnFirstPhpError(false);
		$it->errorHandling->setBreakOnFirstMatcherFail(false);
		
		$it->matchers->add('true', function($actual){
			return ($actual === true);
		});

		$it->matchers->add('false', function($actual){
			return ($actual === false);
		});

		$it->matchers->add('null', function($actual){
			return ($actual === null);
		});

		$it->matchers->add('eq', function($actual, $expected){
			return ($actual == $expected);
		});

		$it->matchers->add('returnSecondArg', function($actual, $expected)
		{
			$args = func_get_args();
			return $args[2];
		});

		$it->matchers->add('bad', function($actual){
			throw new \Exception('I am bad matcher');
		});

		$it->matchers->add('badToo', function($actual){
			throw new \Exception('I am bad matcher too');
		});

		return $it;
	}

	protected function assertAllResultsDetailsDifferent(array $results)
	{
		foreach ($results as $key => $val)
		{
			foreach ($results as $key2 => $val2)
			{
				if ($key != $key2)
				{
					$this->assertNotSame($val['details'], $val2['details']);
				}
			}
		}
	}
}