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
 * @return SpecInterface
 */
function getCurrentBuildingSpec() {
	$reflection = new \ReflectionFunction(config::getFunctionReplacement('\spectrum\_private\setCurrentBuildingSpec'));
	$staticVariables = $reflection->getStaticVariables();
	
	if (isset($staticVariables['buildingSpec'])) {
		return $staticVariables['buildingSpec'];
	} else {
		$getRootSpecFunction = config::getFunctionReplacement('\spectrum\_private\getRootSpec');
		return $getRootSpecFunction();
	}
}