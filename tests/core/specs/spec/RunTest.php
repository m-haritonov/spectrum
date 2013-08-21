<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core\spec;
use spectrum\config;
use spectrum\core\Spec;

require_once __DIR__ . '/../../../init.php';

class RunTest extends \spectrum\tests\Test
{
	public function setUp()
	{
		parent::setUp();
		config::unregisterSpecPlugins();
	}

	/*
	public function testReturnsNewInstanceForEachEndingSpec()
	{
		$spec = new Spec();
		$this->assertInstanceOf('\spectrum\core\ResultBuffer', $spec->getResultBuffer());
	}
	
	public function testReturnsNullForNotEndingSpecs()
	{
		$spec = new Spec();
		$this->assertSame(null, $spec->getDeepestRunningSpec());
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

*/
}