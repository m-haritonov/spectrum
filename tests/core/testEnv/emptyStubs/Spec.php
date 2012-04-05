<?php
/*
 * (c) Mikhail Kharitonov <mvkharitonov@gmail.com>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\core\testEnv\emptyStubs;

class Spec implements \spectrum\core\SpecInterface
{
	public function __construct($name = null){}
	public function __get($pluginAccessName){}
	public function setName($name){}
	public function getName(){}
	public function isAnonymous(){}
	public function setParent(\spectrum\core\SpecContainerInterface $spec = null){}
	public function getParent(){}
	public function callPlugin($pluginAccessName){}
	public function enable(){}
	public function disable(){}
	public function isEnabled(){}
	public function run(){}
	public function isRunning(){}
}