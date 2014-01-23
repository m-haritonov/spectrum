<?php
/*
This file is part of the Spectrum Framework (http://spectrum-framework.org/).
For the copyright and license information, see the LICENSE.txt file that was
distributed with this source code.
*/

namespace spectrum\core\plugins\basePlugins\contexts;

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