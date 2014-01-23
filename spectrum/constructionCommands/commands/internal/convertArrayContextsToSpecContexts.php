<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\constructionCommands\commands\internal;

use spectrum\config;

function convertArrayContextsToSpecContexts($storage, array $contexts)
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
			$callBrokerClass = config::getConstructionCommandCallBrokerClass();
			foreach ($dataRow as $propertyName => $value)
				$callBrokerClass::this()->$propertyName = $value;
		}, 'before');
		
		$specs[] = $spec;
	}
	
	return $specs;
}