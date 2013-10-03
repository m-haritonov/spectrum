<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\constructionCommands\commands\internal;

use spectrum\constructionCommands\callBroker;

require_once __DIR__ . '/../../../init.php';

class GetArgumentsForSpecDeclaringCommandTest extends \spectrum\tests\Test
{
	public function provider()
	{
		$function1 = function(){};
		$function2 = function(){};
		
		return array(
			// function()
			array(
				array('name' => null, 'contexts' => null, 'body' => null, 'settings' => null), 
				array(),
			),
			
			// function(null)
			array(
				array('name' => null, 'contexts' => null, 'body' => null, 'settings' => null), 
				array(null),
			),
			
			// function(null, null)
			array(
				array('name' => null, 'contexts' => null, 'body' => null, 'settings' => null), 
				array(null, null),
			),
			
			// function(null, null, null)
			array(
				array('name' => null, 'contexts' => null, 'body' => null, 'settings' => null), 
				array(null, null, null),
			),
			
			// function(null, null, null, null)
			array(
				array('name' => null, 'contexts' => null, 'body' => null, 'settings' => null), 
				array(null, null, null, null),
			),
			
		/**/
			
			// function(null, \Closure $contexts, \Closure $body, array $settings)
			array(
				array('name' => null, 'contexts' => $function1, 'body' => $function2, 'settings' => array('bbb' => 'ccc')), 
				array(null, $function1, $function2, array('bbb' => 'ccc')),
			),
			
			// function(null, null, \Closure $body, array $settings)
			array(
				array('name' => null, 'contexts' => null, 'body' => $function2, 'settings' => array('bbb' => 'ccc')), 
				array(null, null, $function2, array('bbb' => 'ccc')),
			),
			
			// function(null, null, null, array $settings)
			array(
				array('name' => null, 'contexts' => null, 'body' => null, 'settings' => array('bbb' => 'ccc')), 
				array(null, null, null, array('bbb' => 'ccc')),
			),
			
		/**/
			
			// function(scalar $name)
			array(
				array('name' => 'aaa', 'contexts' => null, 'body' => null, 'settings' => null), 
				array('aaa'),
			),
			
			// function(\Closure $body)
			array(
				array('name' => null, 'contexts' => null, 'body' => $function1, 'settings' => null), 
				array($function1),
			),
			
			// function(array $settings)
			array(
				array('name' => null, 'contexts' => null, 'body' => null, 'settings' => array('aaa' => 'bbb')), 
				array(array('aaa' => 'bbb')),
			),
			
			// function(scalar $name, \Closure $body)
			array(
				array('name' => 'aaa', 'contexts' => null, 'body' => $function1, 'settings' => null), 
				array('aaa', $function1),
			),
			
			// function(scalar $name, array $settings)
			array(
				array('name' => 'aaa', 'contexts' => null, 'body' => null, 'settings' => array('bbb' => 'ccc')), 
				array('aaa', array('bbb' => 'ccc')),
			),
			
			// function(\Closure $contexts, \Closure $body)
			array(
				array('name' => null, 'contexts' => $function1, 'body' => $function2, 'settings' => null), 
				array($function1, $function2),
			),
			
			// function(array $contexts, \Closure $body)
			array(
				array('name' => null, 'contexts' => array('aaa', 'bbb'), 'body' => $function1, 'settings' => null), 
				array(array('aaa', 'bbb'), $function1),
			),
			
			// function(\Closure $body, array $settings)
			array(
				array('name' => null, 'contexts' => null, 'body' => $function1, 'settings' => array('aaa' => 'bbb')), 
				array($function1, array('aaa' => 'bbb')),
			),
			
			// function(scalar $name, \Closure $contexts, \Closure $body)
			array(
				array('name' => 'aaa', 'contexts' => $function1, 'body' => $function2, 'settings' => null), 
				array('aaa', $function1, $function2),
			),
			
			// function(scalar $name, array $contexts, \Closure $body)
			array(
				array('name' => 'aaa', 'contexts' => array('bbb', 'ccc'), 'body' => $function1, 'settings' => null), 
				array('aaa', array('bbb', 'ccc'), $function1),
			),
			
			// function(scalar $name, \Closure $body, array $settings)
			array(
				array('name' => 'aaa', 'contexts' => null, 'body' => $function1, 'settings' => array('bbb' => 'ccc')), 
				array('aaa', $function1, array('bbb' => 'ccc')),
			),
			
			// function(\Closure $contexts, \Closure $body, array $settings)
			array(
				array('name' => null, 'contexts' => $function1, 'body' => $function2, 'settings' => array('aaa' => 'bbb')), 
				array($function1, $function2, array('aaa' => 'bbb')),
			),
			
			// function(array $contexts, \Closure $body, array $settings)
			array(
				array('name' => null, 'contexts' => array('aaa', 'bbb'), 'body' => $function1, 'settings' => array('ccc' => 'ddd')), 
				array(array('aaa', 'bbb'), $function1, array('ccc' => 'ddd')),
			),
			
			// function(scalar $name, \Closure $contexts, \Closure $body, array $settings)
			array(
				array('name' => 'aaa', 'contexts' => $function1, 'body' => $function2, 'settings' => array('bbb' => 'ccc')), 
				array('aaa', $function1, $function2, array('bbb' => 'ccc')),
			),
			
			// function(scalar $name, array $contexts, \Closure $body, array $settings)
			array(
				array('name' => 'aaa', 'contexts' => array('bbb', 'ccc'), 'body' => $function1, 'settings' => array('ddd' => 'eee')), 
				array('aaa', array('bbb', 'ccc'), $function1, array('ddd' => 'eee')),
			),
			
		/**/
			
			array(
				array('name' => 'aaa', 'contexts' => 'aaa', 'body' => 'aaa', 'settings' => 'aaa'), 
				array('aaa', 'aaa', 'aaa', 'aaa'),
			),
		);
	}

	/**
	 * @dataProvider provider
	 */
	public function testCallsAtDeclaringState_ReturnsArrayWith4Elements($exceptedArguments, $passedArguments)
	{
		$this->assertSame($exceptedArguments, callBroker::internal_getArgumentsForSpecDeclaringCommand($passedArguments));
	}
}