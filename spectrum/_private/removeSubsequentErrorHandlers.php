<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_private;

use spectrum\core\config;

/**
 * @access private
 * @param callable $checkErrorHandler
 */
function removeSubsequentErrorHandlers($checkErrorHandler) {
	$getLastErrorHandlerFunction = config::getFunctionReplacement('\spectrum\_private\getLastErrorHandler');
	
	$errorHandlers = array();
	while ($lastErrorHandler = $getLastErrorHandlerFunction()) {
		if ($lastErrorHandler === $checkErrorHandler) {
			$errorHandlers = array();
			break;
		}
		
		$errorHandlers[] = $lastErrorHandler;
		restore_error_handler();
	}
	
	// Rollback all error handlers if $checkErrorHandler is not find
	foreach (array_reverse($errorHandlers) as $errorHandler) {
		set_error_handler($errorHandler);
	}
}