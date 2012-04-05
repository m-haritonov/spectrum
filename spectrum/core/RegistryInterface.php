<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core;

interface RegistryInterface
{
	static public function getRunningSpecItem();
	static public function setRunningSpecItem(SpecItemInterface $instance = null);
}