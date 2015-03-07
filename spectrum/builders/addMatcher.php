<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\builders;

use spectrum\config;
use spectrum\Exception;

/**
 * Adds matcher to current group.
 * @throws \spectrum\Exception If called not at building state
 * @param string $name
 * @param callable $function
 */
function addMatcher($name, $function) {
	$isRunningStateFunction = config::getFunctionReplacement('\spectrum\_private\isRunningState');
	if ($isRunningStateFunction()) {
		throw new Exception('Builder "addMatcher" should be call only at building state');
	}

	$getCurrentBuildingSpecFunction = config::getFunctionReplacement('\spectrum\_private\getCurrentBuildingSpec');
	return $getCurrentBuildingSpecFunction()->getMatchers()->add($name, $function);
}