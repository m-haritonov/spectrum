<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\commands\internal;

use spectrum\config;
use spectrum\core\specs\SpecInterface;

function getMultiplierEndingSpecs(SpecInterface $spec)
{
	$callBrokerClass = config::getConstructionCommandsCallBrokerClass();
	$multiplierExclusionSpecs = $callBrokerClass::internal_getMultiplierExclusionSpecs();
	
	$endingSpecs = array();
	foreach ($spec->getChildSpecs() as $childSpec)
	{
		if (in_array($childSpec, $multiplierExclusionSpecs, true))
			continue;
		
		if ($childSpec->getChildSpecs())
			$endingSpecs = array_merge($endingSpecs, call_user_func(__FUNCTION__, $childSpec));
		else
			$endingSpecs[] = $childSpec;
	}
	
	return $endingSpecs;
}