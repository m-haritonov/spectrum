<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\commands\internal;

function getArgumentsForTestCommand(array $arguments)
{
	$isClosure = function($variable){
		return is_object($variable) && ($variable instanceof \Closure);
	};
	
	$argumentCount = count($arguments);
	
	if ($argumentCount == 1) // test(scalar $name)
	{
		return array(
			'name' => $arguments[0],
			'multiplier' => null,
			'testFunction' => null,
			'settings' => null,
		);
	}
	else if ($argumentCount == 2 && is_array($arguments[1])) // test(scalar $name, array $settings)
	{
		return array(
			'name' => $arguments[0],
			'multiplier' => null,
			'testFunction' => null,
			'settings' => $arguments[1],
		);
	}
	else if ($argumentCount == 2 && $isClosure($arguments[1])) // test(scalar $name, \Closure $testFunction)
	{
		return array(
			'name' => $arguments[0],
			'multiplier' => null,
			'testFunction' => $arguments[1],
			'settings' => null,
		);
	}
	else if ($argumentCount == 3 && $isClosure($arguments[1]) && is_array($arguments[2])) // test(scalar $name, \Closure $testFunction, array $settings)
	{
		return array(
			'name' => $arguments[0],
			'multiplier' => null,
			'testFunction' => $arguments[1],
			'settings' => $arguments[2],
		);
	}
	else if ($argumentCount == 3 && $isClosure($arguments[1]) && $isClosure($arguments[2])) // test(scalar $name, \Closure $multiplier, \Closure $testFunction)
	{
		return array(
			'name' => $arguments[0],
			'multiplier' => $arguments[1],
			'testFunction' => $arguments[2],
			'settings' => null,
		);
	}
	else if ($argumentCount == 3 && is_array($arguments[1]) && $isClosure($arguments[2])) // test(scalar $name, array $multiplier, \Closure $testFunction)
	{
		return array(
			'name' => $arguments[0],
			'multiplier' => $arguments[1],
			'testFunction' => $arguments[2],
			'settings' => null,
		);
	}
	else if ($argumentCount == 4 && $isClosure($arguments[1]) && $isClosure($arguments[2]) && is_array($arguments[3])) // test(scalar $name, \Closure $multiplier, \Closure $testFunction, array $settings)
	{
		return array(
			'name' => $arguments[0],
			'multiplier' => $arguments[1],
			'testFunction' => $arguments[2],
			'settings' => $arguments[3],
		);
	}
	else if ($argumentCount == 4 && is_array($arguments[1]) && $isClosure($arguments[2]) && is_array($arguments[3])) // test(scalar $name, array $multiplier, \Closure $testFunction, array $settings)
	{
		return array(
			'name' => $arguments[0],
			'multiplier' => $arguments[1],
			'testFunction' => $arguments[2],
			'settings' => $arguments[3],
		);
	}
	else
		return false;
}