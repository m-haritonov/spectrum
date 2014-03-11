<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\builders\internal;

use spectrum\core\SpecInterface;

function callFunctionOnBuildingSpec($function, SpecInterface $spec)
{
	$specBackup = \spectrum\builders\internal\getBuildingSpec();
	
	\spectrum\builders\internal\setBuildingSpec($spec);
	$returnValue = $function();
	\spectrum\builders\internal\setBuildingSpec($specBackup);
	
	return $returnValue;
}