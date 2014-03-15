<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\builders;
use spectrum\config;

/**
 * @throws \spectrum\builders\Exception If called not at building state
 * @param  string $name
 * @param  callback $function
 */
function addMatcher($name, $function)
{
	$isRunningStateFunction = config::getFunctionReplacement('\spectrum\_internal\isRunningState');
	if ($isRunningStateFunction())
		throw new \spectrum\builders\Exception('Builder "addMatcher" should be call only at building state');

	$getBuildingSpecFunction = config::getFunctionReplacement('\spectrum\_internal\getBuildingSpec');
	return $getBuildingSpecFunction()->matchers->add($name, $function);
}