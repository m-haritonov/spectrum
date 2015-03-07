<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internals;

/**
 * @access private
 * @return null|callable
 */
function getLastErrorHandler() {
	$lastErrorHandler = set_error_handler(function($errorSeverity, $errorMessage){});
	restore_error_handler();
	return $lastErrorHandler;
}