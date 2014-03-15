<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\_internal;

use spectrum\config;

function filterOutExclusionSpecs(array $specs)
{
	$getExclusionSpecsFunction = config::getFunctionReplacement('\spectrum\_internal\getExclusionSpecs');
	$exclusionSpecs = $getExclusionSpecsFunction();
	
	$filteredSpecs = array();
	foreach ($specs as $spec)
	{
		if (!in_array($spec, $exclusionSpecs, true))
			$filteredSpecs[] = $spec;
	}
	
	return $filteredSpecs;
}