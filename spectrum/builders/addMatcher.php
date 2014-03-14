<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\builders;

/**
 * @throws \spectrum\builders\Exception If called not at building state
 * @param  string $name
 * @param  callback $function
 */
function addMatcher($name, $function)
{
	if (\spectrum\_internal\isRunningState())
		throw new \spectrum\builders\Exception('Builder "addMatcher" should be call only at building state');

	return \spectrum\_internal\getBuildingSpec()->matchers->add($name, $function);
}