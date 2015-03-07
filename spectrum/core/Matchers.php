<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core;

use spectrum\config;

class Matchers implements MatchersInterface {
	/**
	 * @var array
	 */
	protected $items = array();

	/**
	 * @var SpecInterface
	 */
	protected $ownerSpec;
	
	public function __construct(SpecInterface $ownerSpec) {
		$this->ownerSpec = $ownerSpec;
	}
	
	/**
	 * @param string $name
	 * @param callable $function
	 */
	public function add($name, $function) {
		$handleSpecModifyDenyFunction = config::getFunctionReplacement('\spectrum\_internals\handleSpecModifyDeny');
		$handleSpecModifyDenyFunction($this->ownerSpec, $this, __FUNCTION__);
		
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
		$callMethodThroughRunningAncestorSpecsFunction = config::getFunctionReplacement('\spectrum\_internals\callMethodThroughRunningAncestorSpecs');
		return $callMethodThroughRunningAncestorSpecsFunction($this->ownerSpec, 'getMatchers->get', array($name));
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
		$handleSpecModifyDenyFunction = config::getFunctionReplacement('\spectrum\_internals\handleSpecModifyDeny');
		$handleSpecModifyDenyFunction($this->ownerSpec, $this, __FUNCTION__);
		
		unset($this->items[$name]);
	}
	
	public function removeAll() {
		$handleSpecModifyDenyFunction = config::getFunctionReplacement('\spectrum\_internals\handleSpecModifyDeny');
		$handleSpecModifyDenyFunction($this->ownerSpec, $this, __FUNCTION__);
		
		$this->items = array();
	}
}