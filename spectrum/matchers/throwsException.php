<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\matchers;

use spectrum\config;
use spectrum\Exception;

/**
 * Returns true when code in $functionWithTestCode throws exception instance of $expectedClass (if not null) with
 * $expectedStringInMessage (if not null) and $expectedCode (if not null)
 * @return bool
 */
function throwsException($functionWithTestCode, $expectedClass = null, $expectedStringInMessage = null, $expectedCode = null)
{
	if (!is_callable($functionWithTestCode))
		throw new Exception('Function with test code is not callable');
	
	if ($expectedClass !== null && (!is_string($expectedClass) || $expectedClass === ''))
		throw new Exception('Expected class should be not empty string');
	
	if ($expectedStringInMessage !== null && !is_string($expectedStringInMessage))
		throw new Exception('Expected string in message should be a string');
	
	if ($expectedCode !== null && !is_int($expectedCode))
		throw new Exception('Expected code should be a integer');
	
	if ($expectedClass !== null && mb_substr($expectedClass, 0, 1, 'us-ascii') != '\\')
		$expectedClass = '\\' . $expectedClass;

	$convertLatinCharsToLowerCaseFunction = config::getFunctionReplacement('\spectrum\_internal\convertLatinCharsToLowerCase');
	
	// Class names are case-insensitive for A-Z chars and case-sensitive for chars with codes from 127 through 255 (0x7f-0xff)
	$expectedClassWithLatinLowerCase = $convertLatinCharsToLowerCaseFunction($expectedClass);
	
	if ($expectedClass !== null && $expectedClassWithLatinLowerCase !== '\exception' && !is_subclass_of($expectedClass, '\Exception'))
		throw new Exception('Expected class should be subclass of "\Exception" class (now "' . $expectedClass . '" is not subclass of "\Exception" class)');

	try
	{
		$functionWithTestCode();
	}
	catch (\Exception $e)
	{
		$actualClass = '\\' . get_class($e);
		
		// Class names are case-insensitive for A-Z chars and case-sensitive for chars with codes from 127 through 255 (0x7f-0xff)
		$actualClassWithLatinLowerCase = $convertLatinCharsToLowerCaseFunction($actualClass);

		if ($expectedClass !== null && $actualClassWithLatinLowerCase !== $expectedClassWithLatinLowerCase && !is_subclass_of($actualClass, $expectedClass))
			return false;
		
		if ($expectedStringInMessage !== null)
		{
			if ($expectedStringInMessage === '' && $e->getMessage() !== '')
				return false;
			else if ($expectedStringInMessage !== '' && mb_strpos($e->getMessage(), $expectedStringInMessage, null, 'us-ascii') === false)
				return false;
		}

		if ($expectedCode !== null && $e->getCode() !== $expectedCode)
			return false;

		return true;
	}

	return false;
}