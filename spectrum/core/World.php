<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core;

class World implements WorldInterface, \Countable, \ArrayAccess
{
	public function count()
	{
		return count((array) $this);
	}

	public function offsetSet($key, $value)
	{
		$this->$key = $value;
	}

	public function offsetExists($key)
	{
		return property_exists($this, $key);
	}

	public function offsetUnset($key)
	{
		unset($this->$key);
	}

	public function offsetGet($key)
	{
		return $this->$key;
	}
}