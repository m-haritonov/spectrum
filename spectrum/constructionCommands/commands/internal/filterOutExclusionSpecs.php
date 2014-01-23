<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\constructionCommands\commands\internal;

use spectrum\config;

function filterOutExclusionSpecs($storage, array $specs)
{
	$callBrokerClass = config::getConstructionCommandCallBrokerClass();
	$exclusionSpecs = $callBrokerClass::internal_getExclusionSpecs();
	
	$filteredSpecs = array();
	foreach ($specs as $spec)
	{
		if (!in_array($spec, $exclusionSpecs, true))
			$filteredSpecs[] = $spec;
	}
	
	return $filteredSpecs;
}