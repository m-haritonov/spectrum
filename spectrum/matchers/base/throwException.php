<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\matchers\base;

/**
 * Return true, if code in $callbackWithTestedCode throw exception instance of $expectedClass with
 * $expectedStringInMessage (if not null) and $expectedCode (if not null)
 * @return bool
 */
function throwException($callbackWithTestedCode, $expectedClass = '\Exception', $expectedStringInMessage = null, $expectedCode = null)
{
	if ($expectedClass == null)
		$expectedClass = '\Exception';

	if (!is_subclass_of($expectedClass, '\Exception') && $expectedClass != '\Exception')
		throw new \spectrum\matchers\Exception('Excepted class "' . $expectedClass . '" should be subclass of "\Exception"');

	try {
		call_user_func($callbackWithTestedCode);
	}
	catch (\Exception $e)
	{
		$actualClass = '\\' . get_class($e);

		if ($actualClass == $expectedClass || is_subclass_of($actualClass, $expectedClass))
		{
			$actualMessage = $e->getMessage();
			$actualCode = $e->getCode();

			if ($expectedStringInMessage !== null && mb_stripos($actualMessage, $expectedStringInMessage) === false)
				throw new \spectrum\matchers\Exception('Actual message "' . $actualMessage . '" not contains expected string "' . $expectedStringInMessage . '"');

			if ($expectedCode !== null && $actualCode != $expectedCode)
				throw new \spectrum\matchers\Exception('Actual code "' . $actualCode . '" not equal to expected code "' . $expectedCode . '"');

			return true;
		}
	}

	throw new \spectrum\matchers\Exception('Excepted exception "' . $expectedClass . '" not thrown');
}