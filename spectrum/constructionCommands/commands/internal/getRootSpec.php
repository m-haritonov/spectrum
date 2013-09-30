<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands\commands\internal;

use spectrum\config;

function getRootSpec()
{
	static $rootSpec;
	if ($rootSpec === null)
	{
		$specClass = config::getSpecClass();
		$rootSpec = new $specClass;
		
		$callBrokerClass = config::getConstructionCommandCallBrokerClass();
		$callBrokerClass::internal_loadBaseMatchers($rootSpec);
	}
				
	return $rootSpec;
}