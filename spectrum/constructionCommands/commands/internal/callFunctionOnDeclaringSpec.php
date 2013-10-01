<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\commands\internal;

use spectrum\config;
use spectrum\core\SpecInterface;

function callFunctionOnDeclaringSpec($storage, $function, SpecInterface $spec)
{
	$callBrokerClass = config::getConstructionCommandCallBrokerClass();
	$specBackup = $callBrokerClass::internal_getCurrentDeclaringSpec();
	$callBrokerClass::internal_setCurrentDeclaringSpec($spec);
	$returnValue = call_user_func($function);
	$callBrokerClass::internal_setCurrentDeclaringSpec($specBackup);
	return $returnValue;
}