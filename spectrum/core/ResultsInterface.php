<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core;

interface ResultsInterface {
	public function __construct(SpecInterface $ownerSpec);
	
	/**
	 * @return SpecInterface
	 */
	public function getOwnerSpec();
	
	/**
	 * @param null|bool $result
	 * @param mixed $details
	 */
	public function add($result, $details = null);
	
	/**
	 * @return array
	 */
	public function getAll();

	/**
	 * @return null|bool
	 */
	public function getTotal();
}