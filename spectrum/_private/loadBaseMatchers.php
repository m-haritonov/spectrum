<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_private;

/**
 * @access private
 * @return array
 */
function loadBaseMatchers() {
	$matchers = array(
		'eq' => null,
		'gt' => null,
		'gte' => null,
		'ident' => null,
		'is' => null,
		'lt' => null,
		'lte' => null,
		'throwsException' => null,
	);
	
	foreach ($matchers as $matcherName => $functionName) {
		$fileName = __DIR__ . '/../core/matchers/' . $matcherName . '.php';
		$functionName = '\spectrum\core\matchers\\' . $matcherName;
		
		require_once $fileName;
		$matchers[$matcherName] = $functionName;
	}
	
	return $matchers;
}