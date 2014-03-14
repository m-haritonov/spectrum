<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\_internal;

use spectrum\core\SpecInterface;

function callFunctionOnBuildingSpec($function, SpecInterface $spec)
{
	$specBackup = \spectrum\_internal\getBuildingSpec();
	
	\spectrum\_internal\setBuildingSpec($spec);
	$returnValue = $function();
	\spectrum\_internal\setBuildingSpec($specBackup);
	
	return $returnValue;
}