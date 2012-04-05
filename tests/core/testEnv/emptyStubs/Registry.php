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

class Registry implements \spectrum\core\RegistryInterface
{
	static public function getRunningSpecItem(){}
	static public function setRunningSpecItem(\spectrum\core\SpecItemInterface $instance = null){}
}