<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\baseCommands;
/**
 * @throws \spectrum\constructionCommands\Exception If called not at running state
 * @param  mixed $actualValue
 * @return \spectrum\core\asserts\Assert
 */
function be($actualValue)
{
	$managerClass = \spectrum\constructionCommands\Config::getManagerClass();
	if (!$managerClass::isRunningState())
		throw new \spectrum\constructionCommands\Exception('Construction command "be" should be call only at running state');

	$class = \spectrum\core\Config::getAssertClass();
	return new $class($actualValue);
}