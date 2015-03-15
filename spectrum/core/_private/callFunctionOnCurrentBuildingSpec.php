<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\_private;

use spectrum\core\config;
use spectrum\core\models\SpecInterface;

/**
 * @access private
 * @param callable $function
 * @return mixed
 */
function callFunctionOnCurrentBuildingSpec($function, SpecInterface $spec) {
	$getCurrentBuildingSpecFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\getCurrentBuildingSpec');
	$specBackup = $getCurrentBuildingSpecFunction();
	
	$setCurrentBuildingSpecFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\setCurrentBuildingSpec');
	$setCurrentBuildingSpecFunction($spec);
	$returnValue = $function();
	$setCurrentBuildingSpecFunction($specBackup);
	
	return $returnValue;
}