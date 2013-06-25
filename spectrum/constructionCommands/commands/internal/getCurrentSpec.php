<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\commands\internal;
use spectrum\config;

/**
 * @return \spectrum\core\specs\SpecInterface|null
 */
function getCurrentSpec()
{
	$callBrokerClass = config::getConstructionCommandsCallBrokerClass();
	if ($callBrokerClass::internal_isRunningState())
		return $callBrokerClass::internal_getCurrentRunningSpec();
	else
		return $callBrokerClass::internal_getCurrentDeclaringSpec();
}