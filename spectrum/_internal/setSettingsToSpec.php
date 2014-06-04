<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internal;

use spectrum\config;
use spectrum\core\SpecInterface;

function setSettingsToSpec(SpecInterface $spec, $settings)
{
	$normalizeSettingsFunction = config::getFunctionReplacement('\spectrum\_internal\normalizeSettings');
	$settings = $normalizeSettingsFunction($settings);
	
	if ($settings['catchPhpErrors'] !== null)
		$spec->errorHandling->setCatchPhpErrors($settings['catchPhpErrors']);
	
	if ($settings['breakOnFirstPhpError'] !== null)
		$spec->errorHandling->setBreakOnFirstPhpError($settings['breakOnFirstPhpError']);
	
	if ($settings['breakOnFirstMatcherFail'] !== null)
		$spec->errorHandling->setBreakOnFirstMatcherFail($settings['breakOnFirstMatcherFail']);
}