<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\_private;

use spectrum\core\config;
use spectrum\core\models\DataInterface;
use spectrum\core\models\SpecInterface;

/**
 * @access private
 * @return null|DataInterface
 */
function getCurrentData() {
	$getCurrentRunningEndingSpecFunction = config::getCoreFunctionReplacement('\spectrum\core\_private\getCurrentRunningEndingSpec');
	/** @var null|SpecInterface $spec */
	$spec = $getCurrentRunningEndingSpecFunction();
	if ($spec) {
		return $spec->getData();
	} else {
		return null;
	}
}