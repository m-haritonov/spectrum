<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\specItemIt;
use spectrum\core\RunResultsBuffer;
use spectrum\core\SpecItem;
use spectrum\core\SpecItemIt;

require_once dirname(__FILE__) . '/../../init.php';

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

	public function testShouldBeCreateNewEmptyRunResultsBufferBeforeEveryRun()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function() use($it, &$runResultsBuffers){
			$runResultsBuffers[] = $it->getRunResultsBuffer();
		});

		$it->run();
		$this->assertEquals(1, count($runResultsBuffers));
		$this->assertTrue($runResultsBuffers[0] instanceof RunResultsBuffer);
		$this->assertSame(array(), $runResultsBuffers[0]->getResults());

		$it->run();
		$this->assertEquals(2, count($runResultsBuffers));
		$this->assertTrue($runResultsBuffers[1] instanceof RunResultsBuffer);
		$this->assertSame(array(), $runResultsBuffers[1]->getResults());

		$this->assertNotSame($runResultsBuffers[0], $runResultsBuffers[1]);
	}

	public function testShouldBeUnsetReferenceToRunResultsBufferAfterRun()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function() use($it){
			$it->getRunResultsBuffer()->addResult(false);
		});

		$it->run();

		$this->assertNull($it->getRunResultsBuffer());
	}

	public function testShouldBeUnsetResultsInRunResultsBufferAfterRun()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function() use($it, &$runResultsBuffer){
			$it->getRunResultsBuffer()->addResult(false);
			$it->getRunResultsBuffer()->addResult(true, 'details foo bar');
			$runResultsBuffer = $it->getRunResultsBuffer();
		});

		$it->run();

		$this->assertSame(array(
			array('result' => false, 'details' => null),
			array('result' => true, 'details' => 'details foo bar'),
		), $runResultsBuffer->getResults());
	}

	public function testShouldBeIgnorePreviousRunResult()
	{
		$it = new SpecItemIt();

		$it->setTestCallback(function() use($it) { $it->getRunResultsBuffer()->addResult(false); });
		$this->assertFalse($it->run());

		$it->setTestCallback(function() use($it) { $it->getRunResultsBuffer()->addResult(true); });
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
			$it->getRunResultsBuffer()->addResult(true);
			$it->getRunResultsBuffer()->addResult(null);
			$it->getRunResultsBuffer()->addResult(true);
		});

		$this->assertFalse($it->run());
	}

	public function testReturnValue_ShouldBeReturnTrueIfAllResultsInStackIsLikeTrue()
	{
		$it = new SpecItemIt();
		$it->setTestCallback(function() use($it)
		{
			$it->getRunResultsBuffer()->addResult(true);
			$it->getRunResultsBuffer()->addResult(1);
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