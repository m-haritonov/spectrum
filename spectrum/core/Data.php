<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core;

class Data implements DataInterface
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