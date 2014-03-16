<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\_internal;

use spectrum\core\SpecInterface;

function addTestSpec(SpecInterface $spec)
{
	static $specs = array();
	$specs[] = $spec;
}