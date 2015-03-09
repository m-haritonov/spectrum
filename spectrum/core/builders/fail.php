<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\builders;

use spectrum\core\config;
use spectrum\core\Exception;
use spectrum\core\SpecInterface;

/**
 * Adds to results of current test false result wits message as details.
 * @throws \spectrum\core\Exception If called not at running state
 * @param null|string $message
 */
function fail($message = null) {
	$isRunningStateFunction = config::getFunctionReplacement('\spectrum\_private\isRunningState');
	if (!$isRunningStateFunction()) {
		throw new Exception('Function "fail" should be call only at running state');
	}

	$getCurrentRunningEndingSpecFunction = config::getFunctionReplacement('\spectrum\_private\getCurrentRunningEndingSpec');
	$userFailDetailsClass = config::getClassReplacement('\spectrum\core\details\UserFail');
	/** @var SpecInterface $spec */
	$spec = $getCurrentRunningEndingSpecFunction();
	$spec->getResults()->add(false, new $userFailDetailsClass($message));
}