<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\_internal;

use spectrum\config;

function convertArrayWithContextsToSpecs(array $contexts)
{
	$specClass = config::getClassReplacement('\spectrum\core\Spec');
	$specs = array();
	
	foreach ($contexts as $title => $values)
	{
		if (!is_array($values))
			$values = array($values);

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
		$spec->contexts->add(function() use($values){
			$getRunningEndingSpecFunction = config::getFunctionReplacement('\spectrum\_internal\getRunningEndingSpec');
			$contextData = $getRunningEndingSpecFunction()->contexts->getContextData();
			foreach ($values as $propertyName => $value)
				$contextData->$propertyName = $value;
		}, 'before');
		
		$specs[] = $spec;
	}
	
	return $specs;
}