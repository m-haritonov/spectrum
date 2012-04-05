<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\baseCommands;
use spectrum\constructionCommands\Manager;
/**
 * Available at declaring and running state.
 * @return \spectrum\core\SpecContainerInterface|null
 */
function getCurrentContainer()
{
	$declaringContainer = Manager::getDeclaringContainer();
	$registryClass = \spectrum\core\Config::getRegistryClass();
	$managerClass = \spectrum\constructionCommands\Config::getManagerClass();

	if ($declaringContainer)
		return $declaringContainer;
	else if ($registryClass::getRunningSpecContainer())
		return $registryClass::getRunningSpecContainer();
	else if ($managerClass::isDeclaringState())
		return \spectrum\RootDescribe::getOnceInstance();
	else
		return null;
}