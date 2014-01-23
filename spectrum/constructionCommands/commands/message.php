<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\constructionCommands\commands;
use spectrum\config;

/**
 * Add message to Messages plugin.
 * @throws \spectrum\constructionCommands\Exception If called not at running state
 */
function message($storage, $message)
{
	$callBrokerClass = config::getConstructionCommandCallBrokerClass();
	if (!$callBrokerClass::internal_isRunningState())
		throw new \spectrum\constructionCommands\Exception('Construction command "message" should be call only at running state');
	
	$callBrokerClass::internal_getRunningEndingSpec()->messages->add($message);
}