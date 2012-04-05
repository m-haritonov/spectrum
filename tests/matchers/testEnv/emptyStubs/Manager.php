<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\matchers\testEnv\emptyStubs;

class Manager implements \spectrum\matchers\ManagerInterface
{
	static public function addAllMatchersToSpec(\spectrum\core\SpecInterface $spec){}
	static public function addBaseMatchersToSpec(\spectrum\core\SpecInterface $spec){}
}