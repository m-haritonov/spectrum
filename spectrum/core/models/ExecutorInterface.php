<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\models;

interface ExecutorInterface {
	public function __construct(SpecInterface $ownerSpec);
	
	/**
	 * @param \Closure $function
	 */
	public function setFunction($function);

	/**
	 * @return null|\Closure
	 */
	public function getFunction();

	/**
	 * @return null|\Closure
	 */
	public function getFunctionThroughRunningAncestors();
}