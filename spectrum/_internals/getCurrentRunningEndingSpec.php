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
 * @return null|SpecInterface
 */
function getCurrentRunningEndingSpec() {
	$getRootSpecFunction = config::getFunctionReplacement('\spectrum\_internals\getRootSpec');
	/** @var SpecInterface $rootSpec */
	$rootSpec = $getRootSpecFunction();
	if ($rootSpec->isRunning() && !$rootSpec->getChildSpecs()) {
		return $rootSpec;
	} else {
		return $rootSpec->getRunningDescendantEndingSpec();
	}
}