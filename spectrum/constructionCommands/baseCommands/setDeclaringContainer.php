<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\baseCommands;

/**
 * @see getCurrentContainer()
 */
function setDeclaringContainer(\spectrum\core\SpecContainerInterface $spec = null)
{
	static $container;
	$container = $spec;
}