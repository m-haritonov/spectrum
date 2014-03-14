<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\builders;

/**
 * Add message to Messages plugin.
 * @throws \spectrum\builders\Exception If called not at running state
 */
function message($message)
{
	if (!\spectrum\_internal\isRunningState())
		throw new \spectrum\builders\Exception('Builder "message" should be call only at running state');
	
	\spectrum\_internal\getRunningEndingSpec()->messages->add($message);
}