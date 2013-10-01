<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\commands\internal;
use spectrum\core\SpecInterface;

/**
 * @see getDeclaringSpec()
 */
function setDeclaringSpec($storage, SpecInterface $spec = null)
{
	$storage['_self_']['currentSpec'] = $spec;
}