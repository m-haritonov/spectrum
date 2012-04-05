<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\baseCommands;

/**
 * Add to RunResultsBuffer of running SpecItem false result wits exception as details.
 * @throws \spectrum\constructionCommands\Exception If called not at running state
 * @param string|null $message
 * @param int $code
 */
function fail($message = null, $code = 0)
{
	$managerClass = \spectrum\constructionCommands\Config::getManagerClass();
	if (!$managerClass::isRunningState())
		throw new \spectrum\constructionCommands\Exception('Construction command "fail" should be call only at running state');
	
	$managerClass::getCurrentItem()->getRunResultsBuffer()->addResult(false, new \spectrum\constructionCommands\ExceptionFail($message, $code));
}