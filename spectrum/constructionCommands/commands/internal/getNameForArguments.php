<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\commands\internal;

function getNameForArguments(array $arguments, $defaultName)
{
	$arguments = array_values($arguments);
	
	if (count($arguments) == 1 && is_scalar($arguments[0]) && $defaultName == (int) $defaultName)
	{
		if (mb_strlen($arguments[0]) > 100)
			return mb_substr($arguments[0], 0, 100) . '...';
		else
			return $arguments[0];
	}
	else
		return $defaultName;
}