<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins\reports\drivers\html\components\code;

use spectrum\config;

class variable extends \spectrum\core\plugins\reports\drivers\html\components\component
{
	static public function getHtml($variable, $depth = 0, $inputCharset = null)
	{
		$convertLatinCharsToLowerCaseFunction = config::getFunctionReplacement('\spectrum\_internal\convertLatinCharsToLowerCase');
		$type = $convertLatinCharsToLowerCaseFunction(gettype($variable));

		if ($type === 'boolean')
			return static::callComponentMethod('code\variables\boolVar', 'getHtml', array($variable));
		else if ($type === 'integer')
			return static::callComponentMethod('code\variables\intVar', 'getHtml', array($variable));
		else if ($type === 'double')
			return static::callComponentMethod('code\variables\floatVar', 'getHtml', array($variable));
		else if ($type === 'string')
			return static::callComponentMethod('code\variables\stringVar', 'getHtml', array($variable, $inputCharset));
		else if ($type === 'array')
			return static::callComponentMethod('code\variables\arrayVar', 'getHtml', array($variable, $depth, $inputCharset));
		else if ($type === 'object')
		{
			$closure = function(){};
			if ($variable instanceof $closure)
				return static::callComponentMethod('code\variables\functionVar', 'getHtml', array($variable, $inputCharset));
			else
				return static::callComponentMethod('code\variables\objectVar', 'getHtml', array($variable, $depth, $inputCharset));
		}
		else if ($type === 'resource')
			return static::callComponentMethod('code\variables\resourceVar', 'getHtml', array($variable, $inputCharset));
		else if ($type === 'null')
			return static::callComponentMethod('code\variables\nullVar', 'getHtml');
		else
			return static::callComponentMethod('code\variables\unknownVar', 'getHtml', array($variable, $inputCharset));
	}
}