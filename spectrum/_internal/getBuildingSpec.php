<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\_internal;
use spectrum\config;

/**
 * @return \spectrum\core\SpecInterface|null
 */
function getBuildingSpec()
{
	$reflection = new \ReflectionFunction(config::getFunctionReplacement('\spectrum\_internal\setBuildingSpec'));
	$staticVariables = $reflection->getStaticVariables();
	
	if (isset($staticVariables['buildingSpec']))
		return $staticVariables['buildingSpec'];
	else
	{
		$getRootSpecFunction = config::getFunctionReplacement('\spectrum\_internal\getRootSpec');
		return $getRootSpecFunction();
	}
}