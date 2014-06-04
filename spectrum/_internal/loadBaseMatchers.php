<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internal;

function loadBaseMatchers()
{
	$matchers = array(
		'eq' => null,
		'false' => null,
		'gt' => null,
		'gte' => null,
		'ident' => null,
		'instanceof' => null,
		'lt' => null,
		'lte' => null,
		'null' => null,
		'throwsException' => null,
		'true' => null,
	);
	
	foreach ($matchers as $matcherName => $functionName)
	{
		$fileName = __DIR__ . '/../matchers/' . $matcherName . '.php';
		$functionName = '\spectrum\matchers\\' . $matcherName;
		
		// "instanceof" is reserved word and forbidden to use as function name (but allowed to use as property name)
		if ((string) $matcherName === 'instanceof')
		{
			$fileName = __DIR__ . '/../matchers/instanceofMatcher.php';
			$functionName = '\spectrum\matchers\instanceofMatcher';
		}
		
		require_once $fileName;
		$matchers[$matcherName] = $functionName;
	}
	
	return $matchers;
}