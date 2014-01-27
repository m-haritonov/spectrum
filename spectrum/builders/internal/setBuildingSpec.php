<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\builders\internal;

use spectrum\core\SpecInterface;

/**
 * @see getBuildingSpec()
 */
function setBuildingSpec(SpecInterface $spec = null)
{
	static $buildingSpec = null;
	$buildingSpec = $spec;
}