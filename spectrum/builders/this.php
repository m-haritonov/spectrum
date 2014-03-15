<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\builders;

use spectrum\config;

function this()
{
	$isRunningStateFunction = config::getFunctionReplacement('\spectrum\_internal\isRunningState');
	if (!$isRunningStateFunction())
		throw new \spectrum\builders\Exception('Builder "this" should be call only at running state');

	$getContextDataFunction = config::getFunctionReplacement('\spectrum\_internal\getContextData');
	return $getContextDataFunction();
}