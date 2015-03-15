<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core\models;

interface ContextModifiersInterface {
	public function __construct(SpecInterface $ownerSpec);
	
	/**
	 * @param callable $function
	 * @param string $type "before" or "after"
	 */
	public function add($function, $type = 'before');

	/**
	 * @param null|string $type null or "before" of "after"
	 * @return array
	 */
	public function getAll($type = null);
	
	/**
	 * @param string $type When type if "before": order is from parent to child; when type is "after": order is from child to parent
	 * @return array
	 */
	public function getAllThroughRunningAncestors($type = 'before');

	/**
	 * @param int $index
	 */
	public function remove($index);
	public function removeAll();
}