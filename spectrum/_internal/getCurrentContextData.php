<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\_internal;

use spectrum\config;

function getCurrentContextData()
{
	$getCurrentRunningEndingSpecFunction = config::getFunctionReplacement('\spectrum\_internal\getCurrentRunningEndingSpec');
	return $getCurrentRunningEndingSpecFunction()->test->getContextData();
}