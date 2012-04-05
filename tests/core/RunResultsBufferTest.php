<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core;
require_once dirname(__FILE__) . '/../init.php';

class RunResultsBufferTest extends Test
{
	/**
	 * @var \spectrum\core\RunResultsBuffer
	 */
	private $runResultsBuffer;

	public function setUp()
	{
		parent::setUp();

		$this->runResultsBuffer = new RunResultsBuffer(new SpecItemIt());
	}

	public function testAddResult_ShouldBeCanAcceptResultOnly()
	{
		$this->runResultsBuffer->addResult(false);

		$this->assertSame(array(
			array('result' => false, 'details' => null),
		), $this->runResultsBuffer->getResults());
	}

	public function testAddResult_ShouldBeCanAcceptResultAndDetails()
	{
		$exception = new Exception();
		$this->runResultsBuffer->addResult(false, $exception);

		$this->assertSame(array(
			array('result' => false, 'details' => $exception),
		), $this->runResultsBuffer->getResults());
	}

	public function testAddResult_ShouldBeCastResultsLikeFalseToBooleanFalse()
	{
		$this->runResultsBuffer->addResult(false);
		$this->runResultsBuffer->addResult(null);
		$this->runResultsBuffer->addResult(0);
		$this->runResultsBuffer->addResult('');

		$this->assertSame(array(
			array('result' => false, 'details' => null),
			array('result' => false, 'details' => null),
			array('result' => false, 'details' => null),
			array('result' => false, 'details' => null),
		), $this->runResultsBuffer->getResults());
	}

	public function testAddResult_ShouldBeCastResultsLikeTrueToBooleanTrue()
	{
		$this->runResultsBuffer->addResult(true);
		$this->runResultsBuffer->addResult(1);
		$this->runResultsBuffer->addResult(-1);
		$this->runResultsBuffer->addResult('foo');

		$this->assertSame(array(
			array('result' => true, 'details' => null),
			array('result' => true, 'details' => null),
			array('result' => true, 'details' => null),
			array('result' => true, 'details' => null),
		), $this->runResultsBuffer->getResults());
	}

	public function testAddResult_ShouldBeCanAcceptMixedDetails()
	{
		$exception = new Exception();
		$this->runResultsBuffer->addResult(true, $exception);
		$this->runResultsBuffer->addResult(true, 'foo is not bar');
		$this->runResultsBuffer->addResult(true, array('foo', 'bar'));

		$this->assertSame(array(
			array('result' => true, 'details' => $exception),
			array('result' => true, 'details' => 'foo is not bar'),
			array('result' => true, 'details' => array('foo', 'bar')),
		), $this->runResultsBuffer->getResults());
	}

	public function testAddResult_ShouldBeCollectResults()
	{
		$exception1 = new Exception();
		$exception2 = new Exception();

		$this->runResultsBuffer->addResult(false, $exception1);
		$this->runResultsBuffer->addResult(false, $exception2);
		$this->runResultsBuffer->addResult(true);

		$this->assertSame(array(
			array('result' => false, 'details' => $exception1),
			array('result' => false, 'details' => $exception2),
			array('result' => true, 'details' => null),
		), $this->runResultsBuffer->getResults());
	}
	
/**/

	public function testGetResults_ShouldBeReturnEmptyArrayByDefault()
	{
		$this->assertSame(array(), $this->runResultsBuffer->getResults());
	}

	public function testGetResults_ShouldBeReturnAddedResults()
	{
		$this->runResultsBuffer->addResult(0);
		$this->runResultsBuffer->addResult('foo');

		$this->assertSame(array(
			array('result' => false, 'details' => null),
			array('result' => true, 'details' => null),
		), $this->runResultsBuffer->getResults());
	}

/**/

	/**
	 * @see RunResultsBufferTest::addResult()
	 */
	public function testCalculateFinalResult_ShouldBeReturnFalseIfAnyResultIsFalse()
	{
		$this->runResultsBuffer->addResult(true);
		$this->runResultsBuffer->addResult(false);
		$this->runResultsBuffer->addResult(true);

		$this->assertFalse($this->runResultsBuffer->calculateFinalResult());
	}

	public function testCalculateFinalResult_ShouldBeReturnFalseIfAnyResultIsNull()
	{
		$this->runResultsBuffer->addResult(true);
		$this->runResultsBuffer->addResult(null);
		$this->runResultsBuffer->addResult(true);

		$this->assertFalse($this->runResultsBuffer->calculateFinalResult());
	}

	public function testCalculateFinalResult_ShouldBeReturnTrueIfAllResultsIsTrue()
	{
		$this->runResultsBuffer->addResult(true);
		$this->runResultsBuffer->addResult(true);

		$this->assertTrue($this->runResultsBuffer->calculateFinalResult());
	}

	public function testCalculateFinalResult_ShouldBeReturnNullOnlyIfNoResults()
	{
		$this->assertSame(array(), $this->runResultsBuffer->getResults());
		
		$this->assertNull($this->runResultsBuffer->calculateFinalResult());
	}
}