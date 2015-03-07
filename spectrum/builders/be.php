<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\builders;

use spectrum\config;
use spectrum\Exception;

/**
 * Creates assertion.
 * @throws \spectrum\Exception If called not at running state
 * @param mixed $testedValue
 * @return \spectrum\core\AssertionInterface
 */
function be($testedValue) {
	$isRunningStateFunction = config::getFunctionReplacement('\spectrum\_internals\isRunningState');
	if (!$isRunningStateFunction()) {
		throw new Exception('Builder "be" should be call only at running state');
	}

	$assertionClass = config::getClassReplacement('\spectrum\core\Assertion');
	$getCurrentRunningEndingSpecFunction = config::getFunctionReplacement('\spectrum\_internals\getCurrentRunningEndingSpec');
	return new $assertionClass($getCurrentRunningEndingSpecFunction(), $testedValue);
}