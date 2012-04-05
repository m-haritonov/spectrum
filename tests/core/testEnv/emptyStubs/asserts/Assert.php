<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
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