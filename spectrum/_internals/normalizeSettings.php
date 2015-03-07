<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internals;

use spectrum\Exception;

/**
 * @example
 * \spectrum\_internals\normalizeSettings(array(
 *     'catchPhpErrors' => -1,            // see \spectrum\core\ErrorHandling::setCatchPhpErrors()
 *     'breakOnFirstPhpError' => true,    // see \spectrum\core\ErrorHandling::setBreakOnFirstPhpError()
 *     'breakOnFirstMatcherFail' => true, // see \spectrum\core\ErrorHandling::setBreakOnFirstMatcherFail()
 * ));
 *
 * @example
 * \spectrum\_internals\normalizeSettings(E_ALL); // see \spectrum\core\ErrorHandling::setCatchPhpErrors()
 * \spectrum\_internals\normalizeSettings(true);  // see \spectrum\core\ErrorHandling::setCatchPhpErrors()
 *
 * @access private
 * @param null|int|bool|array $settings
 */
function normalizeSettings($settings) {
	$normalizedSettings = array(
		'catchPhpErrors' => null,
		'breakOnFirstPhpError' => null,
		'breakOnFirstMatcherFail' => null,
	);
	
	if ($settings !== null) {
		if (is_int($settings) || is_bool($settings)) {
			$normalizedSettings['catchPhpErrors'] = $settings;
		} else if (is_array($settings)) {
			foreach ($settings as $settingName => $settingValue) {
				if (in_array($settingName, array('catchPhpErrors', 'breakOnFirstPhpError', 'breakOnFirstMatcherFail'))) {
					$normalizedSettings[$settingName] = $settingValue;
				} else {
					throw new Exception('Invalid setting "' . $settingName . '"');
				}
			}
		} else {
			throw new Exception('Invalid settings variable type ("' . gettype($settings) . '")');
		}
	}
	
	return $normalizedSettings;
}