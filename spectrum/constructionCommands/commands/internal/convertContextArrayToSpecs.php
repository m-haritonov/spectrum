<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\commands\internal;

use spectrum\config;

function convertContextArrayToSpecs(array $contexts)
{
	$callBrokerClass = config::getConstructionCommandCallBrokerClass();
	$specClass = config::getSpecClass();
	$specs = array();
	
	foreach ($contexts as $specName => $dataRow)
	{
		if (!is_array($dataRow))
			$dataRow = array($dataRow);
		
		$spec = new $specClass();
		$spec->setName($callBrokerClass::internal_getNameForArguments($dataRow, $specName));
		$spec->contexts->add(function() use($dataRow){
			foreach ($dataRow as $propertyName => $value)
				this()->$propertyName = $value;
		}, 'before');
		
		$specs[] = $spec;
	}
	
	return $specs;
}