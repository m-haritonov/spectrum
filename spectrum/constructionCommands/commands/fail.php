<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\commands;
use spectrum\config;

/**
 * Add to ResultBuffer of running SpecItem false result wits exception as details.
 * @throws \spectrum\constructionCommands\Exception If called not at running state
 * @param string|null $message
 * @param int $code
 */
function fail($message = null, $code = 0)
{
	$callBrokerClass = config::getConstructionCommandsCallBrokerClass();
	if (!$callBrokerClass::internal_isRunningState())
		throw new \spectrum\constructionCommands\Exception('Construction command "' . __FUNCTION__ . '" should be call only at running state');
	
	$callBrokerClass::internal_getCurrentRunningSpec()->getResultBuffer()->addResult(false, new \spectrum\constructionCommands\FailException($message, $code));
}