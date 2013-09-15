<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core\verifications\verification;

require_once __DIR__ . '/../../../init.php';

abstract class Test extends \spectrum\core\Test
{

/*** Test ware ***/

	public function createSpecIt()
	{
		$it = new \spectrum\core\SpecItemIt();
		$it->errorHandling->setCatchExceptions(false);
		$it->errorHandling->setCatchPhpErrors(false);
		$it->errorHandling->setBreakOnFirstPhpError(false);
		$it->errorHandling->setBreakOnFirstMatcherFail(false);
		return $it;
	}
	
	public function runInTestCallback(\spectrum\core\SpecItemIt $it, $callback)
	{
		$test = $this;
		$it->setTestCallback(function() use($callback, $test, $it, &$isCallbackCalled)
		{
			$isCallbackCalled = true;
			call_user_func($callback, $test, $it, func_get_args());
		});

		$it->run();
		$this->assertTrue($isCallbackCalled);
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