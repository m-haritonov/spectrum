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
		array('closure:body'),                                                                                  // function(\Closure $body)
		array('closure:body', 'null|scalar|array:settings'),                                                    // function(\Closure $body, null|scalar|array $settings)
		array('array|closure:contexts', 'closure:body'),                                                        // function(array|\Closure $contexts, \Closure $body)
		array('array|closure:contexts', 'closure:body', 'null|scalar|array:settings'),                          // function(array|\Closure $contexts, \Closure $body, null|scalar|array $settings)
		array('null|scalar:name', 'closure:body'),                                                              // function(null|scalar $name, \Closure $body)
		array('null|scalar:name', 'closure:body', 'null|scalar|array:settings'),                                // function(null|scalar $name, \Closure $body, null|scalar|array $settings)
		array('null|scalar:name', 'null|array|closure:contexts', 'closure:body'),                               // function(null|scalar $name, null|array|\Closure $contexts, \Closure $body)
		array('null|scalar:name', 'null|array|closure:contexts', 'closure:body', 'null|scalar|array:settings'), // function(null|scalar $name, null|array|\Closure $contexts, \Closure $body, null|scalar|array $settings)
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
				list($types, $name) = explode(':', $receiveArgument);
				$types = explode('|', $types);
				
				$isNull = (in_array('null', $types) && is_null($arguments[$num]));
				$isScalar = (in_array('scalar', $types) && is_scalar($arguments[$num]));
				$isArray = (in_array('array', $types) && is_array($arguments[$num]));
				$isClosure = (in_array('closure', $types) && is_object($arguments[$num]) && $arguments[$num] instanceof \Closure);
				
				if ($isNull || $isScalar || $isArray || $isClosure)
					$result[$name] = $arguments[$num];
				else
					continue(2);
			}
			
			return array_values($result);
		}
	}
	
	return null;
}