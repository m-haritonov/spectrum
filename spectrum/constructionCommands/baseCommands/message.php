<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\baseCommands;

/**
 * Add message to Messages plugin.
 * @throws \spectrum\constructionCommands\Exception If called not at running state
 */
function message($message)
{
	$managerClass = \spectrum\constructionCommands\Config::getManagerClass();
	if (!$managerClass::isRunningState())
		throw new \spectrum\constructionCommands\Exception('Construction command "message" should be call only at running state');
	
	$managerClass::getCurrentItem()->messages->add($message);
}