<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\constructionCommands\commands;
use spectrum\config;

/**
 * @throws \spectrum\constructionCommands\Exception If called not at declaring state
 * @param  string $name
 * @param  callback $function
 */
function addMatcher($storage, $name, $function)
{
	$callBrokerClass = config::getConstructionCommandCallBrokerClass();
	if ($callBrokerClass::internal_isRunningState())
		throw new \spectrum\constructionCommands\Exception('Construction command "addMatcher" should be call only at declaring state');

	return $callBrokerClass::internal_getDeclaringSpec()->matchers->add($name, $function);
}