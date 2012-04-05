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

namespace spectrum\core\testEnv\emptyStubs\asserts;

/**
 * @property not
 */
class Assert implements \spectrum\core\asserts\AssertInterface
{
	public function __construct($actualValue){}
	public function __call($name, array $expectedArgs = array()){}
	public function __get($name){}

	public function getActualValue(){}
	public function isNot(){}
	public function invertNot(){}
	public function resetNot(){}
}