<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\commands\internal;

function getArgumentsForGroupCommand(array $arguments)
{
	$isClosure = function($variable){
		return is_object($variable) && ($variable instanceof \Closure);
	};
	
	$argumentCount = count($arguments);
	
	if ($argumentCount == 0) // group()
	{
		return array(
			'name' => null,
			'multiplier' => null,
			'function' => null,
			'settings' => null,
		);
	}
	else if ($argumentCount == 1 && is_scalar($arguments[0])) // group(scalar $name)
	{
		return array(
			'name' => $arguments[0],
			'multiplier' => null,
			'function' => null,
			'settings' => null,
		);
	}
	else if ($argumentCount == 2 && is_scalar($arguments[0]) && $isClosure($arguments[1])) // group(scalar $name, \Closure $function)
	{
		return array(
			'name' => $arguments[0],
			'multiplier' => null,
			'function' => $arguments[1],
			'settings' => null,
		);
	}
	else if ($argumentCount == 3 && is_scalar($arguments[0]) && $isClosure($arguments[1]) && $isClosure($arguments[2])) // group(scalar $name, \Closure $multiplier, \Closure $function)
	{
		return array(
			'name' => $arguments[0],
			'multiplier' => $arguments[1],
			'function' => $arguments[2],
			'settings' => null,
		);
	}
	else if ($argumentCount == 3 && is_scalar($arguments[0]) && $isClosure($arguments[1]) && is_array($arguments[2])) // group(scalar $name, \Closure $function, array $settings)
	{
		return array(
			'name' => $arguments[0],
			'multiplier' => null,
			'function' => $arguments[1],
			'settings' => $arguments[2],
		);
	}
	else if ($argumentCount == 4 && is_scalar($arguments[0]) && $isClosure($arguments[1]) && $isClosure($arguments[2]) && is_array($arguments[3])) // group(scalar $name, \Closure $multiplier, \Closure $function, array $settings)
	{
		return array(
			'name' => $arguments[0],
			'multiplier' => $arguments[1],
			'function' => $arguments[2],
			'settings' => $arguments[3],
		);
	}
	else if ($argumentCount == 2 && is_scalar($arguments[0]) && is_array($arguments[1])) // group(scalar $name, array $settings)
	{
		return array(
			'name' => $arguments[0],
			'multiplier' => null,
			'function' => null,
			'settings' => $arguments[1],
		);
	}
	else if ($argumentCount == 1 && $isClosure($arguments[0])) // group(\Closure $function)
	{
		return array(
			'name' => null,
			'multiplier' => null,
			'function' => $arguments[0],
			'settings' => null,
		);
	}
	else if ($argumentCount == 2 && $isClosure($arguments[0]) && $isClosure($arguments[1])) // group(\Closure $multiplier, \Closure $function)
	{
		return array(
			'name' => null,
			'multiplier' => $arguments[0],
			'function' => $arguments[1],
			'settings' => null,
		);
	}
	else if ($argumentCount == 2 && $isClosure($arguments[0]) && is_array($arguments[1])) // group(\Closure $function, array $settings)
	{
		return array(
			'name' => null,
			'multiplier' => null,
			'function' => $arguments[0],
			'settings' => $arguments[1],
		);
	}
	else if ($argumentCount == 3 && $isClosure($arguments[0]) && $isClosure($arguments[1]) && is_array($arguments[2])) // group(\Closure $multiplier, \Closure $function, array $settings)
	{
		return array(
			'name' => null,
			'multiplier' => $arguments[0],
			'function' => $arguments[1],
			'settings' => $arguments[2],
		);
	}
	else if ($argumentCount == 1 && is_array($arguments[0])) // group(array $settings)
	{
		return array(
			'name' => null,
			'multiplier' => null,
			'function' => null,
			'settings' => $arguments[0],
		);
	}
	else
		return false;
}