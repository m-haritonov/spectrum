<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\builders;

use spectrum\core\Exception;
use spectrum\core\config;


/**
 * Adds "after" context modifier.
 * @throws \spectrum\core\Exception If called not at building state
 * @param callable $function
 */
function after($function) {
	$isRunningStateFunction = config::getFunctionReplacement('\spectrum\_private\isRunningState');
	if ($isRunningStateFunction()) {
		throw new Exception('Builder "after" should be call only at building state');
	}

	$getCurrentBuildingSpecFunction = config::getFunctionReplacement('\spectrum\_private\getCurrentBuildingSpec');
	return $getCurrentBuildingSpecFunction()->getContextModifiers()->add($function, 'after');
}