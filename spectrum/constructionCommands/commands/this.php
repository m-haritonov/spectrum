<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\commands;

use spectrum\config;

function this()
{
	$callBrokerClass = config::getConstructionCommandsCallBrokerClass();
	if (!$callBrokerClass::internal_isRunningState())
		throw new \spectrum\constructionCommands\Exception('Construction command "' . __FUNCTION__ . '" should be call only at running state');

	return $callBrokerClass::internal_getCurrentRunningSpec()->testFunction->getContextData();
}