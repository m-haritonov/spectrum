<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\constructionCommands;

use spectrum\config;

final class callBroker implements callBrokerInterface
{
	// For abstract class imitation (using "abstract" keyword with "final" not allowed)
	private function __construct(){}
	
	static public function __callStatic($constructionCommandName, array $arguments = array())
	{
		if (!config::isLocked())
			config::lock();
	
		return call_user_func_array(\spectrum\config::getRegisteredConstructionCommandFunction($constructionCommandName), $arguments);	
	}
}