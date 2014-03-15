<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\_internal;
use spectrum\config;

/**
 * @example
 * \spectrum\_internal\normalizeSettings(array(
 *     'catchPhpErrors' => -1,            // see \spectrum\core\plugins\ErrorHandling::setCatchPhpErrors()
 *     'breakOnFirstPhpError' => true,    // see \spectrum\core\plugins\ErrorHandling::setBreakOnFirstPhpError()
 *     'breakOnFirstMatcherFail' => true, // see \spectrum\core\plugins\ErrorHandling::setBreakOnFirstMatcherFail()
 * ));
 *
 * @example
 * \spectrum\_internal\normalizeSettings(E_ALL); // see \spectrum\core\plugins\ErrorHandling::setCatchPhpErrors()
 * \spectrum\_internal\normalizeSettings(true);  // see \spectrum\core\plugins\ErrorHandling::setCatchPhpErrors()
 *
 * @param mixed $settings
 */
function normalizeSettings($settings)
{
	$normalizedSettings = array(
		'catchPhpErrors' => null,
		'breakOnFirstPhpError' => null,
		'breakOnFirstMatcherFail' => null,
	);
	
	if ($settings !== null)
	{
		if (is_int($settings) || is_bool($settings))
			$normalizedSettings['catchPhpErrors'] = $settings;
		else if (is_array($settings))
		{
			foreach ($settings as $settingName => $settingValue)
			{
				if (in_array($settingName, array('catchPhpErrors', 'breakOnFirstPhpError', 'breakOnFirstMatcherFail')))
					$normalizedSettings[$settingName] = $settingValue;
				else
					throw new \spectrum\builders\Exception('Invalid setting "' . $settingName . '"');
			}
		}
		else
			throw new \spectrum\builders\Exception('Invalid settings variable type ("' . gettype($settings) . '")');
	}
	
	return $normalizedSettings;
}