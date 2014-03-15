<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\builders;

use spectrum\config;

/**
 * @throws \spectrum\builders\Exception If called not at running state
 * @param  mixed $testedValue
 * @return \spectrum\core\Assert
 */
function be($testedValue)
{
	if (!\spectrum\_internal\isRunningState())
		throw new \spectrum\builders\Exception('Builder "be" should be call only at running state');

	$assertClass = config::getClassReplacement('\spectrum\core\Assert');
	return new $assertClass(\spectrum\_internal\getRunningEndingSpec(), $testedValue);
}