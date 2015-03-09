<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_private;

use spectrum\core\config;
use spectrum\core\SpecInterface;

/**
 * @access private
 * @param callable $function
 * @return mixed
 */
function callFunctionOnCurrentBuildingSpec($function, SpecInterface $spec) {
	$getCurrentBuildingSpecFunction = config::getFunctionReplacement('\spectrum\_private\getCurrentBuildingSpec');
	$specBackup = $getCurrentBuildingSpecFunction();
	
	$setCurrentBuildingSpecFunction = config::getFunctionReplacement('\spectrum\_private\setCurrentBuildingSpec');
	$setCurrentBuildingSpecFunction($spec);
	$returnValue = $function();
	$setCurrentBuildingSpecFunction($specBackup);
	
	return $returnValue;
}