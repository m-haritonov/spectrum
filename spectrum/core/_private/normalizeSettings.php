<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\_private;

use spectrum\core\Exception;

/**
 * @example
 * \spectrum\core\_private\normalizeSettings(array(
 *     'catchPhpErrors' => -1,            // see \spectrum\core\models\ErrorHandling::setCatchPhpErrors()
 *     'breakOnFirstPhpError' => true,    // see \spectrum\core\models\ErrorHandling::setBreakOnFirstPhpError()
 *     'breakOnFirstMatcherFail' => true, // see \spectrum\core\models\ErrorHandling::setBreakOnFirstMatcherFail()
 * ));
 *
 * @example
 * \spectrum\core\_private\normalizeSettings(E_ALL); // see \spectrum\core\models\ErrorHandling::setCatchPhpErrors()
 * \spectrum\core\_private\normalizeSettings(true);  // see \spectrum\core\models\ErrorHandling::setCatchPhpErrors()
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