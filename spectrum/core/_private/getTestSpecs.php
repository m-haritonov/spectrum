<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\_private;

use spectrum\core\config;

/**
 * @access private
 * @return array
 */
function getTestSpecs() {
	static $data;
	if (!$data) {
		$reflection = new \ReflectionFunction(config::getCoreFunctionReplacement('\spectrum\core\_private\addTestSpec'));
		$staticVariables = $reflection->getStaticVariables();
		$data = $staticVariables['data'];
	}
	
	if (isset($data->specs)) {
		return $data->specs;
	} else {
		return array();
	}
}