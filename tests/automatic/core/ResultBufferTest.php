<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core;
use spectrum\core\ResultBuffer;
use spectrum\core\Spec;

require_once __DIR__ . '/../../init.php';

class ResultBufferTest extends \spectrum\tests\automatic\Test {
	public function testGetOwnerSpec_ReturnsPassedToConstructorSpecInstance() {
		$spec = new Spec();
		$resultBuffer = new ResultBuffer($spec);
		$this->assertSame($spec, $resultBuffer->getOwnerSpec());
	}
	
/**/
	
	public function testAddResult_AcceptsTrueOrFalseOrNullWithDetailsOrWithoutDetails() {
		$resultBuffer = new ResultBuffer(new Spec());
		
		$object1 = new \stdClass();
		$object2 = new \stdClass();
		$object3 = new \stdClass();
		
		$resultBuffer->addResult(true, $object1);
		$resultBuffer->addResult(false, $object2);
		$resultBuffer->addResult(null, $object3);
		
		$resultBuffer->addResult(true, 'aaa');
		$resultBuffer->addResult(false, 'bbb');
		$resultBuffer->addResult(null, 'ccc');
		
		$resultBuffer->addResult(true);
		$resultBuffer->addResult(false);
		$resultBuffer->addResult(null);

		$this->assertSame(array(
			array('result' => true, 'details' => $object1),
			array('result' => false, 'details' => $object2),
			array('result' => null, 'details' => $object3),
			
			array('result' => true, 'details' => 'aaa'),
			array('result' => false, 'details' => 'bbb'),
			array('result' => null, 'details' => 'ccc'),
			
			array('result' => true, 'details' => null),
			array('result' => false, 'details' => null),
			array('result' => null, 'details' => null),
		), $resultBuffer->getResults());
	}
	
	public function providerBadResultValues() {
		return array(
			array(''),
			array('some string'),
			array(0),
			array(1),
			array(456),
			array(0.456),
			array(array()),
			array(new \stdClass()),
			array(function(){}),
		);
	}

	/**
	 * @dataProvider providerBadResultValues
	 */
	public function testAddResult_ResultIsNotTrueAndIsNotFalseAndIsNotNull_ThrowsExceptionAndDoesNotAddResult($badResultValue) {
		$resultBuffer = new ResultBuffer(new Spec());
		
		$this->assertThrowsException('\spectrum\Exception', 'ResultBuffer is accept only "true", "false" or "null"', function() use($resultBuffer, $badResultValue){
			$resultBuffer->addResult($badResultValue);
		});
		
		$this->assertSame(array(), $resultBuffer->getResults());
	}

/**/

	public function testGetResults_ReturnsEmptyArrayByDefault() {
		$resultBuffer = new ResultBuffer(new Spec());
		$this->assertSame(array(), $resultBuffer->getResults());
	}

	public function testGetResults_ReturnsAddedResults() {
		$resultBuffer = new ResultBuffer(new Spec());
		$resultBuffer->addResult(true, 'aaa');
		$resultBuffer->addResult(false, 'bbb');
		$resultBuffer->addResult(null, 'ccc');

		$this->assertSame(array(
			array('result' => true, 'details' => 'aaa'),
			array('result' => false, 'details' => 'bbb'),
			array('result' => null, 'details' => 'ccc'),
		), $resultBuffer->getResults());
	}

/**/

	public function providerFalseResult() {
		return array(
			array(array(false)),
			array(array(false, false)),
			array(array(false, false, false)),
			
			array(array(false, true)),
			array(array(true, false)),
			array(array(true, false, true)),
			array(array(false, true, false)),
			
			array(array(false, null)),
			array(array(null, false)),
			array(array(null, false, null)),
			array(array(false, null, false)),
			
			array(array(false, true, null)),
			array(array(true, false, null)),
			array(array(true, null, false)),
			
			array(array(false, false, true, true, null, null)),
			array(array(true, true, false, false, null, null)),
			array(array(true, true, null, null, false, false)),
			
			array(array(false, false, null, null, true, true)),
			array(array(null, null, false, false, true, true)),
			array(array(null, null, true, true, false, false)),
		);
	}

	/**
	 * @dataProvider providerFalseResult
	 */
	public function testGetTotalResult_ReturnsFalseIfAnyResultIsFalse($results) {
		$resultBuffer = new ResultBuffer(new Spec());
		foreach ($results as $result) {
			$resultBuffer->addResult($result);
		}

		$this->assertSame(false, $resultBuffer->getTotalResult());
	}
	
	public function testGetTotalResult_AnyResultIsNotTrueAndNotNullAndNotFalse_ThrowsException() {
		$resultBufferClass = \spectrum\tests\_testware\tools::createClass('
			class ... extends \spectrum\core\ResultBuffer {
				public function addResult($result, $details = null) {
					$this->results[] = array(
						"result" => $result,
						"details" => $details,
					);
				}
			}
		');
		
		$resultBuffer = new $resultBufferClass(new Spec());
		$resultBuffer->addResult('aaa');

		$this->assertThrowsException('\spectrum\Exception', 'ResultBuffer should be contain "true", "false" or "null" values only (now it is contain value of "string" type)', function() use($resultBuffer){
			$resultBuffer->getTotalResult();
		});
	}

	public function providerTrueResult() {
		return array(
			array(array(true)),
			array(array(true, true)),
			array(array(true, true, true)),
		);
	}

	/**
	 * @dataProvider providerTrueResult
	 */
	public function testGetTotalResult_ReturnsTrueIfAllResultsAreTrue($results) {
		$resultBuffer = new ResultBuffer(new Spec());
		foreach ($results as $result) {
			$resultBuffer->addResult($result);
		}

		$this->assertSame(true, $resultBuffer->getTotalResult());
	}

	public function providerNullResult() {
		return array(
			array(array(null)),
			array(array(null, null)),
			array(array(null, null, null)),
			
			array(array(null, true)),
			array(array(true, null)),
			array(array(true, null, true)),
			array(array(null, true, null)),
		);
	}

	/**
	 * @dataProvider providerNullResult
	 */
	public function testGetTotalResult_ReturnsNullIfAnyResultIsNullAndNoFalseResult($results) {
		$resultBuffer = new ResultBuffer(new Spec());
		foreach ($results as $result) {
			$resultBuffer->addResult($result);
		}
		
		$this->assertSame(null, $resultBuffer->getTotalResult());
	}
	
	public function testGetTotalResult_ReturnsNullIfThereAreNoResults() {
		$resultBuffer = new ResultBuffer(new Spec());
		$this->assertSame(array(), $resultBuffer->getResults());
		$this->assertSame(null, $resultBuffer->getTotalResult());
	}
}