<?php
/*
 * Spectrum
 *
 * Copyright (c) 2011 Mikhail Kharitonov <mvkharitonov@gmail.com>
 * All rights reserved.
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 */

namespace spectrum\core;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
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