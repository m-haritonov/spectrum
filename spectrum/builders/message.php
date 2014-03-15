<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\builders;
use spectrum\config;

/**
 * Add message to Messages plugin.
 * @throws \spectrum\builders\Exception If called not at running state
 */
function message($message)
{
	$isRunningStateFunction = config::getFunctionReplacement('\spectrum\_internal\isRunningState');
	if (!$isRunningStateFunction())
		throw new \spectrum\builders\Exception('Builder "message" should be call only at running state');
	
	$getRunningEndingSpecFunction = config::getFunctionReplacement('\spectrum\_internal\getRunningEndingSpec');
	$getRunningEndingSpecFunction()->messages->add($message);
}