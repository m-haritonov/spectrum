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

namespace spectrum\core\asserts;

/**
 * @author Mikhail Kharitonov <mvkharitonov@gmail.com>
 * @link   http://www.mkharitonov.net/spectrum/
 * @property not
 */
interface AssertInterface
{
	public function __construct($actualValue);
	public function __call($name, array $expectedArgs = array());
	public function __get($name);

	public function getActualValue();
	public function isNot();
	public function invertNot();
	public function resetNot();
}