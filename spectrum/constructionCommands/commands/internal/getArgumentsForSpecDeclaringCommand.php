<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\commands\internal;

function getArgumentsForSpecDeclaringCommand(array $arguments)
{
	$isClosure = function($variable){
		return is_object($variable) && ($variable instanceof \Closure);
	};
	
	$argumentCount = count($arguments);
	
	if ($argumentCount == 0) // function()
	{
		return array(
			'name' => null,
			'contexts' => null,
			'body' => null,
			'settings' => null,
		);
	}
	else if ($argumentCount == 1 && is_scalar($arguments[0])) // function(scalar $name)
	{
		return array(
			'name' => $arguments[0],
			'contexts' => null,
			'body' => null,
			'settings' => null,
		);
	}
	else if ($argumentCount == 1 && $isClosure($arguments[0])) // function(\Closure $body)
	{
		return array(
			'name' => null,
			'contexts' => null,
			'body' => $arguments[0],
			'settings' => null,
		);
	}
	else if ($argumentCount == 1 && is_array($arguments[0])) // function(array $settings)
	{
		return array(
			'name' => null,
			'contexts' => null,
			'body' => null,
			'settings' => $arguments[0],
		);
	}
	else if ($argumentCount == 2 && is_scalar($arguments[0]) && $isClosure($arguments[1])) // function(scalar $name, \Closure $body)
	{
		return array(
			'name' => $arguments[0],
			'contexts' => null,
			'body' => $arguments[1],
			'settings' => null,
		);
	}
	else if ($argumentCount == 2 && is_scalar($arguments[0]) && is_array($arguments[1])) // function(scalar $name, array $settings)
	{
		return array(
			'name' => $arguments[0],
			'contexts' => null,
			'body' => null,
			'settings' => $arguments[1],
		);
	}
	else if ($argumentCount == 2 && $isClosure($arguments[0]) && $isClosure($arguments[1])) // function(\Closure $contexts, \Closure $body)
	{
		return array(
			'name' => null,
			'contexts' => $arguments[0],
			'body' => $arguments[1],
			'settings' => null,
		);
	}
	else if ($argumentCount == 2 && is_array($arguments[0]) && $isClosure($arguments[1])) // function(array $contexts, \Closure $body)
	{
		return array(
			'name' => null,
			'contexts' => $arguments[0],
			'body' => $arguments[1],
			'settings' => null,
		);
	}
	else if ($argumentCount == 2 && $isClosure($arguments[0]) && is_array($arguments[1])) // function(\Closure $body, array $settings)
	{
		return array(
			'name' => null,
			'contexts' => null,
			'body' => $arguments[0],
			'settings' => $arguments[1],
		);
	}
	else if ($argumentCount == 3 && is_scalar($arguments[0]) && $isClosure($arguments[1]) && $isClosure($arguments[2])) // function(scalar $name, \Closure $contexts, \Closure $body)
	{
		return array(
			'name' => $arguments[0],
			'contexts' => $arguments[1],
			'body' => $arguments[2],
			'settings' => null,
		);
	}
	else if ($argumentCount == 3 && is_scalar($arguments[0]) && is_array($arguments[1]) && $isClosure($arguments[2])) // function(scalar $name, array $contexts, \Closure $body)
	{
		return array(
			'name' => $arguments[0],
			'contexts' => $arguments[1],
			'body' => $arguments[2],
			'settings' => null,
		);
	}
	else if ($argumentCount == 3 && is_scalar($arguments[0]) && $isClosure($arguments[1]) && is_array($arguments[2])) // function(scalar $name, \Closure $body, array $settings)
	{
		return array(
			'name' => $arguments[0],
			'contexts' => null,
			'body' => $arguments[1],
			'settings' => $arguments[2],
		);
	}
	else if ($argumentCount == 3 && $isClosure($arguments[0]) && $isClosure($arguments[1]) && is_array($arguments[2])) // function(\Closure $contexts, \Closure $body, array $settings)
	{
		return array(
			'name' => null,
			'contexts' => $arguments[0],
			'body' => $arguments[1],
			'settings' => $arguments[2],
		);
	}
	else if ($argumentCount == 3 && is_array($arguments[0]) && $isClosure($arguments[1]) && is_array($arguments[2])) // function(array $contexts, \Closure $body, array $settings)
	{
		return array(
			'name' => null,
			'contexts' => $arguments[0],
			'body' => $arguments[1],
			'settings' => $arguments[2],
		);
	}
	else if ($argumentCount == 4 && is_scalar($arguments[0]) && $isClosure($arguments[1]) && $isClosure($arguments[2]) && is_array($arguments[3])) // function(scalar $name, \Closure $contexts, \Closure $body, array $settings)
	{
		return array(
			'name' => $arguments[0],
			'contexts' => $arguments[1],
			'body' => $arguments[2],
			'settings' => $arguments[3],
		);
	}
	else if ($argumentCount == 4 && is_scalar($arguments[0]) && is_array($arguments[1]) && $isClosure($arguments[2]) && is_array($arguments[3])) // function(scalar $name, array $contexts, \Closure $body, array $settings)
	{
		return array(
			'name' => $arguments[0],
			'contexts' => $arguments[1],
			'body' => $arguments[2],
			'settings' => $arguments[3],
		);
	}
	else
		return null;
}