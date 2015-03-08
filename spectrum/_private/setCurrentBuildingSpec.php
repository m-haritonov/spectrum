<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_private;

use spectrum\core\SpecInterface;

/**
 * @access private
 * @see getCurrentBuildingSpec()
 */
function setCurrentBuildingSpec(SpecInterface $spec = null) {
	static $buildingSpec = null;
	$buildingSpec = $spec;
}