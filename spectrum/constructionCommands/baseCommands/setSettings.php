<?php
/*
 * Spectrum
 *
 * Copyright (c) 2011 Mikhail Kharitonov <mvkharitonov@gmail.com>
 * All rights reserved.
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 */

namespace spectrum\constructionCommands\baseCommands;

/**
 * Function with base functional for construction commands describe() and context().
 *
 * @example
 * Manager::setSettings($spec, array(
 *     'catchExceptions' => true,         // see core\plugins\basePlugins\ErrorHandling::setCatchExceptions()
 *     'catchPhpErrors' => -1,            // see core\plugins\basePlugins\ErrorHandling::setCatchPhpErrors()
 *     'breakOnFirstPhpError' => true,    // see core\plugins\basePlugins\ErrorHandling::setBreakOnFirstPhpError()
 *     'breakOnFirstMatcherFail' => true, // see core\plugins\basePlugins\ErrorHandling::setBreakOnFirstMatcherFail()
 *     'inputEncoding' => 'windows-1251', // see core\plugins\basePlugins\Output::setInputEncoding()
 * ));
 *
 * @example
 * Manager::setSettings($spec, 'windows-1251'); // see core\plugins\basePlugins\Output::setInputEncoding()
 *
 * @example
 * Manager::setSettings($spec, E_ALL); // see core\plugins\basePlugins\ErrorHandling::setCatchPhpErrors()
 * Manager::setSettings($spec, true);  // see core\plugins\basePlugins\ErrorHandling::setCatchPhpErrors()
 *
 * @param mixed $settings
 *
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
function setSettings(\spectrum\core\SpecInterface $spec, $settings)
{
	if (is_string($settings)) // it('foo', function(){}, 'windows-1251')
		$spec->output->setInputEncoding($settings);
	else if (is_int($settings) || is_bool($settings)) // it('foo', function(){}, E_ALL)
		$spec->errorHandling->setCatchPhpErrors($settings);
	else if (is_array($settings))
	{
		foreach ($settings as $settingName => $settingValue)
		{
			if ($settingName == 'catchExceptions')
				$spec->errorHandling->setCatchExceptions($settingValue);
			else if ($settingName == 'catchPhpErrors')
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
		throw new \spectrum\constructionCommands\Exception('Invalid $settings type ("' . gettype($settings) . '") in spec with name "' . $spec->output->convertToOutputEncoding($spec->getName()) . '"');
}