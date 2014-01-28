<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\builders;

use spectrum\core\SpecInterface;

/**
 * Available at building and running state.
 * @return bool
 */
function isRunningState()
{
	foreach (debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT | DEBUG_BACKTRACE_IGNORE_ARGS) as $trace)
	{
		if (isset($trace['object']) && is_object($trace['object']) && $trace['object'] instanceof SpecInterface)
			return true;
	}

	return false;
}