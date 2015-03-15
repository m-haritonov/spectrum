<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\constructs;

use spectrum\core\Exception;
use spectrum\core\config;

/**
 * Creates assertion.
 * @throws \spectrum\core\Exception If called not at running state
 * @param mixed $testedValue
 * @return \spectrum\core\models\AssertionInterface
 */
function be($testedValue) {
	$isRunningStateFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\isRunningState');
	if (!$isRunningStateFunction()) {
		throw new Exception('Function "be" should be call only at running state');
	}

	$assertionClass = config::getCoreClassReplacement('\spectrum\core\models\Assertion');
	$getCurrentRunningEndingSpecFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\getCurrentRunningEndingSpec');
	return new $assertionClass($getCurrentRunningEndingSpecFunction(), $testedValue);
}