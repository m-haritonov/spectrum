<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\commands\internal;

/**
 * @example
 * manager::setSettings($spec, array(
 *     'catchPhpErrors' => -1,            // see \spectrum\core\plugins\basePlugins\errorHandling\ErrorHandling::setCatchPhpErrors()
 *     'breakOnFirstPhpError' => true,    // see \spectrum\core\plugins\basePlugins\errorHandling\ErrorHandling::setBreakOnFirstPhpError()
 *     'breakOnFirstMatcherFail' => true, // see \spectrum\core\plugins\basePlugins\errorHandling\ErrorHandling::setBreakOnFirstMatcherFail()
 *     'inputEncoding' => 'windows-1251', // see \core\plugins\basePlugins\Output::setInputEncoding()
 * ));
 *
 * @example
 * manager::setSettings($spec, 'windows-1251'); // see \core\plugins\basePlugins\Output::setInputEncoding()
 *
 * @example
 * manager::setSettings($spec, E_ALL); // see \spectrum\core\plugins\basePlugins\errorHandling\ErrorHandling::setCatchPhpErrors()
 * manager::setSettings($spec, true);  // see \spectrum\core\plugins\basePlugins\errorHandling\ErrorHandling::setCatchPhpErrors()
 *
 * @param mixed $settings
 */
function setSpecSettings($storage, \spectrum\core\SpecInterface $spec, $settings)
{
	if (is_string($settings))
		$spec->output->setInputEncoding($settings);
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
			else if ($settingName == 'inputEncoding')
				$spec->output->setInputEncoding($settingValue);
			else
				throw new \spectrum\constructionCommands\Exception('Invalid setting "' . $settingName . '" in spec with name "' . $spec->output->convertToOutputEncoding($spec->getName()) . '"');
		}
	}
	else
		throw new \spectrum\constructionCommands\Exception('Invalid settings variable type ("' . gettype($settings) . '") in spec with name "' . $spec->output->convertToOutputEncoding($spec->getName()) . '"');
}