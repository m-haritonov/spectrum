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
function after($storage, $function)
{
	$callBrokerClass = config::getConstructionCommandCallBrokerClass();
	if ($callBrokerClass::internal_isRunningState())
		throw new \spectrum\constructionCommands\Exception('Construction command "after" should be call only at declaring state');

	return $callBrokerClass::internal_getDeclaringSpec()->contexts->add($function, 'after');
}