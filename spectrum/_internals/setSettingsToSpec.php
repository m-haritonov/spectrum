<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internals;

use spectrum\config;
use spectrum\core\SpecInterface;

/**
 * @access private
 */
function setSettingsToSpec(SpecInterface $spec, $settings) {
	$normalizeSettingsFunction = config::getFunctionReplacement('\spectrum\_internals\normalizeSettings');
	$settings = $normalizeSettingsFunction($settings);
	
	if ($settings['catchPhpErrors'] !== null) {
		$spec->errorHandling->setCatchPhpErrors($settings['catchPhpErrors']);
	}
	
	if ($settings['breakOnFirstPhpError'] !== null) {
		$spec->errorHandling->setBreakOnFirstPhpError($settings['breakOnFirstPhpError']);
	}
	
	if ($settings['breakOnFirstMatcherFail'] !== null) {
		$spec->errorHandling->setBreakOnFirstMatcherFail($settings['breakOnFirstMatcherFail']);
	}
}