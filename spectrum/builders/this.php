<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\builders;

function this()
{
	if (!\spectrum\_internal\isRunningState())
		throw new \spectrum\builders\Exception('Builder "this" should be call only at running state');

	return \spectrum\_internal\getRunningEndingSpec()->contexts->getContextData();
}