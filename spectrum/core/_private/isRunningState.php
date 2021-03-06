<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\_private;

use spectrum\core\models\SpecInterface;

/**
 * Available at building and running state.
 * @access private
 * @return bool
 */
function isRunningState() {
	foreach (debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT | DEBUG_BACKTRACE_IGNORE_ARGS) as $trace) {
		if (isset($trace['object']) && is_object($trace['object']) && $trace['object'] instanceof SpecInterface) {
			return true;
		}
	}

	return false;
}