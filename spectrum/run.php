<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum;

function run()
{
	if (!config::isLocked())
		config::lock();
	
	$getRootSpecFunction = config::getFunctionReplacement('\spectrum\_internal\getRootSpec');
	return $getRootSpecFunction()->run();
}