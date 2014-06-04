<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internal;
use spectrum\config;

/**
 * @access private
 * @return \spectrum\core\SpecInterface|null
 */
function getCurrentBuildingSpec()
{
	$reflection = new \ReflectionFunction(config::getFunctionReplacement('\spectrum\_internal\setCurrentBuildingSpec'));
	$staticVariables = $reflection->getStaticVariables();
	
	if (isset($staticVariables['buildingSpec']))
		return $staticVariables['buildingSpec'];
	else
	{
		$getRootSpecFunction = config::getFunctionReplacement('\spectrum\_internal\getRootSpec');
		return $getRootSpecFunction();
	}
}