<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace spectrum\tests\testHelpers\emptyStubs\core;

use spectrum\core\SpecInterface;

class Spec implements \spectrum\core\SpecInterface
{
	public function __get($pluginAccessName){}
	public function dispatchPluginEvent($eventName, array $args = array()){}
	
	public function enable(){}
	public function disable(){}
	public function isEnabled(){}
	
	public function setName($name){}
	public function getName(){}
	public function isAnonymous(){}

	public function isRoot(){}
	public function getRootSpec(){}
	public function getRunningParentSpec(){}
	public function getRunningAncestorSpecs(){}
	public function getParentSpecs(){}
	public function hasParentSpec(SpecInterface $spec){}
	public function bindParentSpec(SpecInterface $spec){}
	public function unbindParentSpec(SpecInterface $spec){}
	public function unbindAllParentSpecs(){}

	public function getChildSpecs(){}
	public function hasChildSpec(SpecInterface $spec){}
	public function bindChildSpec(SpecInterface $spec){}
	public function unbindChildSpec(SpecInterface $spec){}
	public function unbindAllChildSpecs(){}

	public function run(){}
	public function isRunning(){}
}