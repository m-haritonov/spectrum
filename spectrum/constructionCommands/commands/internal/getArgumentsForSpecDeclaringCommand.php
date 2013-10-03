<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\commands\internal;

function getArgumentsForSpecDeclaringCommand($storage, array $arguments)
{
	$receiveArguments = array(
		array('scalar:name'),                                        // function(scalar $name)
		array('closure:body'),                                       // function(\Closure $body)
		array('array:settings'),                                     // function(array $settings)
		array('scalar:name', 'closure:body'),                        // function(scalar $name, \Closure $body)
		array('scalar:name', 'array:settings'),                      // function(scalar $name, array $settings)
		array('closure:contexts', 'closure:body'),                   // function(\Closure $contexts, \Closure $body)
		array('array:contexts', 'closure:body'),                     // function(array $contexts, \Closure $body)
		array('closure:body', 'array:settings'),                     // function(\Closure $body, array $settings)
		array('scalar:name', 'closure:contexts', 'closure:body'),    // function(scalar $name, \Closure $contexts, \Closure $body)
		array('scalar:name', 'array:contexts', 'closure:body'),      // function(scalar $name, array $contexts, \Closure $body)
		array('scalar:name', 'closure:body', 'array:settings'),      // function(scalar $name, \Closure $body, array $settings)
		array('closure:contexts', 'closure:body', 'array:settings'), // function(\Closure $contexts, \Closure $body, array $settings)
		array('array:contexts', 'closure:body', 'array:settings'),   // function(array $contexts, \Closure $body, array $settings)
	);
			
	$arguments = array_values($arguments);
	$argumentCount = count($arguments);
	foreach ($receiveArguments as $receiveArgumentRow)
	{
		if ($argumentCount == count($receiveArgumentRow))
		{
			$result = array(
				'name' => null,
				'contexts' => null,
				'body' => null,
				'settings' => null,
			);
			
			foreach ($receiveArgumentRow as $num => $receiveArgument)
			{
				list($type, $name) = explode(':', $receiveArgument);
				
				if ($type == 'scalar' && is_scalar($arguments[$num]) || $type == 'array' && is_array($arguments[$num]) || $type == 'closure' && is_object($arguments[$num]) && $arguments[$num] instanceof \Closure)
					$result[$name] = $arguments[$num];
				else
					continue(2);
			}
			
			return $result;
		}
	}
	
	return array(
		'name' => @$arguments[0],
		'contexts' => @$arguments[1],
		'body' => @$arguments[2],
		'settings' => @$arguments[3],
	);
}