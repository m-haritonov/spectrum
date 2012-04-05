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
 * @return \spectrum\core\SpecItemInterface|null
 */
function getCurrentItem()
{
	$managerClass = \spectrum\constructionCommands\Config::getManagerClass();
	if (!$managerClass::isRunningState())
		throw new \spectrum\constructionCommands\Exception('Construction command "getCurrentItem" should be call only at running state');

	$registryClass = \spectrum\core\Config::getRegistryClass();
	return $registryClass::getRunningSpecItem();
}