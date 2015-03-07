<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internals;

use spectrum\config;
use spectrum\core\SpecInterface;

/**
 * @access private
 * @return SpecInterface
 */
function getRootSpec() {
	static $rootSpec = null;
	
	if (!isset($rootSpec)) {
		$specClass = config::getClassReplacement('\spectrum\core\Spec');
		/** @var SpecInterface $rootSpec */
		$rootSpec = new $specClass();
		
		$loadBaseMatchersFunction = config::getFunctionReplacement('\spectrum\_internals\loadBaseMatchers');
		foreach ($loadBaseMatchersFunction() as $name => $function) {
			$rootSpec->getMatchers()->add($name, $function);
		}
	}
	
	return $rootSpec;
}