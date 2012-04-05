<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\baseCommands;

/**
 * @throws \spectrum\constructionCommands\Exception If called not at declaring state
 * @param  string $name
 * @param  callback $callback
 */
function addMatcher($name, $callback)
{
	$managerClass = \spectrum\constructionCommands\Config::getManagerClass();
	if (!$managerClass::isDeclaringState())
		throw new \spectrum\constructionCommands\Exception('Construction command "addMatcher" should be call only at declaring state');

	return $managerClass::getCurrentContainer()->matchers->add($name, $callback);
}