<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\builders\internal;

function getExclusionSpecs()
{
	$reflection = new \ReflectionFunction('spectrum\builders\internal\addExclusionSpec');
	$staticVariables = $reflection->getStaticVariables();
	
	if (isset($staticVariables['exclusionSpecs']))
		return $staticVariables['exclusionSpecs'];
	else
		return array();
}