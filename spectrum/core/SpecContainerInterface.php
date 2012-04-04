<?php
/*
 * Spectrum
 *
 * Copyright (c) 2011 Mikhail Kharitonov <mvkharitonov@gmail.com>
 * All rights reserved.
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 */

namespace spectrum\core;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 */
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