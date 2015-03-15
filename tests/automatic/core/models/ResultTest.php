<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core\models;

use spectrum\core\models\Result;

require_once __DIR__ . '/../../../init.php';

class ResultTest extends \spectrum\tests\automatic\Test {
	public function testSetValue_AcceptsTrueOrFalseOrNull() {
		$result = new Result();
		
		$result->setValue(true);
		$this->assertSame(true, $result->getValue());
		
		$result->setValue(false);
		$this->assertSame(false, $result->getValue());
		
		$result->setValue(null);
		$this->assertSame(null, $result->getValue());
	}
	
	public function providerWrongResultValues() {
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
	 * @dataProvider providerWrongResultValues
	 */
	public function testSetValue_ResultIsNotTrueAndIsNotFalseAndIsNotNull_ThrowsExceptionAndDoesNotSetResult($wrongValue) {
		$result = new Result();
		
		$this->assertThrowsException('\spectrum\core\Exception', 'Value accepts only "true", "false" or "null"', function() use($result, $wrongValue){
			$result->setValue($wrongValue);
		});
		
		$this->assertSame(null, $result->getValue());
	}

/**/

	public function testGetValue_ReturnsNullByDefault() {
		$result = new Result();
		$this->assertSame(null, $result->getValue());
	}

	public function testGetValue_ReturnsSetValue() {
		$result = new Result();
		$result->setValue(true);
		$this->assertSame(true, $result->getValue());
	}
	
/**/
	
	public function testSetDetails_AcceptsAnyValue() {
		$result = new Result();
		
		$result->setDetails(true);
		$this->assertSame(true, $result->getDetails());
		
		$result->setDetails('aaa');
		$this->assertSame('aaa', $result->getDetails());
		
		$e = new \Exception();
		$result->setDetails($e);
		$this->assertSame($e, $result->getDetails());
	}

/**/

	public function testGetDetails_ReturnsNullByDefault() {
		$result = new Result();
		$this->assertSame(null, $result->getDetails());
	}

	public function testGetDetails_ReturnsSetValue() {
		$result = new Result();
		$result->setDetails('aaa');
		$this->assertSame('aaa', $result->getDetails());
	}
}