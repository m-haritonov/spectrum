<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_private;

use spectrum\config;

/**
 * @param string $event
 * @param array $arguments
 * @param callable $onCatchException
 */
function dispatchEvent($event, array $arguments = array(), $onCatchException = null) {
	$callbacks = config::getRegisteredEventListeners();
	
	foreach ($callbacks as $callback) {
		if ($callback['event'] === $event) {
			try {
				call_user_func_array($callback['callback'], $arguments);
			} catch (\Exception $e) {
				if ($onCatchException) {
					$onCatchException($e);
				} else {
					throw $e;
				}
			}
		}
	}
}