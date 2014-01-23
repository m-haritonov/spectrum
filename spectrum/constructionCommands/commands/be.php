<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\constructionCommands\commands;
use spectrum\config;

/**
 * @throws \spectrum\constructionCommands\Exception If called not at running state
 * @param  mixed $testedValue
 * @return \spectrum\core\Assert
 */
function be($storage, $testedValue)
{
	$callBrokerClass = config::getConstructionCommandCallBrokerClass();
	if (!$callBrokerClass::internal_isRunningState())
		throw new \spectrum\constructionCommands\Exception('Construction command "be" should be call only at running state');

	$assertClass = config::getAssertClass();
	return new $assertClass($callBrokerClass::internal_getRunningEndingSpec(), $testedValue);
}