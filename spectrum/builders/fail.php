<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\builders;
use spectrum\config;
use spectrum\core\details\UserFail;

/**
 * Add to ResultBuffer of running SpecItem false result wits exception as details.
 * @throws \spectrum\builders\Exception If called not at running state
 * @param string|null $message
 */
function fail($message = null)
{
	if (!\spectrum\_internal\isRunningState())
		throw new \spectrum\builders\Exception('Builder "fail" should be call only at running state');
	
	$userFailDetailsClass = config::getClassReplacement('\spectrum\core\details\UserFail');
	\spectrum\_internal\getRunningEndingSpec()->getResultBuffer()->addResult(false, new $userFailDetailsClass($message));
}