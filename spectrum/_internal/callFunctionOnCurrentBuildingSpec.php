<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\_internal;

use spectrum\config;
use spectrum\core\SpecInterface;

function callFunctionOnCurrentBuildingSpec($function, SpecInterface $spec)
{
	$getCurrentBuildingSpecFunction = config::getFunctionReplacement('\spectrum\_internal\getCurrentBuildingSpec');
	$specBackup = $getCurrentBuildingSpecFunction();
	
	$setCurrentBuildingSpecFunction = config::getFunctionReplacement('\spectrum\_internal\setCurrentBuildingSpec');
	$setCurrentBuildingSpecFunction($spec);
	$returnValue = $function();
	$setCurrentBuildingSpecFunction($specBackup);
	
	return $returnValue;
}