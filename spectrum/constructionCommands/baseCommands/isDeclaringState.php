<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\baseCommands;

/**
 * Available at declaring and running state.
 * @return bool
 */
function isDeclaringState()
{
	$managerClass = \spectrum\constructionCommands\Config::getManagerClass();
	return !$managerClass::isRunningState();
}