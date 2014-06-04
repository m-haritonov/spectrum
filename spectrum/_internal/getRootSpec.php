<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internal;

use spectrum\config;

/**
 * @access private
 */
function getRootSpec()
{
	static $rootSpec = null;
	
	if (!isset($rootSpec))
	{
		$specClass = config::getClassReplacement('\spectrum\core\Spec');
		$rootSpec = new $specClass;
		
		$loadBaseMatchersFunction = config::getFunctionReplacement('\spectrum\_internal\loadBaseMatchers');
		foreach ($loadBaseMatchersFunction() as $name => $function)
			$rootSpec->matchers->add($name, $function);
	}
	
	return $rootSpec;
}