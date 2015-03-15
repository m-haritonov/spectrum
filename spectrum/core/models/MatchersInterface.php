<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\models;

interface MatchersInterface {
	public function __construct(SpecInterface $ownerSpec);
	
	/**
	 * @param string $name
	 * @param callable $function
	 */
	public function add($name, $function);

	/**
	 * @param string $name
	 * @return null|callable
	 */
	public function get($name);

	/**
	 * @param string $name
	 * @return null|callable
	 */
	public function getThroughRunningAncestors($name);

	/**
	 * @return array
	 */
	public function getAll();

	/**
	 * @param string $name
	 */
	public function remove($name);
	public function removeAll();
}