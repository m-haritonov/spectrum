<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core;
use spectrum\core\Result;
use spectrum\core\Results;
use spectrum\core\Spec;

require_once __DIR__ . '/../../init.php';

class ResultsTest extends \spectrum\tests\automatic\Test {
	public function testGetOwnerSpec_ReturnsPassedToConstructorSpecInstance() {
		$spec = new Spec();
		$results = new Results($spec);
		$this->assertSame($spec, $results->getOwnerSpec());
	}
	
/**/
	
	public function testAdd_AddsResultObjectWithPassedValueAndDetails() {
		$results = new Results(new Spec());
		$results->add(true, 'aaa');
		
		$resultsContent = $results->getAll();
		$this->assertSame(1, count($resultsContent));
		$this->assertTrue($resultsContent[0] instanceof Result);
		$this->assertSame(true, $resultsContent[0]->getValue());
		$this->assertSame('aaa', $resultsContent[0]->getDetails());
	}
	
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

		$this->assertSame(9, count($results->getAll()));
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
	public function testAdd_ResultIsNotTrueAndIsNotFalseAndIsNotNull_ThrowsExceptionAndDoesNotAddResult($wrongValue) {
		$results = new Results(new Spec());
		
		$this->assertThrowsException('\spectrum\Exception', 'Value accepts only "true", "false" or "null"', function() use($results, $wrongValue){
			$results->add($wrongValue);
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
		$resultsContent = $results->getAll();
		
		$this->assertSame(2, count($resultsContent));
		$this->assertSame(true, $resultsContent[0]->getValue());
		$this->assertSame('aaa', $resultsContent[0]->getDetails());
		$this->assertSame(false, $resultsContent[1]->getValue());
		$this->assertSame('bbb', $resultsContent[1]->getDetails());
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