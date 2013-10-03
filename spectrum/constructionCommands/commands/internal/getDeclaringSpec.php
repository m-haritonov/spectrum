<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
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