<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\builders;

use spectrum\core\config;
use spectrum\core\Exception;
use spectrum\core\models\SpecInterface;

/**
 * Adds to results of current test false result wits message as details.
 * @throws \spectrum\core\Exception If called not at running state
 * @param null|string $message
 */
function fail($message = null) {
	$isRunningStateFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\isRunningState');
	if (!$isRunningStateFunction()) {
		throw new Exception('Function "fail" should be call only at running state');
	}

	$getCurrentRunningEndingSpecFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\getCurrentRunningEndingSpec');
	$userFailDetailsClass = config::getCoreClassReplacement('\spectrum\core\models\details\UserFail');
	/** @var SpecInterface $spec */
	$spec = $getCurrentRunningEndingSpecFunction();
	$spec->getResults()->add(false, new $userFailDetailsClass($message));
}