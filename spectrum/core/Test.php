<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core;

use spectrum\config;

class Test implements TestInterface {
	/**
	 * @var null|\Closure
	 */
	protected $function;

	/**
	 * @var SpecInterface
	 */
	protected $ownerSpec;
	
	public function __construct(SpecInterface $ownerSpec) {
		$this->ownerSpec = $ownerSpec;
	}
	
	/**
	 * @param \Closure $function
	 */
	public function setFunction($function) {
		$handleSpecModifyDenyFunction = config::getFunctionReplacement('\spectrum\_internals\handleSpecModifyDeny');
		$handleSpecModifyDenyFunction($this->ownerSpec, $this, __FUNCTION__);
		
		$this->function = $function;
	}

	/**
	 * @return null|\Closure
	 */
	public function getFunction() {
		return $this->function;
	}

	/**
	 * @return null|\Closure
	 */
	public function getFunctionThroughRunningAncestors() {
		$callMethodThroughRunningAncestorSpecsFunction = config::getFunctionReplacement('\spectrum\_internals\callMethodThroughRunningAncestorSpecs');
		return $callMethodThroughRunningAncestorSpecsFunction($this->ownerSpec, 'getTest->getFunction');
	}
}