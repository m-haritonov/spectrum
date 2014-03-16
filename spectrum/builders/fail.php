<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\builders;
use spectrum\config;
use spectrum\core\details\UserFail;
use spectrum\Exception;

/**
 * Add to ResultBuffer of running SpecItem false result wits exception as details.
 * @throws \spectrum\Exception If called not at running state
 * @param string|null $message
 */
function fail($message = null)
{
	$isRunningStateFunction = config::getFunctionReplacement('\spectrum\_internal\isRunningState');
	if (!$isRunningStateFunction())
		throw new Exception('Builder "fail" should be call only at running state');

	$getRunningEndingSpecFunction = config::getFunctionReplacement('\spectrum\_internal\getRunningEndingSpec');
	$userFailDetailsClass = config::getClassReplacement('\spectrum\core\details\UserFail');
	$getRunningEndingSpecFunction()->getResultBuffer()->addResult(false, new $userFailDetailsClass($message));
}