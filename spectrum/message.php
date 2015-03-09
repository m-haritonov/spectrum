<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum;

use spectrum\core\Exception;
use spectrum\core\config;

/**
 * Adds message to current test.
 * @throws \spectrum\core\Exception If called not at running state
 */
function message($message) {
	$isRunningStateFunction = config::getFunctionReplacement('\spectrum\_private\isRunningState');
	if (!$isRunningStateFunction()) {
		throw new Exception('Builder "message" should be call only at running state');
	}
	
	$getCurrentRunningEndingSpecFunction = config::getFunctionReplacement('\spectrum\_private\getCurrentRunningEndingSpec');
	$getCurrentRunningEndingSpecFunction()->getMessages()->add($message);
}