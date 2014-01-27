<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\builders\internal;

/**
 * @return \spectrum\core\SpecInterface|null
 */
function getRunningEndingSpec()
{
	$rootSpec = \spectrum\builders\getRootSpec();
	if ($rootSpec->isRunning() && !$rootSpec->getChildSpecs())
		return $rootSpec;
	else
		return $rootSpec->getRunningEndingSpec();
}