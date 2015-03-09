<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\builders;

use spectrum\core\config;

/**
 * Runs tests.
 * @return null|bool
 */
function run() {
	if (!config::isLocked()) {
		config::lock();
	}
	
	$getRootSpecFunction = config::getFunctionReplacement('\spectrum\core\_private\getRootSpec');
	return $getRootSpecFunction()->run();
}