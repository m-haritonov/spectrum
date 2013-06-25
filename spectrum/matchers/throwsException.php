<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\matchers;

/**
 * Return true, if code in $testedValue throws exception instance of $expectedClass with
 * $expectedStringInMessage (if not null) and $expectedCode (if not null)
 * @return bool
 */
function throwsException($functionWithTestedCode, $expectedClass = '\Exception', $expectedStringInMessage = null, $expectedCode = null)
{
	if ($expectedClass == null)
		$expectedClass = '\Exception';

	if (!is_subclass_of($expectedClass, '\Exception') && $expectedClass != '\Exception')
		throw new \spectrum\core\asserts\Exception('Excepted class "' . $expectedClass . '" should be subclass of "\Exception" in "' . __FUNCTION__ . '" matcher');

	try
	{
		call_user_func($functionWithTestedCode);
	}
	catch (\Exception $e)
	{
		$actualClass = '\\' . get_class($e);

		if ($actualClass == $expectedClass || is_subclass_of($actualClass, $expectedClass))
		{
			$actualMessage = $e->getMessage();
			$actualCode = $e->getCode();

			if ($expectedStringInMessage !== null && mb_stripos($actualMessage, $expectedStringInMessage) === false)
				return false;

			if ($expectedCode !== null && $actualCode != $expectedCode)
				return false;

			return true;
		}
	}

	return false;
}