<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
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