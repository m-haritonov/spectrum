<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\builders;
use spectrum\config;
use spectrum\Exception;

/**
 * Add message to Messages plugin.
 * @throws \spectrum\Exception If called not at running state
 */
function message($message)
{
	$isRunningStateFunction = config::getFunctionReplacement('\spectrum\_internal\isRunningState');
	if (!$isRunningStateFunction())
		throw new Exception('Builder "message" should be call only at running state');
	
	$getCurrentRunningEndingSpecFunction = config::getFunctionReplacement('\spectrum\_internal\getCurrentRunningEndingSpec');
	$getCurrentRunningEndingSpecFunction()->messages->add($message);
}