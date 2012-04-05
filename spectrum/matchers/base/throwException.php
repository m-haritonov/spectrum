<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\matchers\base;

/**
 * Return true, if code in $callbackWithActualCode throw exception instance of $expectedClass with
 * $expectedStringInMessage (if not null) and $expectedCode (if not null)
 * @return bool
 */
function throwException($callbackWithActualCode, $expectedClass = '\Exception', $expectedStringInMessage = null, $expectedCode = null)
{
	if ($expectedClass == null)
		$expectedClass = '\Exception';

	if (!is_subclass_of($expectedClass, '\Exception') && $expectedClass != '\Exception')
		throw new \spectrum\matchers\Exception('Excepted class should be subclass of \Exception');

	try {
		call_user_func($callbackWithActualCode);
	}
	catch (\Exception $e)
	{
		$actualClass = '\\' . get_class($e);
		// Class found
		if ($actualClass == $expectedClass || is_subclass_of($actualClass, $expectedClass))
		{
			$isOk = true;
			if ($expectedStringInMessage !== null && mb_stripos($e->getMessage(), $expectedStringInMessage) === false)
				$isOk = false;
			elseif ($expectedCode !== null && $e->getCode() != $expectedCode)
				$isOk = false;

			return $isOk;
		}
	}

	return false;
}