<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\core;
use spectrum\core\ResultBuffer;
use spectrum\core\Spec;

require_once __DIR__ . '/../init.php';

class ResultBufferTest extends \spectrum\tests\core\Test
{
	public function testAddFailResult_ShouldBeAddFalseToResults()
	{
		$resultBuffer = new ResultBuffer(new Spec());
		$resultBuffer->addFailResult('aaa');
		$resultBuffer->addFailResult('bbb');
		$resultBuffer->addFailResult('ccc');

		$this->assertSame(array(
			array('result' => false, 'details' => 'aaa'),
			array('result' => false, 'details' => 'bbb'),
			array('result' => false, 'details' => 'ccc'),
		), $resultBuffer->getResults());
	}
	
	public function testAddSuccessResult_ShouldBeAddTrueToResults()
	{
		$resultBuffer = new ResultBuffer(new Spec());
		$resultBuffer->addSuccessResult('aaa');
		$resultBuffer->addSuccessResult('bbb');
		$resultBuffer->addSuccessResult('ccc');

		$this->assertSame(array(
			array('result' => true, 'details' => 'aaa'),
			array('result' => true, 'details' => 'bbb'),
			array('result' => true, 'details' => 'ccc'),
		), $resultBuffer->getResults());
	}

/**/

	public function testGetResults_ShouldBeReturnEmptyArrayByDefault()
	{
		$resultBuffer = new ResultBuffer(new Spec());
		$this->assertSame(array(), $resultBuffer->getResults());
	}

	public function testGetResults_ShouldBeReturnAddedResults()
	{
		$resultBuffer = new ResultBuffer(new Spec());
		$resultBuffer->addFailResult();
		$resultBuffer->addSuccessResult();
		$resultBuffer->addFailResult('aaa');
		$resultBuffer->addSuccessResult('bbb');

		$this->assertSame(array(
			array('result' => false, 'details' => null),
			array('result' => true, 'details' => null),
			array('result' => false, 'details' => 'aaa'),
			array('result' => true, 'details' => 'bbb'),
		), $resultBuffer->getResults());
	}

/**/

	public function testGetTotalResult_ShouldBeReturnFalseIfAnyResultIsFalse()
	{
		$resultBuffer = new ResultBuffer(new Spec());
		$resultBuffer->addSuccessResult();
		$resultBuffer->addFailResult();
		$resultBuffer->addSuccessResult();

		$this->assertFalse($resultBuffer->getTotalResult());
	}

	public function testGetTotalResult_ShouldBeReturnFalseIfAnyResultIsNull()
	{
		$resultBuffer = new ResultBuffer(new Spec());
		$resultBuffer->addSuccessResult();
		$resultBuffer->addFailResult();
		$resultBuffer->addSuccessResult();

		$this->assertFalse($resultBuffer->getTotalResult());
	}

	public function testGetTotalResult_ShouldBeReturnTrueIfAllResultsIsTrue()
	{
		$resultBuffer = new ResultBuffer(new Spec());
		$resultBuffer->addSuccessResult();
		$resultBuffer->addSuccessResult();

		$this->assertTrue($resultBuffer->getTotalResult());
	}

	public function testGetTotalResult_ShouldBeReturnNullOnlyIfNoResults()
	{
		$resultBuffer = new ResultBuffer(new Spec());
		$this->assertSame(array(), $resultBuffer->getResults());
		$this->assertNull($resultBuffer->getTotalResult());
	}
}