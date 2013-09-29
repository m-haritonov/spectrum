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
 * @throws \spectrum\constructionCommands\Exception If called not at declaring state
 * @param  callback $function
 */
function before($function)
{
	$callBrokerClass = config::getConstructionCommandsCallBrokerClass();
	if ($callBrokerClass::internal_isRunningState())
		throw new \spectrum\constructionCommands\Exception('Construction command "' . __FUNCTION__ . '" should be call only at declaring state');

	return $callBrokerClass::internal_getCurrentDeclaringSpec()->contexts->add($function, 'before');
}