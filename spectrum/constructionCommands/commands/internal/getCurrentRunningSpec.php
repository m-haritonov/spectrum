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
function getCurrentRunningSpec()
{
	$callBrokerClass = config::getConstructionCommandsCallBrokerClass();
	return $callBrokerClass::internal_getInitialSpec()->getDeepestRunningSpec();
}