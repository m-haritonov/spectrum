<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\matchers;

/**
 * Returns true when code in $functionWithTestCode throws exception instance of $expectedClass (if not null) with
 * $expectedStringInMessage (if not null) and $expectedCode (if not null)
 * @return bool
 */
function throwsException($functionWithTestCode, $expectedClass = null, $expectedStringInMessage = null, $expectedCode = null)
{
	if (!is_callable($functionWithTestCode))
		throw new \spectrum\core\Exception('Function with test code is not callable');
	
	if ($expectedClass !== null && (!is_string($expectedClass) || $expectedClass === ''))
		throw new \spectrum\core\Exception('Expected class should be not empty string');
	
	if ($expectedStringInMessage !== null && !is_string($expectedStringInMessage))
		throw new \spectrum\core\Exception('Expected string in message should be a string');
	
	if ($expectedCode !== null && !is_int($expectedCode))
		throw new \spectrum\core\Exception('Expected code should be a integer');
	
	if ($expectedClass !== null && mb_substr($expectedClass, 0, 1) != '\\')
		$expectedClass = '\\' . $expectedClass;

	if ($expectedClass !== null && mb_strtolower($expectedClass) !== mb_strtolower('\Exception') && !is_subclass_of($expectedClass, '\Exception'))
		throw new \spectrum\core\Exception('Expected class should be subclass of "\Exception" class (now "' . $expectedClass . '" is not subclass of "\Exception" class)');

	try
	{
		call_user_func($functionWithTestCode);
	}
	catch (\Exception $e)
	{
		$class = '\\' . get_class($e);

		if ($expectedClass !== null && mb_strtolower($class) !== mb_strtolower($expectedClass) && !is_subclass_of($class, $expectedClass))
			return false;
		
		if ($expectedStringInMessage !== null)
		{
			if ($expectedStringInMessage === '' && $e->getMessage() !== '')
				return false;
			else if ($expectedStringInMessage !== '' && mb_stripos($e->getMessage(), $expectedStringInMessage) === false)
				return false;
		}

		if ($expectedCode !== null && $e->getCode() !== $expectedCode)
			return false;

		return true;
	}

	return false;
}