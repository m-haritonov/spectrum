<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\constructionCommands\commands\internal;

use spectrum\config;
use spectrum\core\SpecInterface;

function callFunctionOnDeclaringSpec($storage, $function, SpecInterface $spec)
{
	$callBrokerClass = config::getConstructionCommandCallBrokerClass();
	$specBackup = $callBrokerClass::internal_getDeclaringSpec();
	
	$callBrokerClass::internal_setDeclaringSpec($spec);
	$returnValue = call_user_func($function);
	$callBrokerClass::internal_setDeclaringSpec($specBackup);
	
	return $returnValue;
}