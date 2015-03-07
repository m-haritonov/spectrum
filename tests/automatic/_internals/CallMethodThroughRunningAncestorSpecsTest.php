<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\_internals;

use spectrum\config;
use spectrum\core\SpecInterface;

require_once __DIR__ . '/../../init.php';

class CallMethodThroughRunningAncestorSpecsTest extends \spectrum\tests\automatic\Test {
	public function provider() {
		return array(
			array(
				'
				  __0__
				 /     \
				1       2
				 \     /
				checkpoint
				',
				array(
					0 => 'aaa',
					1 => 'bbb',
					2 => 'ccc',
					'checkpoint' => 'ddd',
				),
				array('ddd', 'ddd'),
			),
			
			array(
				'
				  __0__
				 /     \
				1       2
				 \     /
				checkpoint
				',
				array(
					0 => 'aaa',
					1 => 'bbb',
					2 => 'ccc',
				),
				array('bbb', 'ccc'),
			),
			
			array(
				'
				  __0__
				 /     \
				1       2
				 \     /
				checkpoint
				',
				array(0 => 'aaa'),
				array('aaa', 'aaa'),
			),
		);
	}
	
	/**
	 * @dataProvider provider
	 */
	public function testReturnsValueFromFirstRunningSpecFromSelfToUp($specVisualPattern, $values, $expectedReturnValues) {
		$actualReturnValues = array();
		$specs = $this->createSpecsByVisualPattern($specVisualPattern, array(), $this->createClass('
			class ... extends \spectrum\core\Spec {
				public $value = null;
				
				public function getValue() {
					return $this->value;
				}
			}
		'));
		
		config::registerEventListener('onSpecRunStart', function(SpecInterface $spec) use(&$specs, &$actualReturnValues) {
			if ($spec === $specs["checkpoint"]) {
				$actualReturnValues[] = \spectrum\_internals\callMethodThroughRunningAncestorSpecs($spec, "getValue");
			}
		});
		
		foreach ($values as $specKey => $pluginValue) {
			$specs[$specKey]->value = $pluginValue;
		}
		
		$specs[0]->run();
		
		$this->assertSame($expectedReturnValues, $actualReturnValues);
	}
	
	public function testPassesArgumentsToCalleeMethod() {
		\spectrum\tests\automatic\Test::$temp["passedArguments"] = array();
		
		config::registerEventListener('onSpecRunStart', function(SpecInterface $spec) {
			\spectrum\_internals\callMethodThroughRunningAncestorSpecs($spec, "getValue", array("aaa", "bbb", "ccc"));
		});
		
		$specClass = $this->createClass('
			class ... extends \spectrum\core\Spec {
				public $value = null;
				
				public function getValue() {
					\spectrum\tests\automatic\Test::$temp["passedArguments"][] = func_get_args();
					return $this->value;
				}
			}
		');
		
		/** @var SpecInterface $spec */
		$spec = new $specClass();
		$spec->run();
		
		$this->assertSame(array(array("aaa", "bbb", "ccc")), \spectrum\tests\automatic\Test::$temp["passedArguments"]);
	}
	
	public function provider2() {
		return array(
			array(
				'
				  __0__
				 /     \
				1       2
				 \     /
				checkpoint
				',
				array(
					0 => 222,
					1 => 333,
					2 => 111,
					'checkpoint' => 111,
				),
				111,
				true,
				array(333, 222),
			),
			
			array(
				'
				  __0__
				 /     \
				1       2
				 \     /
				checkpoint
				',
				array(
					0 => 222,
					1 => '111',
					2 => 111,
					'checkpoint' => 111,
				),
				111,
				true,
				array('111', 222),
			),
			
			array(
				'
				  __0__
				 /     \
				1       2
				 \     /
				checkpoint
				',
				array(
					0 => 222,
					1 => '111',
					2 => 111,
					'checkpoint' => 111,
				),
				111,
				false,
				array(222, 222),
			),
		);
	}
	
	/**
	 * @dataProvider provider2
	 */
	public function testDiscardsIgnoredReturnValues($specTreePattern, $pluginValues, $ignoredReturnValue, $useStrictComparison, $expectedReturnValues) {
		$actualReturnValues = array();
		
		config::registerEventListener('onSpecRunStart', function(SpecInterface $spec) use(&$specs, &$actualReturnValues, &$ignoredReturnValue, &$useStrictComparison) {
			if ($spec === $specs["checkpoint"]) {
				$actualReturnValues[] = \spectrum\_internals\callMethodThroughRunningAncestorSpecs($spec, "getValue", array(), null, $ignoredReturnValue, $useStrictComparison);
			}
		});
		
		$specs = $this->createSpecsByVisualPattern($specTreePattern, array(), $this->createClass('
			class ... extends \spectrum\core\Spec {
				public $value = null;
				
				public function getValue() {
					return $this->value;
				}
			}
		'));
		
		foreach ($pluginValues as $specKey => $pluginValue) {
			$specs[$specKey]->value = $pluginValue;
		}
		
		$specs[0]->run();
		
		$this->assertSame($expectedReturnValues, $actualReturnValues);
	}
	
	public function testProperReturnValueIsNotFound_ReturnsDefaultReturnValue() {
		$actualReturnValues = array();
		
		config::registerEventListener('onSpecRunStart', function(SpecInterface $spec) use(&$specs, &$actualReturnValues) {
			if ($spec === $specs["checkpoint"]) {
				$actualReturnValues[] = \spectrum\_internals\callMethodThroughRunningAncestorSpecs($spec, "getValue", array(), "some text", null);
			}
		});
		
		$specs = $this->createSpecsByVisualPattern(
			'
				  __0__
				 /     \
				1       2
				 \     /
				checkpoint
			',
			array(),
			$this->createClass('
				class ... extends \spectrum\core\Spec {
					public $value = null;
					
					public function getValue() {
						return $this->value;
					}
				}
			')
		);
		
		$specs[0]->run();
		
		$this->assertSame(array('some text', 'some text'), $actualReturnValues);
	}
}