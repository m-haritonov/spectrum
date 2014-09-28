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
 * @return SpecInterface
 */
function getCurrentBuildingSpec() {
	$reflection = new \ReflectionFunction(config::getFunctionReplacement('\spectrum\_internals\setCurrentBuildingSpec'));
	$staticVariables = $reflection->getStaticVariables();
	
	if (isset($staticVariables['buildingSpec'])) {
		return $staticVariables['buildingSpec'];
	} else {
		$getRootSpecFunction = config::getFunctionReplacement('\spectrum\_internals\getRootSpec');
		return $getRootSpecFunction();
	}
}