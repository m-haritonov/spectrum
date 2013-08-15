<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core\specItemIt;
use spectrum\core\ResultBuffer;
use spectrum\core\SpecItem;
use spectrum\core\SpecItemIt;

require_once __DIR__ . '/../../init.php';

class RunTest extends Test
{
	public function testShouldBeCallTestCallback()
	{
		$it = new SpecItemIt();
		$isCalled = false;
		$it->setTestCallback(function() use(&$isCalled){ $isCalled = true; });

		$it->run();

		$this->assertTrue($isCalled);
	}

	public function testShouldBePassTestCallbackArgumentsToTestCallback()
	{
		$it = new SpecItemIt();
		$it->setTestCallbackArguments(array('foo', 'bar', 'baz'));
		$it->setTestCallback(function() use(&$passedArguments){
			$passedArguments = func_get_args();
		});

		$it->run();

		$this->assertEquals(3, count($passedArguments));

		$this->assertEquals('foo', $passedArguments[0]);
		$this->assertEquals('bar', $passedArguments[1]);
		$this->assertEquals('baz', $passedArguments[2]);
	}

	public function testShouldBeCreateNewEmptyResultBufferBeforeEveryRun()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function() use($it, &$resultBuffers){
			$resultBuffers[] = $it->getResultBuffer();
		});

		$it->run();
		$this->assertEquals(1, count($resultBuffers));
		$this->assertTrue($resultBuffers[0] instanceof ResultBuffer);
		$this->assertSame(array(), $resultBuffers[0]->getResults());

		$it->run();
		$this->assertEquals(2, count($resultBuffers));
		$this->assertTrue($resultBuffers[1] instanceof ResultBuffer);
		$this->assertSame(array(), $resultBuffers[1]->getResults());

		$this->assertNotSame($resultBuffers[0], $resultBuffers[1]);
	}

	public function testShouldBeUnsetReferenceToResultBufferAfterRun()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function() use($it){
			$it->getResultBuffer()->addResult(false);
		});

		$it->run();

		$this->assertNull($it->getResultBuffer());
	}

	public function testShouldBeUnsetResultsInResultBufferAfterRun()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function() use($it, &$resultBuffer){
			$it->getResultBuffer()->addResult(false);
			$it->getResultBuffer()->addResult(true, 'details foo bar');
			$resultBuffer = $it->getResultBuffer();
		});

		$it->run();

		$this->assertSame(array(
			array('result' => false, 'details' => null),
			array('result' => true, 'details' => 'details foo bar'),
		), $resultBuffer->getResults());
	}

	public function testShouldBeIgnorePreviousRunResult()
	{
		$it = new SpecItemIt();

		$it->setTestCallback(function() use($it) { $it->getResultBuffer()->addResult(false); });
		$this->assertFalse($it->run());

		$it->setTestCallback(function() use($it) { $it->getResultBuffer()->addResult(true); });
		$this->assertTrue($it->run());
	}

	public function testShouldBeSetSelfAsRunningSpecItemToRegistryDuringRun()
	{
		$it = new SpecItemIt();
		$runningSpecItem = null;
		$it->setTestCallback(function() use(&$runningSpecItem){
			$runningSpecItem = \spectrum\core\Registry::getRunningSpecItem();
		});

		$it->run();

		$this->assertSame($it, $runningSpecItem);
	}

	public function testShouldBeRestoreRunningSpecItemInRegistryAfterRun()
	{
		$runningSpecItemBackup = \spectrum\core\Registry::getRunningSpecItem();
		$it = new SpecItemIt();
		$it->setTestCallback(function(){});

		$it->run();

		$this->assertSame($runningSpecItemBackup, \spectrum\core\Registry::getRunningSpecItem());
	}

	public function testShouldBeRestoreRunningSpecItemInRegistryAfterNestedRun()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function() use(&$runningSpecItemAfterNestedRun)
		{
			$it2 = new SpecItemIt();
			$it2->setTestCallback(function() use($it2) {});
			$it2->run();

			$runningSpecItemAfterNestedRun = \spectrum\core\Registry::getRunningSpecItem();
		});

		$it->run();

		$this->assertSame($it, $runningSpecItemAfterNestedRun);
	}

/**/

	public function testReturnValue_ShouldBeReturnFalseIfAnyResultInStackIsLikeFalse()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function() use($it)
		{
			$it->getResultBuffer()->addResult(true);
			$it->getResultBuffer()->addResult(null);
			$it->getResultBuffer()->addResult(true);
		});

		$this->assertFalse($it->run());
	}

	public function testReturnValue_ShouldBeReturnTrueIfAllResultsInStackIsLikeTrue()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function() use($it)
		{
			$it->getResultBuffer()->addResult(true);
			$it->getResultBuffer()->addResult(1);
		});

		$this->assertTrue($it->run());
	}

	public function testReturnValue_ShouldBeReturnNullIfNoResultsInStack()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function() use($it) {});

		$this->assertNull($it->run());
	}

	public function testReturnValue_ShouldBeReturnNullIfTestCallbackNotSet()
	{
		$it = new SpecItemIt();
		$this->assertNull($it->run());
	}
}