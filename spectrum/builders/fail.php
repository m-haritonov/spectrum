<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\builders;

use spectrum\config;
use spectrum\Exception;

/**
 * Adds to result buffer of current test false result wits message as details.
 * @throws \spectrum\Exception If called not at running state
 * @param null|string $message
 */
function fail($message = null) {
	$isRunningStateFunction = config::getFunctionReplacement('\spectrum\_private\isRunningState');
	if (!$isRunningStateFunction()) {
		throw new Exception('Builder "fail" should be call only at running state');
	}

	$getCurrentRunningEndingSpecFunction = config::getFunctionReplacement('\spectrum\_private\getCurrentRunningEndingSpec');
	$userFailDetailsClass = config::getClassReplacement('\spectrum\core\details\UserFail');
	$getCurrentRunningEndingSpecFunction()->getResultBuffer()->addResult(false, new $userFailDetailsClass($message));
}