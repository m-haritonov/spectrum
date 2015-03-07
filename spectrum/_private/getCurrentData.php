<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_private;

use spectrum\config;
use spectrum\core\DataInterface;
use spectrum\core\SpecInterface;

/**
 * @access private
 * @return null|DataInterface
 */
function getCurrentData() {
	$getCurrentRunningEndingSpecFunction = config::getFunctionReplacement('\spectrum\_private\getCurrentRunningEndingSpec');
	/** @var null|SpecInterface $spec */
	$spec = $getCurrentRunningEndingSpecFunction();
	if ($spec) {
		return $spec->getData();
	} else {
		return null;
	}
}