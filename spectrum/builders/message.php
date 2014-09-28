<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\builders;

use spectrum\config;
use spectrum\Exception;

/**
 * Adds message to current test.
 * @throws \spectrum\Exception If called not at running state
 */
function message($message) {
	$isRunningStateFunction = config::getFunctionReplacement('\spectrum\_internals\isRunningState');
	if (!$isRunningStateFunction()) {
		throw new Exception('Builder "message" should be call only at running state');
	}
	
	$getCurrentRunningEndingSpecFunction = config::getFunctionReplacement('\spectrum\_internals\getCurrentRunningEndingSpec');
	$getCurrentRunningEndingSpecFunction()->messages->add($message);
}