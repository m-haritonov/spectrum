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
	static $initialSpec;
	if ($initialSpec === null)
	{
		$specClass = config::getSpecClass();
		$initialSpec = new $specClass;
		
		$callBrokerClass = config::getConstructionCommandsCallBrokerClass();
		$callBrokerClass::internal_loadBaseMatchers($initialSpec);
	}
				
	return $initialSpec;
}