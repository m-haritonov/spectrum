<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\tests\constructionCommands\commands\internal;

use spectrum\constructionCommands\callBroker;

require_once __DIR__ . '/../../../init.php';

class GetNameForArgumentsTest extends \spectrum\tests\Test
{
	public function provider()
	{
		return array(
			array('aaa', array('aaa'), null),
			array('aaa', array('aaa'), 0),
			array('aaa', array('aaa'), 1),
			array('aaa', array('aaa', 'bbb'), 1),
			
			array(123, array(123), null),
			array(123, array(123), 0),
			array(123, array(123), 1),
			array(123, array(123, 'aaa'), 1),
			
			array('bbb', array('aaa'), 'bbb'),
			array('123', array('aaa'), '123'),
			
			array(str_repeat('a', 100), array(str_repeat('a', 100)), null),
			array(str_repeat('a', 100) . '...', array(str_repeat('a', 101)), null),
			array(str_repeat('a', 100) . '...', array(str_repeat('a', 200)), null),
		);
	}
	
	/**
	 * @dataProvider provider
	 */
	public function testCallsAtDeclaringState_ReturnsProperName($expectedName, $arguments, $defaultName)
	{
		$this->assertSame($expectedName, callBroker::internal_getNameForArguments($arguments, $defaultName));
	}
}