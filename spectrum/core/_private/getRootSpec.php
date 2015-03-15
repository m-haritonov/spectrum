<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\_private;

use spectrum\core\config;
use spectrum\core\models\SpecInterface;

/**
 * @access private
 * @return SpecInterface
 */
function getRootSpec() {
	static $rootSpec = null;
	
	if (!isset($rootSpec)) {
		$specClass = config::getCoreClassReplacement('\spectrum\core\models\Spec');
		/** @var SpecInterface $rootSpec */
		$rootSpec = new $specClass();
		
		$getBaseMatchersFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\getBaseMatchers');
		foreach ($getBaseMatchersFunction() as $name => $function) {
			$rootSpec->getMatchers()->add($name, $function);
		}
	}
	
	return $rootSpec;
}