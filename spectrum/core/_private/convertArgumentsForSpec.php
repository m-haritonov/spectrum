<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\_private;

use spectrum\core\config;
use spectrum\core\Exception;

/**
 * @access private
 * @param string $constructName
 * @return array
 */
function convertArgumentsForSpec(array $arguments, $constructName) {
	$convertArgumentsFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\convertArguments');
	$arguments = $convertArgumentsFunction($arguments, array(
		array('closure:body'),                                                                                  // function(\Closure $body)
		array('closure:body', 'null|scalar|array:settings'),                                                    // function(\Closure $body, null|scalar|array $settings)
		array('array|closure:contexts', 'closure:body'),                                                        // function(array|\Closure $contexts, \Closure $body)
		array('array|closure:contexts', 'closure:body', 'null|scalar|array:settings'),                          // function(array|\Closure $contexts, \Closure $body, null|scalar|array $settings)
		array('null|scalar:name', 'closure:body'),                                                              // function(null|scalar $name, \Closure $body)
		array('null|scalar:name', 'closure:body', 'null|scalar|array:settings'),                                // function(null|scalar $name, \Closure $body, null|scalar|array $settings)
		array('null|scalar:name', 'null|array|closure:contexts', 'closure:body'),                               // function(null|scalar $name, null|array|\Closure $contexts, \Closure $body)
		array('null|scalar:name', 'null|array|closure:contexts', 'closure:body', 'null|scalar|array:settings'), // function(null|scalar $name, null|array|\Closure $contexts, \Closure $body, null|scalar|array $settings)
	), array(
		'name' => null,
		'contexts' => null,
		'body' => null,
		'settings' => null,
	));
	
	if ($arguments === null) {
		throw new Exception('Incorrect arguments in "' . $constructName . '" construct');
	}
	
	return $arguments;
}