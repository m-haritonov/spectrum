<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\_private;

use spectrum\core\config;
use spectrum\core\SpecInterface;

/**
 * @access private
 * @return SpecInterface
 */
function getCurrentBuildingSpec() {
	static $data;
	if (!$data) {
		$reflection = new \ReflectionFunction(config::getCoreFunctionReplacement('\spectrum\core\_private\setCurrentBuildingSpec'));
		$staticVariables = $reflection->getStaticVariables();
		$data = $staticVariables['data'];
	}
	
	if (isset($data->buildingSpec)) {
		return $data->buildingSpec;
	} else {
		$getRootSpecFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\getRootSpec');
		return $getRootSpecFunction();
	}
}