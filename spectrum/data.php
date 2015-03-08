<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum;

use spectrum\config;
use spectrum\core\DataInterface;
use spectrum\Exception;

/**
 * Returns data instance of current test.
 * @throws \spectrum\Exception If called not at running state
 * @return DataInterface
 */
function data() {
	$isRunningStateFunction = config::getFunctionReplacement('\spectrum\_private\isRunningState');
	if (!$isRunningStateFunction()) {
		throw new Exception('Builder "data" should be call only at running state');
	}

	$getCurrentDataFunction = config::getFunctionReplacement('\spectrum\_private\getCurrentData');
	return $getCurrentDataFunction();
}