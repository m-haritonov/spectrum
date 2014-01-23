<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\constructionCommands\commands;

use spectrum\config;

function this()
{
	$callBrokerClass = config::getConstructionCommandCallBrokerClass();
	if (!$callBrokerClass::internal_isRunningState())
		throw new \spectrum\constructionCommands\Exception('Construction command "this" should be call only at running state');

	return $callBrokerClass::internal_getRunningEndingSpec()->contexts->getContextData();
}