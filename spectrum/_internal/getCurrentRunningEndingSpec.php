<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\_internal;
use spectrum\config;

/**
 * @return \spectrum\core\SpecInterface|null
 */
function getCurrentRunningEndingSpec()
{
	$getRootSpecFunction = config::getFunctionReplacement('\spectrum\_internal\getRootSpec');
	$rootSpec = $getRootSpecFunction();
	if ($rootSpec->isRunning() && !$rootSpec->getChildSpecs())
		return $rootSpec;
	else
		return $rootSpec->getRunningEndingSpec();
}