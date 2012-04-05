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
function getDeclaringContainer()
{
	$reflection = new \ReflectionFunction('\spectrum\constructionCommands\baseCommands\setDeclaringContainer');
	$vars = $reflection->getStaticVariables();
	return $vars['container'];
}