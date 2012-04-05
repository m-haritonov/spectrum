<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\baseCommands;

/**
 * Support params variants:
 * describe($name)
 * describe($name, $settings)
 *
 * describe($callback)
 * describe($callback, $settings)
 *
 * describe($name, $callback)
 * describe($name, $callback, $settings)
 *
 * @throws \spectrum\constructionCommands\Exception If called not at declaring state
 * @param  string|callback $nameOrCallback
 * @param  callback|null $callback
 * @return \spectrum\core\SpecContainerDescribe
 */
function describe($name = null, $callback = null, $settings = array())
{
	$managerClass = \spectrum\constructionCommands\Config::getManagerClass();
	if (!$managerClass::isDeclaringState())
		throw new \spectrum\constructionCommands\Exception('Construction command "describe" should be call only at declaring state');

	return $managerClass::container(\spectrum\core\Config::getSpecContainerDescribeClass(), $name, $callback, $settings);
}

