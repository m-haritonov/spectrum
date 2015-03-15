<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\models;

interface ResultsInterface {
	public function __construct(SpecInterface $ownerSpec);
	
	/**
	 * @return SpecInterface
	 */
	public function getOwnerSpec();
	
	/**
	 * @param null|bool $value
	 * @param mixed $details Exception object, some string, backtrace info, etc.
	 */
	public function add($value, $details = null);
	
	/**
	 * @return ResultInterface[]
	 */
	public function getAll();

	/**
	 * @return null|bool
	 */
	public function getTotal();
}