<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\builders\internal;

/**
 * @return \spectrum\core\SpecInterface|null
 */
function getBuildingSpec()
{
	$reflection = new \ReflectionFunction('spectrum\builders\internal\setBuildingSpec');
	$staticVariables = $reflection->getStaticVariables();
	
	if (isset($staticVariables['buildingSpec']))
		return $staticVariables['buildingSpec'];
	else
		return \spectrum\builders\getRootSpec();
}