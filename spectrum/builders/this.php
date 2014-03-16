<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\builders;

use spectrum\config;
use spectrum\Exception;

function this()
{
	$isRunningStateFunction = config::getFunctionReplacement('\spectrum\_internal\isRunningState');
	if (!$isRunningStateFunction())
		throw new Exception('Builder "this" should be call only at running state');

	$getCurrentContextDataFunction = config::getFunctionReplacement('\spectrum\_internal\getCurrentContextData');
	return $getCurrentContextDataFunction();
}