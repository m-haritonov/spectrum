<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core;

class Tools
{
	static public function callClosureInWorld($closure, $arguments, $world)
	{
		// Available in php 5.4
		if (method_exists($closure, 'bindTo'))
		{
			$closure = $closure->bindTo($world);
			if (!$closure)
				throw new Exception('Can\'t bind "$this" variable to world');
		}

		$registryClass = \spectrum\core\Config::getRegistryClass();
		$worldBackup = $registryClass::getWorld();
		$registryClass::setWorld($world);

		$result = call_user_func_array($closure, $arguments);

		$registryClass = \spectrum\core\Config::getRegistryClass();
		$registryClass::setWorld($worldBackup);

		return $result;
	}
}