<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core;
use spectrum\core\SpecInterface;

/**
 * @property not
 */
interface AssertInterface
{
	public function __construct(SpecInterface $ownerSpec, $testedValue);
	public function __call($name, array $matcherArguments = array());
	public function __get($name);
}