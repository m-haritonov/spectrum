<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\core\_private;

use spectrum\core\config;
use spectrum\core\SpecInterface;

require_once __DIR__ . '/../../../init.php';

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
		$specs = \spectrum\tests\_testware\tools::createSpecsByVisualPattern($specVisualPattern, array(), \spectrum\tests\_testware\tools::createClass('
			class ... extends \spectrum\core\Spec {
				public $value = null;
				
				public function getValue() {
					return $this->value;
				}
			}
		'));
		
		config::registerEventListener('onSpecRunStart', function(SpecInterface $spec) use(&$specs, &$actualReturnValues) {
			if ($spec === $specs["checkpoint"]) {
				$actualReturnValues[] = \spectrum\core\_private\callMethodThroughRunningAncestorSpecs($spec, "getValue");
			}
		});
		
		foreach ($values as $specKey => $pluginValue) {
			$specs[$specKey]->value = $pluginValue;
		}
		
		$specs[0]->run();
		
		$this->assertSame($expectedReturnValues, $actualReturnValues);
	}
	
	public function testPassesArgumentsToCalleeMethod() {
		\spectrum\tests\_testware\tools::$temp["passedArguments"] = array();
		
		config::registerEventListener('onSpecRunStart', function(SpecInterface $spec) {
			\spectrum\core\_private\callMethodThroughRunningAncestorSpecs($spec, "getValue", array("aaa", "bbb", "ccc"));
		});
		
		$specClass = \spectrum\tests\_testware\tools::createClass('
			class ... extends \spectrum\core\Spec {
				public $value = null;
				
				public function getValue() {
					\spectrum\tests\_testware\tools::$temp["passedArguments"][] = func_get_args();
					return $this->value;
				}
			}
		');
		
		/** @var SpecInterface $spec */
		$spec = new $specClass();
		$spec->run();
		
		$this->assertSame(array(array("aaa", "bbb", "ccc")), \spectrum\tests\_testware\tools::$temp["passedArguments"]);
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
				$actualReturnValues[] = \spectrum\core\_private\callMethodThroughRunningAncestorSpecs($spec, "getValue", array(), null, $ignoredReturnValue, $useStrictComparison);
			}
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByVisualPattern($specTreePattern, array(), \spectrum\tests\_testware\tools::createClass('
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
				$actualReturnValues[] = \spectrum\core\_private\callMethodThroughRunningAncestorSpecs($spec, "getValue", array(), "some text", null);
			}
		});
		
		$specs = \spectrum\tests\_testware\tools::createSpecsByVisualPattern(
			'
				  __0__
				 /     \
				1       2
				 \     /
				checkpoint
			',
			array(),
			\spectrum\tests\_testware\tools::createClass('
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