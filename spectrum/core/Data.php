<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core;

class Data implements DataInterface {
	/**
	 * @return int
	 */
	public function count() {
		return count((array) $this);
	}

	/**
	 * @param mixed $key
	 * @param mixed $value
	 */
	public function offsetSet($key, $value) {
		$this->$key = $value;
	}

	/**
	 * @param mixed $key
	 * @return bool
	 */
	public function offsetExists($key) {
		return property_exists($this, $key);
	}

	/**
	 * @param mixed $key
	 */
	public function offsetUnset($key) {
		unset($this->$key);
	}

	/**
	 * @param mixed $key
	 * @return mixed
	 */
	public function offsetGet($key) {
		return $this->$key;
	}
}