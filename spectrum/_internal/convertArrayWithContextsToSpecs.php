<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\_internal;

use spectrum\config;
use spectrum\Exception;

function convertArrayWithContextsToSpecs(array $contexts)
{
	$specClass = config::getClassReplacement('\spectrum\core\Spec');
	$specs = array();
	
	$num = 0;
	foreach ($contexts as $title => $values)
	{
		$num++;
		
		if (!is_array($values))
			throw new Exception('The context row #' . $num . ' should be an array');

		$firstValue = reset($values);
		if ((!is_string($title) || $title === '') && count($values) >= 1 && is_scalar($firstValue))
		{
			if (mb_strlen($firstValue, config::getInputCharset()) > 100)
				$title = mb_substr($firstValue, 0, 100, config::getInputCharset()) . '...';
			else
				$title = $firstValue;
		}
		
		$spec = new $specClass();
		$spec->setName($title);
		$spec->contextModifiers->add(function() use($values){
			$getContextDataFunction = config::getFunctionReplacement('\spectrum\_internal\getContextData');
			$contextData = $getContextDataFunction();
			foreach ($values as $propertyName => $value)
				$contextData->$propertyName = $value;
		}, 'before');
		
		$specs[] = $spec;
	}
	
	return $specs;
}