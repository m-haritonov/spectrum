<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\_private;

use spectrum\core\config;
use spectrum\core\models\SpecInterface;

/**
 * @access private
 * @param null|int|bool|array $settings
 */
function setSettingsToSpec(SpecInterface $spec, $settings) {
	$normalizeSettingsFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\normalizeSettings');
	$settings = $normalizeSettingsFunction($settings);
	
	if ($settings['catchPhpErrors'] !== null) {
		$spec->getErrorHandling()->setCatchPhpErrors($settings['catchPhpErrors']);
	}
	
	if ($settings['breakOnFirstPhpError'] !== null) {
		$spec->getErrorHandling()->setBreakOnFirstPhpError($settings['breakOnFirstPhpError']);
	}
	
	if ($settings['breakOnFirstMatcherFail'] !== null) {
		$spec->getErrorHandling()->setBreakOnFirstMatcherFail($settings['breakOnFirstMatcherFail']);
	}
}