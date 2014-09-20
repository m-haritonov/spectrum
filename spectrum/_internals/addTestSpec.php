<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internals;

use spectrum\core\SpecInterface;

/**
 * @access private
 */
function addTestSpec(SpecInterface $spec) {
	static $specs = array();
	$specs[] = $spec;
}