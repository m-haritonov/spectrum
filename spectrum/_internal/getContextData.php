<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\_internal;

use spectrum\config;

function getContextData()
{
	$getRunningEndingSpecFunction = config::getFunctionReplacement('\spectrum\_internal\getRunningEndingSpec');
	return $getRunningEndingSpecFunction()->test->getContextData();
}