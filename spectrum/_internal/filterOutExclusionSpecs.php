<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\_internal;

function filterOutExclusionSpecs(array $specs)
{
	$exclusionSpecs = \spectrum\_internal\getExclusionSpecs();
	
	$filteredSpecs = array();
	foreach ($specs as $spec)
	{
		if (!in_array($spec, $exclusionSpecs, true))
			$filteredSpecs[] = $spec;
	}
	
	return $filteredSpecs;
}