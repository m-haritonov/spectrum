<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core;
use spectrum\core\Results;
use spectrum\core\ResultsInterface;
use spectrum\core\Spec;

require_once __DIR__ . '/../../init.php';

class ResultsTest extends \spectrum\tests\automatic\Test {
	public function testGetOwnerSpec_ReturnsPassedToConstructorSpecInstance() {
		$spec = new Spec();
		$results = new Results($spec);
		$this->assertSame($spec, $results->getOwnerSpec());
	}
	
/**/
	
	public function testAdd_AcceptsTrueOrFalseOrNullWithDetailsOrWithoutDetails() {
		$results = new Results(new Spec());
		
		$object1 = new \stdClass();
		$object2 = new \stdClass();
		$object3 = new \stdClass();
		
		$results->add(true, $object1);
		$results->add(false, $object2);
		$results->add(null, $object3);
		
		$results->add(true, 'aaa');
		$results->add(false, 'bbb');
		$results->add(null, 'ccc');
		
		$results->add(true);
		$results->add(false);
		$results->add(null);

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
		), $results->getAll());
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
	public function testAdd_ResultIsNotTrueAndIsNotFalseAndIsNotNull_ThrowsExceptionAndDoesNotAddResult($badResultValue) {
		$results = new Results(new Spec());
		
		$this->assertThrowsException('\spectrum\Exception', 'Results is accept only "true", "false" or "null"', function() use($results, $badResultValue){
			$results->add($badResultValue);
		});
		
		$this->assertSame(array(), $results->getAll());
	}

/**/

	public function testGetAll_ReturnsEmptyArrayByDefault() {
		$results = new Results(new Spec());
		$this->assertSame(array(), $results->getAll());
	}

	public function testGetAll_ReturnsAddedResults() {
		$results = new Results(new Spec());
		$results->add(true, 'aaa');
		$results->add(false, 'bbb');
		$results->add(null, 'ccc');

		$this->assertSame(array(
			array('result' => true, 'details' => 'aaa'),
			array('result' => false, 'details' => 'bbb'),
			array('result' => null, 'details' => 'ccc'),
		), $results->getAll());
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
	public function testGetTotal_ReturnsFalseIfAnyResultIsFalse($resultsContent) {
		$results = new Results(new Spec());
		foreach ($resultsContent as $result) {
			$results->add($result);
		}

		$this->assertSame(false, $results->getTotal());
	}
	
	public function testGetTotal_AnyResultIsNotTrueAndNotNullAndNotFalse_ThrowsException() {
		$resultsClass = \spectrum\tests\_testware\tools::createClass('
			class ... extends \spectrum\core\Results {
				public function add($result, $details = null) {
					$this->results[] = array(
						"result" => $result,
						"details" => $details,
					);
				}
			}
		');
		
		/** @var ResultsInterface $results */
		$results = new $resultsClass(new Spec());
		$results->add('aaa');

		$this->assertThrowsException('\spectrum\Exception', 'Results should be contain "true", "false" or "null" values only (now it is contain value of "string" type)', function() use($results){
			$results->getTotal();
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
	public function testGetTotal_ReturnsTrueIfAllResultsAreTrue($resultsContent) {
		$results = new Results(new Spec());
		foreach ($resultsContent as $result) {
			$results->add($result);
		}

		$this->assertSame(true, $results->getTotal());
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
	public function testGetTotal_ReturnsNullIfAnyResultIsNullAndNoFalseResult($resultsContent) {
		$results = new Results(new Spec());
		foreach ($resultsContent as $result) {
			$results->add($result);
		}
		
		$this->assertSame(null, $results->getTotal());
	}
	
	public function testGetTotal_ReturnsNullIfThereAreNoResults() {
		$results = new Results(new Spec());
		$this->assertSame(array(), $results->getAll());
		$this->assertSame(null, $results->getTotal());
	}
}