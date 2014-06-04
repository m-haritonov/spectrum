<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum;

function run()
{
	if (!config::isLocked())
		config::lock();
	
	$getRootSpecFunction = config::getFunctionReplacement('\spectrum\_internals\getRootSpec');
	return $getRootSpecFunction()->run();
}