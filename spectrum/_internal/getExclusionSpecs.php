<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\_internal;

use spectrum\config;

function getExclusionSpecs()
{
	$reflection = new \ReflectionFunction(config::getFunctionReplacement('\spectrum\_internal\addExclusionSpec'));
	$staticVariables = $reflection->getStaticVariables();
	
	if (isset($staticVariables['exclusionSpecs']))
		return $staticVariables['exclusionSpecs'];
	else
		return array();
}