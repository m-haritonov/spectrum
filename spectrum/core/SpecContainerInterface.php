<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core;

interface SpecContainerInterface extends SpecInterface
{
	public function addSpec(SpecInterface $spec);
	public function getSpecs();
	/**
	 * Should be return enabled and disabled specs.
	 */
	public function getSpecsToRun();
	public function removeSpec(SpecInterface $spec);
}