<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\commands\internal;

use spectrum\config;

function getRootSpec($storage)
{
	if (!@$storage['_self_']['rootSpec'])
	{
		$specClass = config::getSpecClass();
		$storage['_self_']['rootSpec'] = new $specClass;
		
		$callBrokerClass = config::getConstructionCommandCallBrokerClass();
		$callBrokerClass::internal_loadBaseMatchers($storage['_self_']['rootSpec']);
	}
				
	return $storage['_self_']['rootSpec'];
}