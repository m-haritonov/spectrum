<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\builders;

use spectrum\config;
use spectrum\Exception;

function data()
{
	$isRunningStateFunction = config::getFunctionReplacement('\spectrum\_internal\isRunningState');
	if (!$isRunningStateFunction())
		throw new Exception('Builder "data" should be call only at running state');

	$getCurrentDataFunction = config::getFunctionReplacement('\spectrum\_internal\getCurrentData');
	return $getCurrentDataFunction();
}