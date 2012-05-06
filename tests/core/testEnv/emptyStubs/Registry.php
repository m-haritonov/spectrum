<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\testEnv\emptyStubs;

class Registry implements \spectrum\core\RegistryInterface
{
	static public function getRunningSpecItem(){}
	static public function setRunningSpecItem(\spectrum\core\SpecItemInterface $instance = null){}

	static public function getRunningSpecContainer(){}
	static public function setRunningSpecContainer(\spectrum\core\SpecContainerInterface $instance = null){}

	static public function getWorld(){}
	static public function setWorld(\spectrum\core\WorldInterface $instance = null){}
}