<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internal;

use spectrum\config;

function getTestSpecs()
{
	$reflection = new \ReflectionFunction(config::getFunctionReplacement('\spectrum\_internal\addTestSpec'));
	$staticVariables = $reflection->getStaticVariables();
	
	if (isset($staticVariables['specs']))
		return $staticVariables['specs'];
	else
		return array();
}