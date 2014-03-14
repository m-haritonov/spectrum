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
		$specClass = config::getSpecClass();
		$rootSpec = new $specClass;
		
		\spectrum\_internal\loadBaseMatchers($rootSpec);
	}
	
	return $rootSpec;
}