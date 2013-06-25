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
function getCurrentDeclaringSpec()
{
	$callBrokerClass = config::getConstructionCommandsCallBrokerClass();
	$reflection = new \ReflectionFunction('\spectrum\constructionCommands\commands\internal\setCurrentDeclaringSpec');
	$vars = $reflection->getStaticVariables();
	if ($vars['currentSpec'])
		return $vars['currentSpec'];
	else
		return $callBrokerClass::internal_getInitialSpec();
}