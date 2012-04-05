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