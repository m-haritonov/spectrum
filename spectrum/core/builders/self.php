<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\builders;

use spectrum\core\config;

/**
 * Returns current group or test spec.
 * @return \spectrum\core\SpecInterface
 */
function self() {
	$isRunningStateFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\isRunningState');
	if ($isRunningStateFunction()) {
		$getCurrentRunningEndingSpecFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\getCurrentRunningEndingSpec');
		return $getCurrentRunningEndingSpecFunction();
	} else {
		$getCurrentBuildingSpecFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\getCurrentBuildingSpec');
		return $getCurrentBuildingSpecFunction();
	}
}