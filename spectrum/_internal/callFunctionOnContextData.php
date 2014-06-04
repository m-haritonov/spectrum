<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internal;

use spectrum\Exception;

/**
 * @access private
 */
function callFunctionOnContextData($function, array $arguments, $contextData)
{
	// Access to context through "$this" variable, available in php >= 5.4
	if (method_exists($function, 'bindTo'))
	{
		$function = $function->bindTo($contextData);
		if (!$function)
			throw new Exception('Can\'t bind "$this" variable to context data instance');
	}

	return call_user_func_array($function, $arguments);
}