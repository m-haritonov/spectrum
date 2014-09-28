<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\plugins;

class Matchers extends \spectrum\core\plugins\Plugin {
	/**
	 * @var array
	 */
	protected $items = array();

	/**
	 * @return string
	 */
	static public function getAccessName() {
		return 'matchers';
	}

	/**
	 * @param string $name
	 * @param callable $function
	 */
	public function add($name, $function) {
		$this->handleModifyDeny(__FUNCTION__);
		$this->items[$name] = $function;
	}

	/**
	 * @param string $name
	 * @return null|callable
	 */
	public function get($name) {
		if (isset($this->items[$name])) {
			return $this->items[$name];
		} else {
			return null;
		}
	}

	/**
	 * @param string $name
	 * @return null|callable
	 */
	public function getThroughRunningAncestors($name) {
		return $this->callMethodThroughRunningAncestorSpecs('get', array($name));
	}

	/**
	 * @return array
	 */
	public function getAll() {
		return $this->items;
	}

	/**
	 * @param string $name
	 */
	public function remove($name) {
		$this->handleModifyDeny(__FUNCTION__);
		unset($this->items[$name]);
	}
	
	public function removeAll() {
		$this->handleModifyDeny(__FUNCTION__);
		$this->items = array();
	}
}