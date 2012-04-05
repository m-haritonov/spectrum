<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\baseCommands;

/**
 * Available at declaring and running state.
 * @return bool
 */
function isRunningState()
{
	foreach (debug_backtrace() as $trace)
	{
		if (!is_object(@$trace['object']))
			continue;

		if ($trace['object'] instanceof \spectrum\core\SpecInterface)
			return true;
	}

	return false;
}