<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internals;

use spectrum\config;
use spectrum\core\SpecInterface;

/**
 * @access private
 */
function callFunctionOnCurrentBuildingSpec($function, SpecInterface $spec)
{
	$getCurrentBuildingSpecFunction = config::getFunctionReplacement('\spectrum\_internals\getCurrentBuildingSpec');
	$specBackup = $getCurrentBuildingSpecFunction();
	
	$setCurrentBuildingSpecFunction = config::getFunctionReplacement('\spectrum\_internals\setCurrentBuildingSpec');
	$setCurrentBuildingSpecFunction($spec);
	$returnValue = $function();
	$setCurrentBuildingSpecFunction($specBackup);
	
	return $returnValue;
}