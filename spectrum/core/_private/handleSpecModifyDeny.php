<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\_private;

use spectrum\core\SpecInterface;
use spectrum\core\Exception;

/**
 * @access private
 */
function handleSpecModifyDeny(SpecInterface $spec, $callerObject, $callerFunctionName) {
	foreach (array_merge(array($spec), $spec->getAncestorRootSpecs()) as $spec2) {
		/** @var SpecInterface $spec2 */
		if ($spec2->isRunning()) {
			throw new Exception('Call of "\\' . get_class($callerObject) . '::' . $callerFunctionName . '" method is forbidden on run');
		}
	}
}