<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\commands\internal;

function getExclusionSpecs($storage)
{
	if (@$storage['internal_addExclusionSpec']['exclusionSpecs'])
		return $storage['internal_addExclusionSpec']['exclusionSpecs'];
	else
		return array();
}