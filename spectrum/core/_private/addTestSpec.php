<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\_private;

use spectrum\core\models\SpecInterface;

/**
 * @access private
 */
function addTestSpec(SpecInterface $spec) {
	// This variable is used by getTestSpecs function
	static $data;
	
	if (!$data) {
		$data = new \stdClass();
	}
	
	$data->specs[] = $spec;
}