<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\constructionCommands\commands;
use spectrum\config;

/**
 * Add to ResultBuffer of running SpecItem false result wits exception as details.
 * @throws \spectrum\constructionCommands\Exception If called not at running state
 * @param string|null $message
 */
function fail($storage, $message = null)
{
	$callBrokerClass = config::getConstructionCommandCallBrokerClass();
	if (!$callBrokerClass::internal_isRunningState())
		throw new \spectrum\constructionCommands\Exception('Construction command "fail" should be call only at running state');
	
	$callBrokerClass::internal_getRunningEndingSpec()->getResultBuffer()->addResult(false, new \spectrum\constructionCommands\FailException($message));
}