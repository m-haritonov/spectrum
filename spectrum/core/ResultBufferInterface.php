<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core;

interface ResultBufferInterface {
	public function __construct(\spectrum\core\SpecInterface $ownerSpec);
	
	/**
	 * @return SpecInterface
	 */
	public function getOwnerSpec();
	
	/**
	 * @param null|bool $result
	 * @param mixed $details
	 */
	public function addResult($result, $details = null);
	
	/**
	 * @return array
	 */
	public function getResults();

	/**
	 * @return null|bool
	 */
	public function getTotalResult();
	
	public function lock();
	
	/**
	 * @return bool
	 */
	public function isLocked();
}