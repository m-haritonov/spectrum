<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\builders\internal;

/**
 * @example
 * manager::setSettings($spec, array(
 *     'catchPhpErrors' => -1,            // see \spectrum\core\plugins\basePlugins\ErrorHandling::setCatchPhpErrors()
 *     'breakOnFirstPhpError' => true,    // see \spectrum\core\plugins\basePlugins\ErrorHandling::setBreakOnFirstPhpError()
 *     'breakOnFirstMatcherFail' => true, // see \spectrum\core\plugins\basePlugins\ErrorHandling::setBreakOnFirstMatcherFail()
 *     'inputCharset' => 'windows-1251', // see \core\plugins\basePlugins\Charset::setInputCharset()
 * ));
 *
 * @example
 * manager::setSettings($spec, 'windows-1251'); // see \core\plugins\basePlugins\Charset::setInputCharset()
 *
 * @example
 * manager::setSettings($spec, E_ALL); // see \spectrum\core\plugins\basePlugins\ErrorHandling::setCatchPhpErrors()
 * manager::setSettings($spec, true);  // see \spectrum\core\plugins\basePlugins\ErrorHandling::setCatchPhpErrors()
 *
 * @param mixed $settings
 */
function setSettingsToSpec(\spectrum\core\SpecInterface $spec, $settings)
{
	if (is_string($settings))
		$spec->charset->setInputCharset($settings);
	else if (is_int($settings) || is_bool($settings))
		$spec->errorHandling->setCatchPhpErrors($settings);
	else if (is_array($settings))
	{
		foreach ($settings as $settingName => $settingValue)
		{
			if ($settingName == 'catchPhpErrors')
				$spec->errorHandling->setCatchPhpErrors($settingValue);
			else if ($settingName == 'breakOnFirstPhpError')
				$spec->errorHandling->setBreakOnFirstPhpError($settingValue);
			else if ($settingName == 'breakOnFirstMatcherFail')
				$spec->errorHandling->setBreakOnFirstMatcherFail($settingValue);
			else if ($settingName == 'inputCharset')
				$spec->charset->setInputCharset($settingValue);
			else
				throw new \spectrum\builders\Exception('Invalid setting "' . $settingName . '" in spec with name "' . $spec->output->convertToOutputCharset($spec->getName()) . '"');
		}
	}
	else
		throw new \spectrum\builders\Exception('Invalid settings variable type ("' . gettype($settings) . '") in spec with name "' . $spec->output->convertToOutputCharset($spec->getName()) . '"');
}