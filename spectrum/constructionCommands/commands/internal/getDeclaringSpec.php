<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\constructionCommands\commands\internal;

use spectrum\config;

/**
 * @return \spectrum\core\SpecInterface|null
 */
function getDeclaringSpec($storage)
{
	$callBrokerClass = config::getConstructionCommandCallBrokerClass();
	if (@$storage['internal_setDeclaringSpec']['declaringSpec'])
		return $storage['internal_setDeclaringSpec']['declaringSpec'];
	else
		return $callBrokerClass::internal_getRootSpec();
}