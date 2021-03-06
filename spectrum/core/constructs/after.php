<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\constructs;

use spectrum\core\Exception;
use spectrum\core\config;


/**
 * Adds "after" context modifier.
 * @throws \spectrum\core\Exception If called not at building state
 * @param callable $function
 */
function after($function) {
	$isRunningStateFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\isRunningState');
	if ($isRunningStateFunction()) {
		throw new Exception('Function "after" should be call only at building state');
	}

	$getCurrentBuildingSpecFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\getCurrentBuildingSpec');
	$getCurrentBuildingSpecFunction()->getContextModifiers()->add($function, 'after');
}