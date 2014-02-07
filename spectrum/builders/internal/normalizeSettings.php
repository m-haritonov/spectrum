<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\builders\internal;

/**
 * @example
 * \spectrum\builders\internal\normalizeSettings(array(
 *     'catchPhpErrors' => -1,            // see \spectrum\core\plugins\basePlugins\ErrorHandling::setCatchPhpErrors()
 *     'breakOnFirstPhpError' => true,    // see \spectrum\core\plugins\basePlugins\ErrorHandling::setBreakOnFirstPhpError()
 *     'breakOnFirstMatcherFail' => true, // see \spectrum\core\plugins\basePlugins\ErrorHandling::setBreakOnFirstMatcherFail()
 *     'inputCharset' => 'windows-1251', // see \spectrum\core\Spec::setInputCharset()
 * ));
 *
 * @example
 * \spectrum\builders\internal\normalizeSettings('windows-1251'); // see \spectrum\core\Spec::setInputCharset()
 *
 * @example
 * \spectrum\builders\internal\normalizeSettings(E_ALL); // see \spectrum\core\plugins\basePlugins\ErrorHandling::setCatchPhpErrors()
 * \spectrum\builders\internal\normalizeSettings(true);  // see \spectrum\core\plugins\basePlugins\ErrorHandling::setCatchPhpErrors()
 *
 * @param mixed $settings
 */
function normalizeSettings($settings)
{
	$normalizedSettings = array(
		'catchPhpErrors' => null,
		'breakOnFirstPhpError' => null,
		'breakOnFirstMatcherFail' => null,
		'inputCharset' => null,
	);
	
	if ($settings !== null)
	{
		if (is_string($settings))
			$normalizedSettings['inputCharset'] = $settings;
		else if (is_int($settings) || is_bool($settings))
			$normalizedSettings['catchPhpErrors'] = $settings;
		else if (is_array($settings))
		{
			foreach ($settings as $settingName => $settingValue)
			{
				if (in_array($settingName, array('catchPhpErrors', 'breakOnFirstPhpError', 'breakOnFirstMatcherFail', 'inputCharset')))
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