<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\builders;

use spectrum\core\config;
use spectrum\core\Exception;

/**
 * Adds "before" context modifier.
 * @throws \spectrum\core\Exception If called not at building state
 * @param callable $function
 */
function before($function) {
	$isRunningStateFunction = config::getFunctionReplacement('\spectrum\core\_private\isRunningState');
	if ($isRunningStateFunction()) {
		throw new Exception('Function "before" should be call only at building state');
	}

	$getCurrentBuildingSpecFunction = config::getFunctionReplacement('\spectrum\core\_private\getCurrentBuildingSpec');
	return $getCurrentBuildingSpecFunction()->getContextModifiers()->add($function, 'before');
}