<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\builders;

use spectrum\core\Exception;
use spectrum\core\config;

/**
 * Creates assertion.
 * @throws \spectrum\core\Exception If called not at running state
 * @param mixed $testedValue
 * @return \spectrum\core\AssertionInterface
 */
function be($testedValue) {
	$isRunningStateFunction = config::getFunctionReplacement('\spectrum\_private\isRunningState');
	if (!$isRunningStateFunction()) {
		throw new Exception('Function "be" should be call only at running state');
	}

	$assertionClass = config::getClassReplacement('\spectrum\core\Assertion');
	$getCurrentRunningEndingSpecFunction = config::getFunctionReplacement('\spectrum\_private\getCurrentRunningEndingSpec');
	return new $assertionClass($getCurrentRunningEndingSpecFunction(), $testedValue);
}