<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\_internal;

use spectrum\config;

function getRootSpec()
{
	static $rootSpec = null;
	
	if (!isset($rootSpec))
	{
		$specClass = config::getClassReplacement('\spectrum\core\Spec');
		$rootSpec = new $specClass;
		
		$loadBaseMatchersFunction = config::getFunctionReplacement('\spectrum\_internal\loadBaseMatchers');
		$loadBaseMatchersFunction($rootSpec);
	}
	
	return $rootSpec;
}