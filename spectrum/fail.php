<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum;

use spectrum\config;
use spectrum\core\SpecInterface;
use spectrum\Exception;

/**
 * Adds to results of current test false result wits message as details.
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
	/** @var SpecInterface $spec */
	$spec = $getCurrentRunningEndingSpecFunction();
	$spec->getResults()->add(false, new $userFailDetailsClass($message));
}