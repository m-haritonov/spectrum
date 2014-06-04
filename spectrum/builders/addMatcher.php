<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\builders;
use spectrum\config;
use spectrum\Exception;

/**
 * @throws \spectrum\Exception If called not at building state
 * @param  string $name
 * @param  callback $function
 */
function addMatcher($name, $function)
{
	$isRunningStateFunction = config::getFunctionReplacement('\spectrum\_internals\isRunningState');
	if ($isRunningStateFunction())
		throw new Exception('Builder "addMatcher" should be call only at building state');

	$getCurrentBuildingSpecFunction = config::getFunctionReplacement('\spectrum\_internals\getCurrentBuildingSpec');
	return $getCurrentBuildingSpecFunction()->matchers->add($name, $function);
}