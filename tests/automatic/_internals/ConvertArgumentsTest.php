<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\tests\automatic\_internals;

require_once __DIR__ . '/../../init.php';

class ConvertArgumentsTest extends \spectrum\tests\automatic\Test {
	public function providerCorrectArguments() {
		$function1 = function(){};
		$function2 = function(){};
		
		return array(
			
			// function(\Closure $body)
			
			array(
				array(null, null, $function1, null), 
				array($function1),
			),
			
			// function(\Closure $body, null|scalar|array $settings)
			
			array(
				array(null, null, $function1, null),
				array($function1, null),
			),
			
			array(
				array(null, null, $function1, 'aaa'),
				array($function1, 'aaa'),
			),
			
			array(
				array(null, null, $function1, array('aaa' => '111', 'bbb' => '222')),
				array($function1, array('aaa' => '111', 'bbb' => '222')),
			),
			
			// function(array|\Closure $contexts, \Closure $body)
			
			array(
				array(null, array('aaa', 'bbb'), $function2, null),
				array(array('aaa', 'bbb'), $function2),
			),
			
			array(
				array(null, $function1, $function2, null),
				array($function1, $function2),
			),
			
			// function(array|\Closure $contexts, \Closure $body, null|scalar|array $settings)
			
			array(
				array(null, array('aaa', 'bbb'), $function1, null),
				array(array('aaa', 'bbb'), $function1, null),
			),
			
			array(
				array(null, array('aaa', 'bbb'), $function1, 'aaa'),
				array(array('aaa', 'bbb'), $function1, 'aaa'),
			),
			
			array(
				array(null, array('aaa', 'bbb'), $function1, array('ccc' => '111', 'ddd' => '222')),
				array(array('aaa', 'bbb'), $function1, array('ccc' => '111', 'ddd' => '222')),
			),
			
			//
			
			array(
				array(null, $function1, $function2, null),
				array($function1, $function2, null),
			),
			
			array(
				array(null, $function1, $function2, 'aaa'),
				array($function1, $function2, 'aaa'),
			),
			
			array(
				array(null, $function1, $function2, array('ccc' => '111', 'ddd' => '222')),
				array($function1, $function2, array('ccc' => '111', 'ddd' => '222')),
			),
			
			// function(null|scalar $name, \Closure $body)
			
			array(
				array(null, null, $function1, null),
				array(null, $function1),
			),
			
			array(
				array('aaa', null, $function1, null),
				array('aaa', $function1),
			),
			
			// function(null|scalar $name, \Closure $body, null|scalar|array $settings)
			
			array(
				array(null, null, $function1, null),
				array(null, $function1, null),
			),
			
			array(
				array(null, null, $function1, 'aaa'),
				array(null, $function1, 'aaa'),
			),
			
			array(
				array(null, null, $function1, array('ccc' => '111', 'ddd' => '222')),
				array(null, $function1, array('ccc' => '111', 'ddd' => '222')),
			),
			
			//
			
			array(
				array('some name', null, $function1, null),
				array('some name', $function1, null),
			),
			
			array(
				array('some name', null, $function1, 'aaa'),
				array('some name', $function1, 'aaa'),
			),
			
			array(
				array('some name', null, $function1, array('ccc' => '111', 'ddd' => '222')),
				array('some name', $function1, array('ccc' => '111', 'ddd' => '222')),
			),
			
			// function(null|scalar $name, null|array|\Closure $contexts, \Closure $body)
			
			array(
				array(null, null, $function1, null),
				array(null, null, $function1),
			),
			
			array(
				array(null, array('aaa', 'bbb'), $function1, null),
				array(null, array('aaa', 'bbb'), $function1),
			),
			
			array(
				array(null, $function1, $function2, null),
				array(null, $function1, $function2),
			),
			
			//
			
			array(
				array('some name', null, $function1, null),
				array('some name', null, $function1),
			),
			
			array(
				array('some name', array('aaa', 'bbb'), $function1, null),
				array('some name', array('aaa', 'bbb'), $function1),
			),
			
			array(
				array('some name', $function1, $function2, null),
				array('some name', $function1, $function2),
			),
			
			// function(null|scalar $name, null|array|\Closure $contexts, \Closure $body, null|scalar|array $settings)
			
			array(
				array(null, null, $function1, null),
				array(null, null, $function1, null),
			),
			
			array(
				array(null, null, $function1, 'ccc'),
				array(null, null, $function1, 'ccc'),
			),
			
			array(
				array(null, null, $function1, array('ddd' => '111', 'eee' => '222')),
				array(null, null, $function1, array('ddd' => '111', 'eee' => '222')),
			),
			
			//
			
			array(
				array(null, array('aaa', 'bbb'), $function1, null),
				array(null, array('aaa', 'bbb'), $function1, null),
			),
			
			array(
				array(null, array('aaa', 'bbb'), $function1, 'ccc'),
				array(null, array('aaa', 'bbb'), $function1, 'ccc'),
			),
			
			array(
				array(null, array('aaa', 'bbb'), $function1, array('ddd' => '111', 'eee' => '222')),
				array(null, array('aaa', 'bbb'), $function1, array('ddd' => '111', 'eee' => '222')),
			),
			
			//
			
			array(
				array(null, $function1, $function2, null),
				array(null, $function1, $function2, null),
			),
			
			array(
				array(null, $function1, $function2, 'ccc'),
				array(null, $function1, $function2, 'ccc'),
			),
			
			array(
				array(null, $function1, $function2, array('ddd' => '111', 'eee' => '222')),
				array(null, $function1, $function2, array('ddd' => '111', 'eee' => '222')),
			),
			
			//
			
			array(
				array('some name', null, $function1, null),
				array('some name', null, $function1, null),
			),
			
			array(
				array('some name', null, $function1, 'ccc'),
				array('some name', null, $function1, 'ccc'),
			),
			
			array(
				array('some name', null, $function1, array('ddd' => '111', 'eee' => '222')),
				array('some name', null, $function1, array('ddd' => '111', 'eee' => '222')),
			),
			
			//
			
			array(
				array('some name', array('aaa', 'bbb'), $function1, null),
				array('some name', array('aaa', 'bbb'), $function1, null),
			),
			
			array(
				array('some name', array('aaa', 'bbb'), $function1, 'ccc'),
				array('some name', array('aaa', 'bbb'), $function1, 'ccc'),
			),
			
			array(
				array('some name', array('aaa', 'bbb'), $function1, array('ddd' => '111', 'eee' => '222')),
				array('some name', array('aaa', 'bbb'), $function1, array('ddd' => '111', 'eee' => '222')),
			),
			
			//
			
			array(
				array('some name', $function1, $function2, null),
				array('some name', $function1, $function2, null),
			),
			
			array(
				array('some name', $function1, $function2, 'ccc'),
				array('some name', $function1, $function2, 'ccc'),
			),
			
			array(
				array('some name', $function1, $function2, array('ddd' => '111', 'eee' => '222')),
				array('some name', $function1, $function2, array('ddd' => '111', 'eee' => '222')),
			),
		);
	}

	/**
	 * @dataProvider providerCorrectArguments
	 */
	public function testCallsAtBuildingState_PassedArgumentsIsCorrect_ReturnsArrayWith4Elements($expectedArguments, $passedArguments) {
		$this->assertSame(
			$expectedArguments,
			\spectrum\_internals\convertArguments(
				$passedArguments,
				array(
					array('closure:body'),                                                                                  // function(\Closure $body)
					array('closure:body', 'null|scalar|array:settings'),                                                    // function(\Closure $body, null|scalar|array $settings)
					array('array|closure:contexts', 'closure:body'),                                                        // function(array|\Closure $contexts, \Closure $body)
					array('array|closure:contexts', 'closure:body', 'null|scalar|array:settings'),                          // function(array|\Closure $contexts, \Closure $body, null|scalar|array $settings)
					array('null|scalar:name', 'closure:body'),                                                              // function(null|scalar $name, \Closure $body)
					array('null|scalar:name', 'closure:body', 'null|scalar|array:settings'),                                // function(null|scalar $name, \Closure $body, null|scalar|array $settings)
					array('null|scalar:name', 'null|array|closure:contexts', 'closure:body'),                               // function(null|scalar $name, null|array|\Closure $contexts, \Closure $body)
					array('null|scalar:name', 'null|array|closure:contexts', 'closure:body', 'null|scalar|array:settings'), // function(null|scalar $name, null|array|\Closure $contexts, \Closure $body, null|scalar|array $settings)
				),
				array(
					'name' => null,
					'contexts' => null,
					'body' => null,
					'settings' => null,
				)
			)
		);
	}
	
	public function providerWrongArguments() {
		return array(
			array(array(null, null, null)),
			array(array(null, null, null, null)),
			array(array('aaa', 'aaa', 'aaa', 'aaa')),
		);
	}

	/**
	 * @dataProvider providerWrongArguments
	 */
	public function testCallsAtBuildingState_PassedArgumentsIsWrong_ReturnsNull($passedArguments) {
		$this->assertSame(
			null,
			\spectrum\_internals\convertArguments(
				$passedArguments,
				array(
					array('closure:body'),                                                                                  // function(\Closure $body)
					array('closure:body', 'null|scalar|array:settings'),                                                    // function(\Closure $body, null|scalar|array $settings)
					array('array|closure:contexts', 'closure:body'),                                                        // function(array|\Closure $contexts, \Closure $body)
					array('array|closure:contexts', 'closure:body', 'null|scalar|array:settings'),                          // function(array|\Closure $contexts, \Closure $body, null|scalar|array $settings)
					array('null|scalar:name', 'closure:body'),                                                              // function(null|scalar $name, \Closure $body)
					array('null|scalar:name', 'closure:body', 'null|scalar|array:settings'),                                // function(null|scalar $name, \Closure $body, null|scalar|array $settings)
					array('null|scalar:name', 'null|array|closure:contexts', 'closure:body'),                               // function(null|scalar $name, null|array|\Closure $contexts, \Closure $body)
					array('null|scalar:name', 'null|array|closure:contexts', 'closure:body', 'null|scalar|array:settings'), // function(null|scalar $name, null|array|\Closure $contexts, \Closure $body, null|scalar|array $settings)
				),
				array(
					'name' => null,
					'contexts' => null,
					'body' => null,
					'settings' => null,
				)
			)
		);
	}
}