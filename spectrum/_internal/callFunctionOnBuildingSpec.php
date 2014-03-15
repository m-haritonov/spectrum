<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\_internal;

use spectrum\config;
use spectrum\core\SpecInterface;

function callFunctionOnBuildingSpec($function, SpecInterface $spec)
{
	$getBuildingSpecFunction = config::getFunctionReplacement('\spectrum\_internal\getBuildingSpec');
	$specBackup = $getBuildingSpecFunction();
	
	$setBuildingSpecFunction = config::getFunctionReplacement('\spectrum\_internal\setBuildingSpec');
	$setBuildingSpecFunction($spec);
	$returnValue = $function();
	$setBuildingSpecFunction($specBackup);
	
	return $returnValue;
}