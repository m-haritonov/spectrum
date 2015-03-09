<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\builders;

use spectrum\core\config;
use spectrum\core\Exception;

/**
 * Adds matcher to current group.
 * @throws \spectrum\core\Exception If called not at building state
 * @param string $name
 * @param callable $function
 */
function matcher($name, $function) {
	$isRunningStateFunction = config::getFunctionReplacement('\spectrum\_private\isRunningState');
	if ($isRunningStateFunction()) {
		throw new Exception('Function "matcher" should be call only at building state');
	}

	$getCurrentBuildingSpecFunction = config::getFunctionReplacement('\spectrum\_private\getCurrentBuildingSpec');
	return $getCurrentBuildingSpecFunction()->getMatchers()->add($name, $function);
}