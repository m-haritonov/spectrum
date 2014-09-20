<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internals;

use spectrum\config;

/**
 * @access private
 */
function getCurrentData() {
	$getCurrentRunningEndingSpecFunction = config::getFunctionReplacement('\spectrum\_internals\getCurrentRunningEndingSpec');
	return $getCurrentRunningEndingSpecFunction()->test->getData();
}