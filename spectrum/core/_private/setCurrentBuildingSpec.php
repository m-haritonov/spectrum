<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\_private;

use spectrum\core\models\SpecInterface;

/**
 * @access private
 * @see getCurrentBuildingSpec()
 */
function setCurrentBuildingSpec(SpecInterface $spec = null) {
	// This variable is used by getCurrentBuildingSpec function
	static $data;
	
	if (!$data) {
		$data = new \stdClass();
	}
	
	$data->buildingSpec = $spec;
}