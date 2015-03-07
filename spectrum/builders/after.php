<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\builders;

use spectrum\config;
use spectrum\Exception;

/**
 * Adds "after" context modifier.
 * @throws \spectrum\Exception If called not at building state
 * @param callable $function
 */
function after($function) {
	$isRunningStateFunction = config::getFunctionReplacement('\spectrum\_internals\isRunningState');
	if ($isRunningStateFunction()) {
		throw new Exception('Builder "after" should be call only at building state');
	}

	$getCurrentBuildingSpecFunction = config::getFunctionReplacement('\spectrum\_internals\getCurrentBuildingSpec');
	return $getCurrentBuildingSpecFunction()->getContextModifiers()->add($function, 'after');
}