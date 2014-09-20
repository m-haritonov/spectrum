<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internals;
use spectrum\config;

/**
 * @access private
 * @return \spectrum\core\SpecInterface|null
 */
function getCurrentRunningEndingSpec() {
	$getRootSpecFunction = config::getFunctionReplacement('\spectrum\_internals\getRootSpec');
	$rootSpec = $getRootSpecFunction();
	if ($rootSpec->isRunning() && !$rootSpec->getChildSpecs()) {
		return $rootSpec;
	}
	else {
		return $rootSpec->getRunningDescendantEndingSpec();
	}
}