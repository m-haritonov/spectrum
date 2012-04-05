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

namespace spectrum\matchers;

interface ManagerInterface
{
	static public function addAllMatchersToSpec(\spectrum\core\SpecInterface $spec);
	static public function addBaseMatchersToSpec(\spectrum\core\SpecInterface $spec);
}