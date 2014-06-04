<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internal;

use spectrum\config;
use spectrum\Exception;

/**
 * @access private
 */
function convertArrayWithContextsToSpecs(array $contexts)
{
	$specClass = config::getClassReplacement('\spectrum\core\Spec');
	$specs = array();
	$closure = function(){};
	
	$num = 0;
	foreach ($contexts as $title => $values)
	{
		$num++;
		if (is_array($values))
		{
			$firstValue = reset($values);
			if ((!is_string($title) || $title === '') && count($values) >= 1 && is_scalar($firstValue))
			{
				if (mb_strlen($firstValue, config::getInputCharset()) > 100)
					$title = mb_substr($firstValue, 0, 100, config::getInputCharset()) . '...';
				else
					$title = $firstValue;
			}
			
			$contextModifierFunction = function() use($values){
				$getCurrentDataFunction = config::getFunctionReplacement('\spectrum\_internal\getCurrentData');
				$data = $getCurrentDataFunction();
				foreach ($values as $propertyName => $value)
					$data->$propertyName = $value;
			};
		}
		else if ($values instanceof $closure)
			$contextModifierFunction = $values;
		else
			throw new Exception('The context row #' . $num . ' should be an array');
		
		$spec = new $specClass();
		$spec->setName($title);
		$spec->contextModifiers->add($contextModifierFunction, 'before');
		$specs[] = $spec;
	}
	
	return $specs;
}