<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\builders;

use spectrum\config;
use spectrum\Exception;

/**
 * @throws \spectrum\Exception If called not at running state
 * @param  mixed $testedValue
 * @return \spectrum\core\Assert
 */
function be($testedValue)
{
	$isRunningStateFunction = config::getFunctionReplacement('\spectrum\_internal\isRunningState');
	if (!$isRunningStateFunction())
		throw new Exception('Builder "be" should be call only at running state');

	$assertClass = config::getClassReplacement('\spectrum\core\Assert');
	$getRunningEndingSpecFunction = config::getFunctionReplacement('\spectrum\_internal\getRunningEndingSpec');
	return new $assertClass($getRunningEndingSpecFunction(), $testedValue);
}