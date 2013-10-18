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
 * Available at declaring and running state.
 * @return bool
 */
function isRunningState()
{
	foreach (debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT | DEBUG_BACKTRACE_IGNORE_ARGS) as $trace)
	{
		if (is_object(@$trace['object']) && $trace['object'] instanceof SpecInterface)
			return true;
	}

	return false;
}