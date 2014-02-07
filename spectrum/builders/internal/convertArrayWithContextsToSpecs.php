<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\builders\internal;

use spectrum\config;

function convertArrayWithContextsToSpecs(array $contexts, $inputCharset)
{
	$specClass = config::getSpecClass();
	$specs = array();
	
	foreach ($contexts as $title => $values)
	{
		if (!is_array($values))
			$values = array($values);

		$firstValue = reset($values);
		if ((!is_string($title) || $title === '') && count($values) >= 1 && is_scalar($firstValue))
		{
			if (mb_strlen($firstValue) > 100)
				$title = mb_substr($firstValue, 0, 100) . '...';
			else
				$title = $firstValue;
		}
		
		$spec = new $specClass();
		$spec->setInputCharset($inputCharset);
		$spec->setName($title);
		$spec->contexts->add(function() use($values){
			foreach ($values as $propertyName => $value)
				\spectrum\builders\this()->$propertyName = $value;
		}, 'before');
		
		$specs[] = $spec;
	}
	
	return $specs;
}